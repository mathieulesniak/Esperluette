<?php
namespace Esperluette\Controller\Admin;

use Esperluette\View;
use Esperluette\Model;
use Esperluette\Model\Config;
use Esperluette\Model\Helper;
use Esperluette\Model\Notification;
use Esperluette\Model\Blog\CategoryList;
use Fwk\Fwk;
use Fwk\Validator;


class Category extends \Esperluette\Controller\Base
{
    public function getCategories($page = null)
    {
        if ($page == null) {
            $page = 1;
        }

        $model = Model\Blog\CategoryList::loadAll()->generateTree();
        $subModel = $model->getSlice(($page - 1) * ADMIN_NB_CATEGORIES_PER_PAGE, ADMIN_NB_CATEGORIES_PER_PAGE);

        $view   = new View\Admin\Category\Homepage($subModel);
        $view
            ->setCurrentPage($page)
            ->setNbItems(count($model))
            ->setNbPerPage(ADMIN_NB_CATEGORIES_PER_PAGE)
            ->setUrl(url('/admin/categories'));


        $this->response->setBody($view->render());
    }

    public function editCategory($categoryId = null)
    {
        $model = new Model\Blog\Category();

        if ($categoryId != null) {
            $model->load($categoryId);
            if ($model->id === null) {
                Notification::write('error', Helper::i18n('error.categories.unknown_category'));
                $this->response->redirect(url('/admin/categories'));
            }
        }

        // Saving category
        if (isset($_POST['save_category'])) {
            $categoryData = array(
                'name'          => '',
                'slug'          => '',
                'description'   => '',
                'parent_id'     => '',
            );

            foreach ($categoryData as $item => $defaultValue) {
                $model->$item = Fwk::Request()->getPostParam($item, $defaultValue);
            }

            // Generate slug from name / check slug is set
            if ($model->slug == '') {
                $model->slug = Helper::sluginator($model->name);
            } else {
                $model->slug = Helper::sluginator($model->slug);
            }

            $validator = new Validator($model);

            $validator
                ->validate('name')
                ->longerThan(2, Helper::i18n('error.categories.name_empty'));

            if ($errors = $validator->getErrors()) {
                Notification::write('error', $errors);
            } else {
                $model->save();
                Notification::write('success', 'All good !');
                $this->response->redirect(url('/admin/categories'));
            }
        }
        
        $view = new View\Admin\Category\Edit($model);
        $this->response->setBody($view->render());
    }

    function deleteCategory($categoryId)
    {
        if ($categoryId != '') {
            $model = new Model\Blog\Category();
            $model->load($categoryId);
            if ($model->id !== null) {
                
                // Cannot delete default category
                if ($model->id == Config::get('posts_default_category')) {
                    Notification::write('error', Helper::i18n('error.categories.cannot_delete_default'));
                    $this->response->redirect(url('/admin/categories'));
                }

                // Reassign sub categories to current parent
                $categoryList = Model\Blog\CategoryList::loadAll()->generateTree();
                $childrenCategories = $categoryList->getChildren($categoryId);
                foreach ($childrenCategories as $currentChild) {
                    $currentChild->parent_id = $model->parent_id;
                    $currentChild->save();
                }

                // Reassign posts
                $postList = $model->posts;
                foreach ($postList as $currentPost) {
                    $currentPost->category = Config::get('posts_default_category');
                    $currentPost->save();
                }

                //$model->delete();
                Notification::write('success', 'All good !');
                $this->response->redirect(url('/admin/categories'));
            }
        }
    }
}