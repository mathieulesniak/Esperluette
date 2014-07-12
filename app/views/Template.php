<?php
namespace App\Views;

use App\Models\Config;

class Template
{
    protected $templateFile;

    public function __construct($file)
    {
        $this->setTemplate($file);
    }

    public function setTemplate($file)
    {
        $this->templateFile = THEME_DIR . DIRECTORY_SEPARATOR . Config::get('theme', 'default') . DIRECTORY_SEPARATOR . $file . '.php';
    }

    public function exists($file)
    {
        return is_readable(THEME_DIR . DIRECTORY_SEPARATOR . Config::get('theme', 'default') . DIRECTORY_SEPARATOR . $file . '.php');
    }
    public function render()
    {
        ob_start();
        require $this->templateFile;
        return ob_get_clean();
    }
}