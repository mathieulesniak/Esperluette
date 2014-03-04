<?php
namespace Esperluette\View\Admin\Comment;

use Esperluette\Model;
use Esperluette\View;
use Esperluette\Model\Helper;


class Homepage extends \Esperluette\View\PaginatedAdmin
{
    protected $section = 'comment';

    public function render($content = '')
    {
        $output  = '<h1>' . Helper::i18n('admin.comments') . '</h1>';
        
        if (count($this->model)) {
            $output .= '<ul class="comment-list">';
            foreach ($this->model as $currentCategory) {
                $output .= '<li>';
                
                $output .= '</li>';
            }
            $output .= '</ul>';
        }
        $output .= $this->renderPagination();
        
        return parent::render($output);
    }
}
