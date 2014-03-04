<?php
namespace App\Models\Blog;

use App\Models;


class PostList extends \Suricate\Collection
{
    const TABLE_NAME    = 'blog_posts';
    const ITEM_TYPE     = '\App\Models\Blog\Post';

    public function loadOnline()
    {
        $sql            = '';
        $sqlParams      = array();

        $sql  = "SELECT *";
        $sql .= " FROM `" . self::TABLE_NAME . "`";
        $sql .= " WHERE";
        $sql .= "   online=1";
        $sql .= "   AND date <= :startDate";
        $sql .= " ORDER BY date DESC";

        $sqlParams['startDate'] = date('Y-m-d H:i:s');

        $this->lazyLoadFromSql($sql, $sqlParams);

        return $this;
    }

    public function loadForUserId($userId)
    {
        $sqlParams  = array();

        $sql  = "SELECT *";
        $sql .= " FROM `" . self::TABLE_NAME . "`";
        $sql .= " WHERE";
        $sql .="    author_id = :authorId";

        $sqlParams['authorId'] = $userId;

        $this->loadFromSql($sql, $sqlParams);

        return $this;
    }

    /**
     * Load all online post for a given month
     * Lazy load, no sort possible on collection
     * @param  int $year            Year
     * @param  int $month           Month
     * @return PostList             The post Collection
     */
    public function loadOnlineForMonth($year, $month)
    {
        $sql            = '';
        $sqlParams      = array();

        $sql  = "SELECT *";
        $sql .= " FROM `" . self::TABLE_NAME . "`";
        $sql .= " WHERE";
        $sql .= "   date >= :startDate";
        $sql .= "   AND date <= :endDate";
        $sql .= "   AND online=1";
        $sql .= " ORDER BY date DESC";

        $sqlParams['startDate'] = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year));
        $sqlParams['endDate']   = date('Y-m-d H:i:s', mktime(23, 59, 59, $month, date('t', strtotime($sqlParams['startDate'])), $year));

        $this->lazyLoadFromSql($sql, $sqlParams);

        return $this;
    }

    public function loadForKeyword($keyword)
    {
        $sql        = '';
        $sqlParams  = array();

        $sql  = "SELECT *, MATCH (title,intro,content) AGAINST (:criteria) AS result";
        $sql .= " FROM `" . self::TABLE_NAME . "`";
        $sql .= " WHERE";
        $sql .= "   MATCH (title,intro,content) AGAINST (:criteria)";
        $sql .= " ORDER BY result DESC";

        $sqlParams['criteria'] = $keyword;

        $this->lazyLoadFromSql($sql, $sqlParams);

        return $this;
    }

    /**
     * Load all online post for a given category
     * Lazy load, no sort possible on collection
     * @param  int $categoryId      Category id
     * @return PostList             The post Collection
     */
    public function loadOnlineForCategoryId($categoryId)
    {
        $sql            = '';
        $sqlParams      = array();

        $sql  = "SELECT *";
        $sql .= " FROM `" . self::TABLE_NAME . "`";
        $sql .= " WHERE cat_id=:categoryId";
        $sql .= "   AND online=1";
        $sql .= " ORDER BY date DESC";

        $sqlParams['categoryId'] = $categoryId;

        $this->lazyLoadFromSql($sql, $sqlParams);

        return $this;
    }


    public function loadForCategoryId($categoryId, $filterOnline = false, $lazyLoad = false)
    {
        $sql            = '';
        $sqlParams      = array();

        $sql  = "SELECT *";
        $sql .= " FROM `" . self::TABLE_NAME . "`";
        $sql .= " WHERE category_id=:categoryId";
        if ($filterOnline) {
            $sql .= " AND status=" . Post::STATUS_PUBLISHED;
        }
        $sql .= " ORDER BY " . self::TABLE_NAME . ".date DESC";

        $sqlParams['categoryId'] = $categoryId;

        if ($lazyLoad) {
            $this->lazyLoadFromSql($sql, $sqlParams);
        } else {
            $this->loadFromSql($sql, $sqlParams);
        }

        return $this;
    }

    public function loadForTag($tag)
    {

        $sql        = '';
        $sqlParams  = array();

        $sql  = "SELECT " . self::TABLE_NAME . ".*";
        $sql .= "   FROM " . self::TABLE_NAME;
        $sql .= "   LEFT JOIN " . TagList::SQL_RELATION_TABLE_NAME;
        $sql .= "       ON " . TagList::SQL_RELATION_TABLE_NAME . ".post_id=" . self::TABLE_NAME . ".id";
        $sql .= "   LEFT JOIN " . Tag::TABLE_NAME;
        $sql .= "       ON " . Tag::TABLE_NAME . ".id=" . TagList::SQL_RELATION_TABLE_NAME . ".tag_id";
        $sql .= "   WHERE";
        $sql .= "       " . Tag::TABLE_NAME . ".tag=:tag";
        $sql .= "   ORDER BY " . self::TABLE_NAME . ".date DESC";

        $sqlParams['tag'] = $tag;

        $this->loadFromSql($sql, $sqlParams);

        return $this;
    }

    public function loadFromSearch($criteria)
    {
        $sphinx = new \SphinxClient();
        $sphinx->SetServer(SPHINX_HOST, SPHINX_PORT);
        $sphinx->SetConnectTimeout(1);

        $results = $sphinx->Query($criteria, self::SPHINX_INDEX);

        if ($results['total_found']) {
            foreach (array_keys($results['matches']) as $resultId) {
                $this->addItemLink($resultId);
            }
        }
    }

    public function saveLatestToCache()
    {

    }

    public static function loadLatestFromCache()
    {
        $cache = Fwk::getService('cache.memcache');
        $instance = $cache->get(ENVIRONMENT . '-' . self::LATEST_CACHE_ID);

        if ($instance !== false) {
            return $instance;
        } else {
            $instance = new PostList();
            $instance->saveLatestToCache();

            return $instance;
        }
    }
    public function generateRSS()
    {
        $rss  = '<?xml version="1.0" encoding="utf-8"?>'."\n";
        $rss .= '<rss version="2.0">'."\n";
        $rss .= '<channel>'."\n";
        $rss .= '   <title><![CDATA[OUYActu, les derniers articles]]></title>'."\n";
        $rss .= '   <description><![CDATA[Les derniers articles publiés sur OUYActu.com]]></description>'."\n";
        $rss .= '   <lastBuildDate>' . date('r', $this->generationDate) . '</lastBuildDate>'."\n";
        $rss .= '   <link>http://www.ouyactu.com/</link>'."\n";

        foreach ($this->items as $currentItem) {
            $rss .= '<item>' . "\n";
            $rss .= '   <title><![CDATA[' . $currentItem->title . ']]></title>' . "\n";
            $rss .= '   <description><![CDATA[' . $currentItem->renderIntro() . "\n" . $currentItem->renderContent() . ']]></description>' . "\n";
            $rss .= '   <pubDate>' . date('r', strtotime($currentItem->date)) . '</pubDate>' . "\n";
            $rss .= '   <link>http://www.ouyactu.com' . $currentItem->getUrl() . '</link>' . "\n";
            $rss .= '</item>'."\n";
        }

        $rss .= '</channel>'."\n";
        $rss .= '</rss>';

        return $rss;
    }
}
