<?php
namespace App\Views\Admin\Comment;

use App\Models;
use App\Views;
use App\Models\Helper;


class Homepage extends \App\Views\PaginatedAdmin
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
