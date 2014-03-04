<?php
namespace App\Controllers\Admin;

use Esperluette\Model;
use Esperluette\Model\Helper;
use \Esperluette\View;

class Comment extends \App\Controllers\Base
{
    public function getComments($statusName = '', $page = null)
    {
        if ($page == null) {
            $page = 1;
        }

        if ($statusName == '') {
            $model  = Model\Comment\CommentList::loadAll();
        } else {
            $model = new Model\Comment\CommentList();
            $model->loadForStatus($statusName);
        }
        $subModel = $model->getSlice(($page - 1) * ADMIN_NB_COMMENTS_PER_PAGE, ADMIN_NB_COMMENTS_PER_PAGE);

        $view   = new View\Admin\Comment\Homepage($subModel);
        $view
            ->setCurrentPage($page)
            ->setNbItems(count($model))
            ->setNbPerPage(ADMIN_NB_COMMENTS_PER_PAGE)
            ->setUrl(url('/admin/comments'));

        $this->response->setBody($view->render());
    }

    public function editComment($commentId)
    {
        if ($commentId != '') {
            $model = new Model\Comment\Comment();
            $model->load($commentId);
            if ($model->id !== null) {

            } else {
                // Unknown comment
            }
            $view = new View\Admin\Comment\Edit($model);
            $this->response->setBody($view->render());
        }
    }

    public function deleteComment($commentId)
    {
        if ($commentId != '') {
            $comment = new Model\Comment\Comment();
            $comment->load($commentId);
            if ($comment->id !== null) {
                $comment->delete();
            }
        }
    }
}
