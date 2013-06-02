<?php namespace Andrew13\Cabinet;

use Carbon\Carbon;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CabinetUpload
{
    public $app;

    public function __construct() {
        parent::__construct();

        if ( is_null($this->app) )
            $this->app = app();
    }

    /**
     * Upload a file.
     * Handles folder creation.
     * @param $filename
     * @param $contents
     * @return array
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function upload($filename, $contents)
    {
        // Check the upload type is valid
        $this->verifyUploadType($contents);

        // Get the folder for uploads
        $folder = $this->getUploadFolder();

        // Check to see if the upload folder exists
        if (! File::exists($folder)) {
            // Try and create it
            if (! File::makeDirectory($folder)) {
                throw new FileException('Directory is not writable. Please make upload folder writable.');
            }
        }

        // Check that the folder is writable
        if (! File::isWritable($folder)) {
            throw new FileException('Folder is not writable.');
        }

        // Check to see if file exists already. If so append a random string.
        list($folder, $filename) = $this->resolveFileName($folder, $filename);

        // Upload the file to the folder
        if(! File::put($folder.$filename, $contents)) {
            throw new FileException('Upload failed.');
        }

        return array($folder, $filename);
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

        $path = $this->app['path.base'].$this->app['config']->get('cabinet::upload_folder');

        // Check to see if it ends in a slash
        if(substr($path, -1) != '/') {
            $path .= '/';
        }

        // Parse in to a folder format. 2013/03/30/{filename}.jpg
        return $path . str_replace(':','/',$date->toDateString()) . '/';
    }

    /**
     * Resolve whether the fiel exists and if it already does, change the file name.
     * @param $folder
     * @param $file
     * @return array
     */
    public function resolveFileName($folder, $file)
    {
        // If file exists append string and try again.
        if (File::isFile($folder.$file)) {
            $file .= '_' . rand()&7;
            list($folder, $file) = $this->resolveFileName($folder, $file);
        }

        return array($folder, $file);
    }

    /**
     * Checks the upload vs the upload types in the config.
     * @param $contents
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function verifyUploadType($contents)
    {
        if (! in_array(File::type($contents) ,$this->app['config']->get('cabinet::upload_file_types'))) {
            throw new FileException('Invalid upload type.');
        }
    }

}