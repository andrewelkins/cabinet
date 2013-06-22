<?php namespace Andrew13\Cabinet;

use Carbon\Carbon;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use LaravelBook\Ardent\Ardent;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CabinetUpload extends Ardent
{
    public static $app;

    /**
     * Create a new CabinetUpload instance.
     */
    public function __construct( array $attributes = array() )
    {
        parent::__construct( $attributes );

        if ( ! static::$app )
            static::$app = app();
    }

    /**
     * Upload a file.
     * Handles folder creation.
     * @param UploadedFile $file
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @return array
     */
    public function upload(UploadedFile $file)
    {
        // Check the upload type is valid by extension and mimetype
        $this->verifyUploadType($file);

        // Get the folder for uploads
        $folder = $this->getUploadFolder();

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

        // Check file size
        if ($file->getSize() > static::$app['config']->get('cabinet::max_upload_file_size')) {
            throw new FileException('File is too big.');
        }

        // Check to see if file exists already. If so append a random string.
        list($folder, $file) = $this->resolveFileName($folder, $file);

        // Upload the file to the folder
        if(! File::put($folder.$file->fileSystemName, $file)) {
            throw new FileException('Upload failed.');
        }

        // If it returns an array it's a successful upload. Otherwise an exception will be thrown.
        return array($folder, $file->fileSystemName);
    }

    /**
     * Get upload path with date folders
     * @param $date
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

        // Check to see if it begins in a slash
        if(substr($path, 0) != '/') {
            $path = '/' . $path;
        }

        // Check to see if it ends in a slash
        if(substr($path, -1) != '/') {
            $path .= '/';
        }

        // Add the project base to the path
        $path = static::$app['path.base'].$path;

        // Parse in to a folder format. 2013:03:30 -> 2013/03/30/{filename}.jpg
        return $path . str_replace('-','/',$date->toDateString()) . '/';
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
            $file = basename($file->fileSystemName, $file->getClientOriginalExtension()) . '_' . md5( uniqid(mt_rand(), true) ) . '.' . $file->getClientOriginalExtension();
        }

        // If file exists append string and try again.
        if (File::isFile($folder.$file->fileSystemName)) {
            // Default file postfix
            $i = '0000';

            // Get the file bits
            $basename = basename($file->fileSystemName, $file->getClientOriginalExtension());
            // Remove trailing period
            $basename = (substr($basename, -1) == '.' ? substr($basename,0,strlen($basename)-1) : $basename);
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
                    for ($c=$b; $c <= 4; $c++) {
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

}