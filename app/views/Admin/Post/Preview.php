<?php
namespace Esperluette\View\Admin\Post;

use Esperluette\Model;
use Experluette\View;
use Esperluette\Model\Helper;


class Preview extends \Esperluette\View\Admin
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
