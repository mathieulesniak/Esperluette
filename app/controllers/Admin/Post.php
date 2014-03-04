<?php
namespace App\Controllers\Admin;

use App\Models;
use App\Views;
use App\Models\Notification;
use Suricate\Validator;

class Post extends \App\Controllers\Base
{
    public function getPosts($categoryName = '', $page = null)
    {
        if ($page == null) {
            $page = 1;
        }

        if ($categoryName != '') {
            $model  = new Models\Blog\PostList();
            $model->loadForCategorySlug(urldecode($categoryName));
        } else {
            $model = Models\Blog\PostList::loadAll();
        }
        $subModel = $model->getSlice(($page - 1) * ADMIN_NB_POSTS_PER_PAGE, ADMIN_NB_POSTS_PER_PAGE);

        $view   = new Views\Admin\Post\Homepage($subModel);
        $view
            ->setCurrentPage($page)
            ->setNbItems(count($model))
            ->setNbPerPage(ADMIN_NB_POSTS_PER_PAGE)
            ->setUrl(url('/admin/posts'));

        $this->response->setBody($view->render());
    }

    public function previewPost($postId)
    {
        if ($postId != '') {
            $model = new Models\Blog\Post();
            $model->load($postId);

            if ($model->id !== null) {
                $view = new Views\Admin\Post\Preview($model);
            }
        }

        $this->response->setBody($view->render());
    }

    public function editPost($postId = null)
    {
        if ($postId != '') {
            $model = new Models\Blog\Post();
            $model->load($postId);
            if ($model->id !== null) {

            } else {
                //Unknown post
            }
            $view = new View\Admin\Post\Edit($model);
            $this->response->setBody($view->render());
        }
    }

    public function deletePost($postId)
    {
        if ($postId != '') {
            $model = new Model\Blog\Post();
            $model->load($postId);
            if ($model->id !== null) {
                $model->delete();
            }
        }

    }
}
