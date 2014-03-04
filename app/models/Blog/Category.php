<?php
namespace App\Models\Blog;

use App\Models;

class Category extends \Suricate\DBObject
{
    const TABLE_NAME    = 'blog_categories';
    const TABLE_INDEX   = 'id';
    public $depth;

    public function __construct()
    {
        $this->dbVariables = array(
                                'id',
                                'name',
                                'slug',
                                'description',
                                'parent_id'
                            );

        $this->protectedVariables = array(
            'posts'
            );

        $this->posts = new PostList();
    }

    protected function accessToProtectedVariable($property_name)
    {
        switch ($property_name) {
            case 'posts':
                $result = $this->loadPosts();
                break;
            default:
                $result = false;
                break;
        }

        return $result;
    }

    public function loadFromSlug($slug)
    {
        $sql  = "SELECT *";
        $sql .= " FROM `" . self::TABLE_NAME . "`";
        $sql .= " WHERE";
        $sql .= "   slug = :slug";

        $sqlParams = array('slug' => $slug);
        $this->loadFromSql($sql, $sqlParams);
    }

    private function loadPosts()
    {
        if ($this->id != '') {
            $postList = new PostList();
            $postList->loadForCategoryId($this->id);
            $this->posts = $postList;
        }

        return true;
    }
}
