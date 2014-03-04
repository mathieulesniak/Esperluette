<?php
namespace App\Models\Blog;

class TagList extends \Suricate\CollectionMapping
{
    const TABLE_NAME                    = 'blog_tags';
    const ITEM_TYPE                     = '\Esperluette\Model\Blog\Tag';
    const SQL_RELATION_TABLE_NAME       = 'blog_tags_mapping';
    const MAPPING_ID_NAME               = 'tag_id';
    const PARENT_ID_NAME                = 'post_id';

    public function getAjaxTagList($criteria)
    {
        $sql  = "SELECT *";
        $sql .= " FROM " . self::TABLE_NAME;
        $sql .= " WHERE";
        $sql .= "   tag LIKE :tag";

        $sqlParams = array(':tag' => '%' . $criteria . '%');

        $this->loadFromSql($sql, $sqlParams);

        $result = array('tags' => array());
        foreach ($this->getPossibleValuesFor('tag') as $currentTag) {
            $result['tags'][] = array('tag' => $currentTag);
        }
        return json_encode($result);
    }
}
