<?php
namespace App\Models\User;

use App\Models;
use Esperluette\Model\Helper;
use Fwk;

class User extends \Suricate\DBObject
{
    const TABLE_NAME    = 'blog_users';
    const TABLE_INDEX   = 'id';

    const LEVEL_USER    = 0;
    const LEVEL_EDITOR  = 1;
    const LEVEL_ADMIN   = 2;

    const NAME_DISPLAY_NICKNAME = 0;
    const NAME_DISPLAY_REAL     = 1;

    public function __construct()
    {
        $this->dbVariables = array(
            'id',
            'nickname',
            'first_name',
            'last_name',
            'name_display',
            'email',
            'password',
            'active',
            'level'
        );
        $this->protectedVariables = array(
            'posts'
        );
        $this->posts = new Models\Blog\PostList();

    }

    protected function accessToProtectedVariable($propertyName)
    {
        switch ($propertyName) {
            case 'posts':
                $result = $this->loadPosts();
                break;
            default:
                $result = false;
                break;
        }

        return $result;
    }

    private function loadPosts()
    {
        if ($this->id != '') {
            $postList = new Models\Blog\PostList();
            $postList->loadForUserId($this->id);
            $this->posts = $postList;
        }

        return true;
    }

    public static function getLevels()
    {
        return array(
            self::LEVEL_USER    => Helper::i18n('admin.user.level_' . self::LEVEL_USER),
            self::LEVEL_EDITOR  => Helper::i18n('admin.user.level_' . self::LEVEL_EDITOR),
            self::LEVEL_ADMIN   => Helper::i18n('admin.user.level_' . self::LEVEL_ADMIN)
        );
    }

    public static function getNameDisplayOptions()
    {
        return array(
            self::NAME_DISPLAY_NICKNAME => Helper::i18n('admin.user.namedisplay_' . self::NAME_DISPLAY_NICKNAME),
            self::NAME_DISPLAY_REAL     => Helper::i18n('admin.user.namedisplay_' . self::NAME_DISPLAY_REAL)
        );
    }
}
