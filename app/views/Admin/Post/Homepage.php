<?php
namespace App\Views\Admin\Post;

use App\Models;
use App\Views;
use App\Models\Helper;
use Fwk\FormItem;

class Homepage extends \App\Views\PaginatedAdmin
{
    public function render($content = '')
    {   
        
        $output = '<ul class="postlist">'."\n";
        foreach ($this->model as $currentPost) {
            $output .= '<li>';
            $output .= '    <a href="#" onclick="adminPreviewPost(\'' . htmlentities($currentPost->id) . '\'); return false">';
            $output .= '        <div class="title">' . htmlentities($currentPost->title, ENT_COMPAT, 'UTF-8') . '</div>';
            $output .= '        <time>NICE TIME GOES HERE</time>';
            $output .= '        <div class="status">' . Helper::i18n('post.status.' . $currentPost->getStatus()) . '</div>'."\n";
            $output .= '    </a>';
            $output .= '</li>';
        }

        $output .= '</ul>'."\n";
        $output .= '<div id="post-preview"></div>';

        $output .= '<script type="text/javascript">'."\n";
        $output .= '    var appRoot = "' . url() . '";'."\n";
        $output .= '</script>'."\n";
        return parent::render($output);
    }
}
