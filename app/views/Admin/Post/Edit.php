<?php
namespace App\Views\Admin\Post;

use App\Models;
use App\Views;
use App\Models\Helper;
use Fwk\FormItem;

class Edit extends \App\Views\Admin
{
    public function render($content = '')
    {   
        $editMode = ($this->model->id !== null);
        $titleHtmlProperties = array(
            'placeholder'   => Helper::i18n('admin.post.title'),
            'autofocus'     => 'true',
            'autocomplete'  => 'off'
        );
        $contentHtmlProperties  = array();
        $tagsHtmlProperties     = array();

        if ($editMode) {
            $this->setTitle(Helper::i18n('admin.post.edit', $this->model->title));
        } else {
            $this->setTitle(Helper::i18n('admin.post.create'));
        }

        $output  = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">'."\n";
        $output .= '    <fieldset class="top">'."\n";
        $output .=          FormItem::text('post[title]', $this->model->title, null, $titleHtmlProperties);
        $output .= '    </fieldset>'."\n";
        $output .= '    <fieldset class="middle">'."\n";
        $output .=          FormItem::textarea('post[content]', $this->model->content, null, $contentHtmlProperties);
        $output .= '        <div id="blogpreview"></div>'."\n";
        $output .= '    </fieldset>'."\n";
        $output .= '    <fieldset class="bottom">'."\n";
        $output .=          FormItem::text('post[tags]', $this->model->tags, null, $tagsHtmlProperties);
        $output .= '        <div class="icon icon-gears"></div>' ."\n";
        $output .= '        <div class="btn">Action</div>'."\n";
        $output .= '    </fieldset>'."\n";
        $output .= '</form>'."\n";

        return parent::render($output);
    }
}
