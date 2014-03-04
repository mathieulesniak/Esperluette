<?php
namespace App\Models\Meta;

use App\Models;

class MetaList extends \Suricate\Collection
{
    const TABLE_NAME        = 'blog_metas';
    const ITEM_TYPE         = '\App\Models\Meta\Meta';
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
