<?php
namespace Esperluette\Model\Theme;

class ThemeList implements \IteratorAggregate
{
    private $themes;

    public function __construct()
    {
        $this->loadThemes();
    }

    private function loadThemes()
    {
        $iterator = new \DirectoryIterator(THEME_DIR);
        foreach ($iterator as $currentFile) {
            if ($currentFile->isDir() 
                && !$currentFile->isDot()
                && is_readable($currentFile->getPathname() . DIRECTORY_SEPARATOR . 'info.txt')) {
                $this->themes[] = new Theme($currentFile->getFilename());
            }
        }

        uasort($this->themes, array($this, 'sortFunction'));
    }

    public function getIterator()
    {
        return new ArrayIterator($this->themes);

    }

    private function sortFunction($a, $b)
    {
        $first  = $a->name;
        $second = $b->name;

        if ($first === $second) {
            return 0;
        }

        return ($first < $second) ? -1 : +1;
    }

    public function getAsArray()
    {
        $result = array();
        foreach ($this->themes as $currentTheme) {
            $result[$currentTheme->dirName] =$currentTheme->name;
        }

        return $result;
    }
}
