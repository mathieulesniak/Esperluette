<?php
namespace App\Controllers;

use \App\Models;
use \App\Views;
use \Fwk\Registry;

class Blog extends Base
{
    public function getHomepage()
    {
        echo "HP";
    }

    public function getPost($postName)
    {
        // Load model
        $model = new Models\Blog\Post();
        $model->loadFromSlug($postName);

        if ($model->id === null) {
            // trigger 404
            echo '404 !!';
        }
        Registry::set('post', $model);
        // Set Model data into registry

        $view = new Views\Template('post');
        $this->response->setBody($view->render());
    }

    public function getPostsByTag($tagName)
    {
        $collection = new Models\Blog\PostList();
        $model = $collection->loadAll();

        Registry::set('posts', $model);
        $view = new Views\Template('posts');
        if ($view->exists('posts-' . $tagName)) {
            $view->setTemplate('posts-' . $tagName);
        }
        $this->response->setBody($view->render());
    }

    public function getPostsByCategory($categoryName, $page = '')
    {

        $category = new Models\Blog\Category();
        $category->loadFromSlug($categoryName);
        if ($category->id === null) {
            // Trigger 404
            echo '404 category';
        } else {
            $collection = new Models\Blog\PostList();
            $model = $collection->loadForCategoryId($category->id);
            echo count($model) . 'posts to display';
        }


        $view = new View\Template('posts');
        if ($view->exists('posts-' . $category->slug)) {
            $view->setTemplate('posts-' . $category);
        }
        $this->response->setBody($view->render());
    }

    public function getPostsByDate()
    {
        $view = new View\Template('posts');
        $this->response->setBody($view->render());
    }
}
