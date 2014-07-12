<?php
namespace App\Views\Admin\Post;

use App\Models;
use App\Views;
use App\Models\Helper;


class Preview extends \App\Views\Admin
{
    protected $removeTemplate = true;
    public function render($content = '')
    {   
        $output  = '<div class="toolbar">';
        $output .= '    TOOLBAR PREVIEW MODE GOES HERE';
        $output .= '    <a href="' . url('/admin/posts/edit/' . $this->model->id) . '">Edit</a>';
        $output .= '    <a href="' . $this->model->getUrl() . '">View</a></div>'."\n";
        $output .= '<div class="post">';
        $output .= $this->model->renderIntro();
        $output .= '<br/>';
        $output .= $this->model->renderContent();
        $output .= '</div>'."\n";
        
        return parent::render($output);
    }
}
