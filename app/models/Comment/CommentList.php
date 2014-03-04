<?php
namespace App\Models\Comment;

use App\Models;

class CommentList extends \Suricate\Collection
{
    const TABLE_NAME    = 'blog_comments';
    const ITEM_TYPE     = '\App\Models\Comment\Comment';
}
