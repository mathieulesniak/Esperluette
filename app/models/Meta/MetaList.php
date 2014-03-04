<?php
namespace Esperluette\Model\Meta;

use Esperluette\Model;
use Fwk\Fwk;

class MetaList extends \Fwk\Collection
{
    const TABLE_NAME        = 'blog_metas';
    const ITEM_TYPE         = '\Esperluette\Model\Meta\Meta';
    const PARENT_ID_NAME    = '';

    public function loadConfigurationMetas()
    {
        
    }

    public static function buildFromArray($values)
    {
        $model = new MetaList();
        foreach ($values as $key => $value) {
            $meta = new Meta();
            $meta->key      = $key;
            $meta->value    = $value;
            $model->addItem($meta);
        }

        return $model;
    }
}
