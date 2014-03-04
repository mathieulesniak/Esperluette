<?php
namespace Esperluette\Model\Blog;

use Esperluette\Model;

class CategoryList extends \Fwk\Collection
{
    const TABLE_NAME    = 'blog_categories';
    const ITEM_TYPE     = '\Esperluette\Model\Blog\Category';

    private $tree;
    private $flattenedTree;

    public function generateTree()
    {
        $tree = array();
        foreach ($this->items as $currentItem) {
            $id         = $currentItem->id;
            $parent     = $currentItem->parent_id;

            if (!isset($tree[$id])) {
                $tree[$id] = array();
            }

            $tree[$id] = array_merge(array('id' => $id, 'name' => $currentItem->name), $tree[$id]) ;

            
            if (!isset($tree[$parent])) {
                $tree[$parent] = array();
            }
    
            $tree[$parent]['items'][$id] =& $tree[$id];
        
        }
 
        $this->tree = $tree[0]['items'];
        uasort($this->tree, array($this, 'sortTree'));

        $newCollection      = array();
        $newMapping         = array();
        $offset             = 0;

        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($this->tree));
        foreach ($it as $el) {
            if ($it->key() == 'id') {
                $newCollection[$offset] = $this->getItemFromKey($el);
                $newCollection[$offset]->depth = ($it->getDepth() - 1) / 2;
                $newMapping[$offset] = $newCollection[$offset]->id;

                $offset++;
            }
        }

        $this->items = $newCollection;
        $this->mapping = $newMapping;

        return $this;
    }

    public function getAsArray()
    {
        foreach ($this->items as $currentItem) {
            $result[$currentItem->id] = str_repeat('—', $currentItem->depth) . $currentItem->name;
        }
        
        return $result;
    }

    public function getChildren($id = null)
    {
        $result = array();

        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($this->tree));
        foreach ($it as $el) {
            if ($it->key() == 'id' && ($el == $id || $id === null)) {
                $subIterator = new \RecursiveIteratorIterator($it->getSubIterator());
                foreach ($subIterator as $subElement) {
                    if ($subIterator->key() == 'id') {
                        $result[] = $subElement;
                    }
                }
            }
        }

        return $result;
    }

    private function sortTree(&$a, &$b)
    {
        if (isset($a['items'])) {
            uasort($a['items'], array($this, 'sortTree'));
        }
        if ($a['name'] === $b['name']) {
            return 0;
        }
        return ($a['name'] < $b['name']) ? -1 : +1;
    }
}
