<?php
namespace Esperluette\Model\Comment;

use Esperluette\Model;

class CommentList extends \Fwk\Collection
{
    const TABLE_NAME    = 'blog_comments';
    const ITEM_TYPE     = '\Esperluette\Model\Comment\Comment';
}
