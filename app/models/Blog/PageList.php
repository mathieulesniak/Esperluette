<?php
namespace App\Models\Blog;

use App\Models;

class PageList extends PostList
{
    const TABLE_NAME    = 'blog_pages';
    const ITEM_TYPE     = '\App\Models\Blog\Page';
}
