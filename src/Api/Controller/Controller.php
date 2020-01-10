<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Api\Controller;

/**
 * Class Controller
 * @package Team1\Api\Controller
 */
class Controller
{
    /**
     * @param string $file
     */
    public function displayHTML(string $file)
    {
        $myfile = file_get_contents($file);
        echo $myfile;
    }

    /**
     * @param $file
     */
    public function displayCSS($file)
    {
        $myfile = file_get_contents($file);
        $myfile = "<style>" . PHP_EOL . $myfile . "\n" . "</style>";
        echo $myfile;
    }
}
