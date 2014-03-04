<?php
namespace Esperluette\View\Admin\User;

use Esperluette\Model;
use Esperluette\Model\Helper;

class Homepage extends \Esperluette\View\PaginatedAdmin
{

    protected $section = 'user';

    public function render($content = '')
    {
        /**
         TODO : check permission
         */
        
        $output  = '<h1>' . Helper::i18n('admin.users') . '</h1>';

        $output .= '<div class="action">';
        $output .= '    <a href="' . url('/admin/users/add') . '" class="btn btn-primary">' . Helper::i18n('admin.users.add') . '</a>';
        $output .= '</div>';
        $output .= '<div class="well">';
        $output .= '    <ul class="user-list">'."\n";
        foreach ($this->model as $currentUser) {
            $output .= '<li>';
            $output .= '    <div class="username">' . htmlentities($currentUser->nickname) . '</div>';
            $output .= '    <div class="realname">' . htmlentities($currentUser->first_name . ' ' . $currentUser->last_name) . '</div>';
            $output .= '    <div class="mail">' .  htmlentities($currentUser->email) . '</div>';
            $output .= '    <div class="level">' . Helper::i18n('admin.users.level_' . $currentUser->level) . '</div>';
            $output .= '    <div class="status">' . Helper::i18n('admin.users.status_' . $currentUser->active) . '</div>';

            $output .= '    <div class="post-count">' . count($currentUser->posts) . '</div>';
            $output .= '    <div class="action">';
            $output .= '        <a href="' . url('/admin/users/edit/' . $currentUser->id) . '" class="btn btn-info">' . Helper::i18n('admin.users.edit') . '</a>';
            $output .= '        <a href="' . url('/admin/users/delete/' . $currentUser->id) . '" class="btn btn-danger">' . Helper::i18n('admin.users.delete') . '</a>';
            $output .= '    </div>';
            $output .= '</li>';
        }

        $output .= '    </ul>'."\n";
        $output .= '</div>';

        return parent::render($output);
    }
}
