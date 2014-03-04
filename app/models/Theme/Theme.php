<?php
namespace Esperluette\Model\Theme;

use Fwk\Fwk;

class Theme
{
    private $themeData = array(
        'name'              => '',
        'themeUri'          => '',
        'author'            => '',
        'authorUri'         => '',
        'authorEmail'       => '',
        'description'       => '',
        'version'           => '',
        'screenshot'        => '',
        );
    private $hasScreenshot = false;
    public $dirName;


    public function __construct($themeName = '')
    {
        if ($themeName != '') {
            $this->load($themeName);
        }
    }

    public function __get($variable) {
        if (isset($this->themeData[$variable])) {
            return $this->themeData[$variable];
        } else {
            return null;
        }
    }

    public function load($themeName)
    {
        $metaFile       = THEME_DIR . DIRECTORY_SEPARATOR . $themeName . DIRECTORY_SEPARATOR . 'info.txt';
        $screenshotFile = THEME_DIR . DIRECTORY_SEPARATOR . $themeName . DIRECTORY_SEPARATOR . 'screenshot.png';

        if (is_readable($metaFile)) {
            $this->dirName = $themeName;
            $this->extractInfos($metaFile);

            if (is_readable($screenshotFile)) {
                $this->hasScreenshot = true;
                $this->screenshot = Fwk::App()->getParameter('url') . '/' . $screenshotFile;
            }
        }
    }

    private function extractInfos($filename)
    {
        $fileContent = explode("\n", file_get_contents($filename));
        foreach ($fileContent as $line) {
            foreach ($this->themeData as $meta => $value) {
                if (preg_match('|^' . $meta . ':(.*)$|i', $line, $matches)) {
                    $this->themeData[$meta] = $matches[1];
                }
            }
        }
        if ($this->name == '') {
            $this->name = $this->dirName;
        }
    }
}