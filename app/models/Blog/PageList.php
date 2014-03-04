<?php
namespace App\Models\Blog;

use App\Models;

class PageList extends PostList
{
    const TABLE_NAME    = 'blog_pages';
    const ITEM_TYPE     = '\Esperluette\Model\Blog\Page';
}
