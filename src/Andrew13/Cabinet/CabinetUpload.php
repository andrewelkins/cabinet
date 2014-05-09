<?php namespace Andrew13\Cabinet;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Image;

class CabinetUpload extends Eloquent
{
    public static $app;

    /**
     * Get the uploads owner.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    /**
     * Get children uploads
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('Upload', 'parent_id');
    }

    /**
     * Create a new CabinetUpload instance.
     */
    public function __construct( array $attributes = array() )
    {
        parent::__construct( $attributes );

        if ( ! static::$app )
            static::$app = app();
    }
    
    public function process(UploadedFile $file)
    {
        // File extension
        $this->extension = $file->getClientOriginalExtension();
        // Mimetype for the file
        $this->mimetype = $file->getMimeType();
        // Current user or 0
        $this->user_id = (Auth::user() ? Auth::user()->id : 0);

        $this->size = $file->getSize();

        list($this->path, $this->filename) = $this->upload($file);

        $this->save();

        // Check to see if image thumbnail generation is enabled
        if(static::$app['config']->get('cabinet::image_manipulation')) {
            $thumbnails = $this->generateThumbnails($this->path, $this->filename);
            $uploads = array();
            foreach($thumbnails as $thumbnail) {
                $upload = new $this;

                $upload->filename = $thumbnail->fileSystemName;

                $upload->path = static::$app['config']->get('cabinet::upload_folder_public_path').$thumbnail->fileSystemName;
                // File extension
                $upload->extension = $thumbnail->getClientOriginalExtension();
                // Mimetype for the file
                $upload->mimetype = $thumbnail->getMimeType();
                // Current user or 0
                $upload->user_id = $this->user_id;

                $upload->size = $thumbnail->getSize();

                $upload->parent_id = $this->id;

                $upload->save();

                $uploads[] = $upload;
            }
            $this->children = $uploads;
        }

    }

    /**
     * Upload a file.
     * Handles folder creation.
     * @param UploadedFile $file
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @return array
     */
    public function upload(UploadedFile &$file)
    {
        // Check the upload type is valid by extension and mimetype
        $this->verifyUploadType($file);

        // Get the folder for uploads
        $folder = $this->getUploadFolder();

        // Check file size
        if ($file->getSize() > static::$app['config']->get('cabinet::max_upload_file_size')) {
            throw new FileException('File is too big.');
        }

        // Check to see if file exists already. If so append a random string.
        list($folder, $file) = $this->resolveFileName($folder, $file);

        // Upload the file to the folder. Exception thrown from move.
        $file->move($folder, $file->fileSystemName);

        // If it returns an array it's a successful upload. Otherwise an exception will be thrown.
        return array($this->cleanPath(static::$app['config']->get('cabinet::upload_folder')), $file->fileSystemName);
    }

    /**
     * Get upload path with date folders
     * @param $date
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @throws \Doctrine\Common\Proxy\Exception\InvalidArgumentException
     * @return string
     */
    public function getUploadFolder($date=null)
    {
        // Check that a date was given
        if (is_null($date)) {
            $date = Carbon::now();
        } elseif (! is_a($date, 'Carbon')) {
            throw new InvalidArgumentException('Must me a Carbon object');
        }

        // Get the configuration value for the upload path
        $path = static::$app['config']->get('cabinet::upload_folder');

        $path = $this->cleanPath($path);

        // Add the project base to the path
        $path = static::$app['path.base'].$path;

        $folder = $path;

        // Check to see if the upload folder exists
        if (! File::exists($folder)) {
            // Try and create it
            if (! File::makeDirectory($folder, static::$app['config']->get('cabinet::upload_folder_permission_value'), true)) {
                throw new FileException('Directory is not writable. Please make upload folder writable.');
            }
        }

        // Check that the folder is writable
        if (! File::isWritable($folder)) {
            throw new FileException('Folder is not writable.');
        }

        return $folder;
    }

    /**
     * Resolve whether the file exists and if it already does, change the file name.
     * @param string $folder
     * @param $file
     * @param bool $enableObfuscation
     * @return array
     */
    public function resolveFileName($folder, UploadedFile $file, $enableObfuscation=true)
    {
        if(! isset($file->fileSystemName)) {
            $file->fileSystemName = $file->getClientOriginalName();
        }

        if(static::$app['config']->get('cabinet::obfuscate_filenames') && $enableObfuscation) {
            $fileName = basename($file->fileSystemName, $file->getClientOriginalExtension()) . '_' . md5( uniqid(mt_rand(), true) ) . '.' . $file->getClientOriginalExtension();
            $file->fileSystemName = $fileName;
        } else {
            $fileName = $file->fileSystemName;
        }
	}

