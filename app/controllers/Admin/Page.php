<?php
namespace App\Controllers\Admin;

use Esperluette\Model;
use Esperluette\View;
use Esperluette\Model\Helper;
use Esperluette\Model\Notification;
use Fwk\Fwk;
use Fwk\Validator;

class Page extends \App\Controllers\Base
{
    public function getPages($statusName = '',$page = null)
    {
        if ($page == null) {
            $page = 1;
        }

        if ($statusName == '') {
            $model  = Model\Blog\PageList::loadAll();
        } else {
            $model = new Model\Comment\PageList();
            $model->loadForStatus($statusName);
        }
        $subModel = $model->getSlice(($page - 1) * ADMIN_NB_PAGES_PER_PAGE, ADMIN_NB_PAGES_PER_PAGE);

        $view   = new View\Admin\Page\Homepage($subModel);
        $view
            ->setCurrentPage($page)
            ->setNbItem(count($model))
            ->setNbPerPage(ADMIN_NB_PAGES_PER_PAGE)
            ->setUrl(url('/admin/page'));

        $this->response->setBody($view->render());
    }

    public function editPage($pageId = null)
    {
        if ($pageId != '') {
            $model = new Model\Blog\Page();
            $model->load($postId);
            if ($model->id !== null) {

            } else {
                //Unknown post
            }
            $view = new View\Admin\Page\Edit($model);
            $this->response->setBody($view->render());
        }
    }

    public function deletePage($pageId)
    {
        if ($pageId != '') {
            $model = new Model\Blog\Page();
            $model->load($pageId);
            if ($model->id !== null) {
                $model->delete();
            }
        }

    }
}
