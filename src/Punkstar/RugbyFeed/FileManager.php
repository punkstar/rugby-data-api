<?php

namespace Punkstar\RugbyFeed;

class FileManager
{
    public function getFileFromUrl($url)
    {
        $filename = $this->generateFilename($url);

        if (!$this->doesFileExist($filename)) {
            $this->createFileFromUrl($filename, $url);
        }

        return $this->getFileContents($filename);
    }

    protected function generateFilename($url)
    {
        $cache_dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . str_replace('\\', '_', __CLASS__);
        $filename = sha1($url);

        @mkdir($cache_dir);

        return $cache_dir . DIRECTORY_SEPARATOR . $filename;
    }

    protected function doesFileExist($filename)
    {
        return file_exists($filename);
    }

    protected function createFileFromUrl($filename, $url)
    {
        file_put_contents($filename, file_get_contents($url));
    }

    protected function getFileContents($filename)
    {
        return file_get_contents($filename);
    }
}
