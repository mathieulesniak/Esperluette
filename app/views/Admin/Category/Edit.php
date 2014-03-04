<?php
namespace Esperluette\View\Admin\Category;

use Esperluette\Model;
use Esperluette\Model\Blog\CategoryList;
use Esperluette\View;
use Esperluette\Model\Helper;
use Fwk\Fwk;
use Fwk\FormItem;


class Edit extends \Esperluette\View\Admin
{
    protected $section = 'category';

    public function render($content = '')
    {
        $formValues = array(
            'name'          => Fwk::Request()->getPostParam('name', $this->model->name),
            'slug'          => Fwk::Request()->getPostParam('slug', $this->model->slug),
            'description'   => Fwk::Request()->getPostParam('description', $this->model->description),
            'parent_id'     => Fwk::Request()->getPostParam('parent_id', $this->model->parent_id),
        );

        if ($this->model->id !== null) {
            $output  = '<h1>' . Helper::i18n('admin.categories.edit') . '</h1>';
        } else {
            $output  = '<h1>' . Helper::i18n('admin.categories.add') . '</h1>';
        }
        $output .= '<div class="well">';
        $output .= '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">'."\n";
        $output .= '    <p class="controls">';
        $output .=          FormItem::text('name', $formValues['name'], Helper::i18n('admin.categories.category_name'));
        $output .= '    </p>';
        $output .= '    <p class="controls">';
        $output .=          FormItem::text('slug', $formValues['slug'], Helper::i18n('admin.categories.slug'));
        $output .= '    </p>';
        $output .= '    <p class="controls">';
        $output .=          FormItem::textarea('description', $formValues['description'], Helper::i18n('admin.categories.description'));
        $output .= '    </p>';
        $output .= '    <p class="controls">';
        $categoriesList = CategoryList::loadAll()->generateTree()->getAsArray();
        array_unshift($categoriesList, Helper::i18n('admin.categories.no_parent'));
        unset($categoriesList[$this->model->id]);
        $output .=          FormItem::select(
            'parent_id',
            $categoriesList,
            $formValues['parent_id'],
            Helper::i18n('admin.categories.parent_id')
        );
        $output .= '    </p>';
        $output .=      FormItem::submit('save_category', Helper::i18n('admin.categories.save'));
        $output .= '</form>';
        $output .= '</div>';
        
        return parent::render($output);
    }
}
