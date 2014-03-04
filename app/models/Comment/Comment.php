<?php
namespace App\Models\Comment;

use App\Models;

class Comment extends \Suricate\DBObject
{
    const TABLE_NAME    = 'blog_comments';
    const TABLE_INDEX   = 'id';

    const STATUS_REGULAR    = 1;
    const STATUS_SPAM       = 2;

    public function __construct()
    {
        $this->dbVariables = array(
                                'id',
                                'parent_id',
                                'author',
                                'site',
                                'email',
                                'comment',
                                'ip_address',
                                'date_added',
                                'status',
                                'online'
                            );
    }

    public function markAsSpam()
    {
        $this->status = self::STATUS_SPAM;
        /**
         TODO : do we need to save here or delegate ->save() to controller ?
         */
        $this->save();

        return $this;
    }

    public function markAsRegular()
    {
        /**
         TODO : do we need to save here or delegate ->save() to controller ?
         */
        $this->status = self::STATUS_REGULAR;
        $this->save();

        return $this;
    }

    public function getAvatarUrl()
    {
        return 'http://www.gravatar.com/avatar/' . md5($this->email);
    }
}