        // If file exists append string and try again.
        if (File::isFile($folder.$fileName)) {
            // Default file postfix
            $i = '0000';

            // Get the file bits
            $basename = $this->getBasename($file);
            $basenamePieces = explode('_', $basename);

            // If there's more than one piece then let see if it's our counter.
            if (count($basenamePieces) > 1) {
                // Pop the last part of the array off.
                $last = array_pop($basenamePieces);
                // Check to see if the last piece is an int. Must be 4 long. This isn't the best, but it'll do in most cases.
                if (strlen($last) == 4 && (is_int($last) || ctype_digit($last))) {
                    // Add one, which converts this string to an int. Gotta love PHP ;)
                    $last += 1;
                    // Prepare to add the proper amount of 0's in front
                    $b = 4 - strlen($last);
                    $i = $last;
                    for ($c=1; $c <= $b; $c++) {
                        $i = '0' . $i;
                    }
                } else {
                    // Put last back on the array
                    array_push($basenamePieces, $last);
                }
                // Put the pieces back together without the postfix.
                $basename = implode('_', $basenamePieces);
            }

            // Create the filename
            $file->fileSystemName = $basename . '_' . $i . '.' . $file->getClientOriginalExtension();
            list($folder, $file) = $this->resolveFileName($folder, $file, false);
        }

        return array($folder, $file);
    }

    /**
     * Checks the upload vs the upload types in the config.
     * @param $file
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function verifyUploadType(UploadedFile $file)
    {
        if (! in_array($file->getMimeType() , static::$app['config']->get('cabinet::upload_file_types')) ||
            ! in_array(strtolower($file->getClientOriginalExtension()), static::$app['config']->get('cabinet::upload_file_extensions'))) {
            throw new FileException('Invalid upload type.');
        }
    }

    /**
     * Checks the upload vs the upload types in the config.
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return bool
     */
    public function verifyImageType($file)
    {
        if (in_array($file->getMimeType() , static::$app['config']->get('cabinet::image_file_types')) ||
            in_array(strtolower($file->getClientOriginalExtension()), static::$app['config']->get('cabinet::image_file_extensions'))) {
            return true;
        } else {
            return false;
        }
    }

    public function getBasename($file)
    {
        // Get the file bits
        $basename = basename((isset($file->fileSystemName) ? $file->fileSystemName : $file->getClientOriginalName()), $file->getClientOriginalExtension());
        // Remove trailing period
        return (substr($basename, -1) == '.' ? substr($basename,0,strlen($basename)-1) : $basename);
    }

    public function generateThumbnails($folder, $file)
    {
        $folder = static::$app['path.base'] . $folder;

        if( is_string($file) ) {
            $file = new UploadedFile($folder.$file, $file);
        }

        $thumbnails = array();

        // Check the image type is valid by extension and mimetype
        if($this->verifyImageType($file)) {
            $image = Image::make($folder . $file->getClientOriginalName());

            foreach(static::$app['config']->get('cabinet::image_resize') as $image_params) {

                $tempFile = clone $file;

                // Add image manipulation to file name.
                $tempFile->fileSystemName = $this->getBasename($file) . '_' .
                    ($image_params[0]!=null?$image_params[0]:'auto') .
                    'x' .
                    ($image_params[1]!=null?$image_params[1]:'auto') .
                    (isset($image_params[2])&&$image_params[2]==true?'_ratio':'') .
                    (isset($image_params[3])&&$image_params[3]==true?'_upsized':'') .
                    '.' . $file->getClientOriginalExtension();

                list($folder, $tempFile) = $this->resolveFileName($folder, $tempFile);

                // Have to clone since we'll be doing this multiple times.
                $clonedImage = clone $image;
                if(! isset($image_params[2])) {
                    $image_params[2] = false;
                }
                if(! isset($image_params[3])) {
                    $image_params[3] = true;
                }

                $clonedImage->resize(
                    $image_params[0],
                    $image_params[1],
                    $image_params[2],
                    $image_params[3]
                )->save($folder . $tempFile->fileSystemName);

                $thumbnails[] = $tempFile;

                // Image files can be big, free up memory.
                unset($clonedImage);
                unset($tempFile);
            }
        }

        return $thumbnails;
    }

    public function cleanPath($path)
    {
        // Check to see if it begins in a slash
        if(substr($path, 0) != '/') {
            $path = '/' . $path;
        }

        // Check to see if it ends in a slash
        if(substr($path, -1) != '/') {
            $path .= '/';
        }

        return $path;
    }
}
