<?php
namespace App\Model\Meta;

use App\Model;

class Meta extends \Fwk\DBObject
{
    const TABLE_NAME    = 'blog_metas';
    const TABLE_INDEX   = 'key';

    public function __construct()
    {
        $this->dbVariables = array(
                                'key',
                                'value'
                            );
        $this->protectedVariables = array();
    }
}
