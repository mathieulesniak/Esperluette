<?php
namespace Esperluette\Model\Blog;

use Esperluette\Model;

class Tag extends \Fwk\DBObject
{
    const TABLE_NAME    = 'blog_tags';
    const TABLE_INDEX   = 'id';
    
    public function __construct()
    {
        
        $this->dbVariables = array(
                                'id',
                                'tag'
                            );
    }

    public function getUrl()
    {
        return sprintf(
            '/tag/%s',
            urlencode($this->tag)
        );
    }
}
