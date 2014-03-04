<?php
namespace App\Controllers\Admin;

use Esperluette\Model;
use Esperluette\Model\Helper;
use Esperluette\Model\Notification;
use Esperluette\View;
use Fwk\Fwk;
use Fwk\Validator;


class User extends \App\Controllers\Base
{
    public function getUsers($page = null)
    {
        if ($page == null) {
            $page = 1;
        }
        $model  = Model\User\UserList::loadAll();
        $view   = new View\Admin\User\Homepage($model);
        $view
            ->setCurrentPage($page)
            ->setNbItems(count($model))
            ->setNbPerPage(ADMIN_NB_USERS_PER_PAGE)
            ->setUrl(url('/admin/users'));
        $this->response->setBody($view->render());
    }

    public function editUser($userId = null)
    {
        $model = new Model\User\User();

        if ($userId != '') {
            $model->load($userId);

            if ($model->id === null) {
                Notification::write('error', Helper::i18n('error.users.unknown_user'));
                $this->response->redirect(url('/admin/users'));
            }
        }

        if (isset($_POST['save_user'])) {
            $userOptions = array(
                'nickname'          => '',
                'name_display'      => '',
                'first_name'        => '',
                'last_name'         => '',
                'email'             => '',
                'password'          => '',
                'active'            => '',
                'level'             => ''
            );



            foreach ($userOptions as $item => $defaultValue) {
                $userData[$item] = Fwk::Request()->getPostParam($item, $defaultValue);
            }

            $validator = new Validator($userData);

            // Check nickname only if in creation mode
            if ($model->id === null) {
                $validator
                    ->validate('nickname')
                    ->longerThan(4, Helper::i18n('error.user.nickname_too_short'));
            }

            if ($userData['password'] != '' || $model->id === null) {
                $validator
                    ->validate('password')
                    ->longerThan(8, Helper::i18n('error.user.password_too_short'));
            }

            // At least first name is required when set to real name display mode
            if ($userData['name_display'] == Model\User\User::NAME_DISPLAY_REAL) {
                $validator
                    ->validate('first_name')
                    ->notBlank(Helper::i18n('error.user.first_name_empty'));
            }

            $validator
                ->validate('email')
                ->email(Helper::i18n('error.user.email_invalid'));


            if ($errors = $validator->getErrors()) {
                Notification::write('error', $errors);
            } else {
                if ($userData['password'] == '') {
                    unset($userData['password']);
                } else {
                    /**
                     TODO : hash password
                     */
                }
                if ($model->id !== null) {
                    $userData['nickname'] = $model->nickname;
                    $userData['id'] = $model->id;
                }
                Model\User\User::buildFromArray($userData)->save();

                Notification::write('success', 'All good !');
                $this->response->redirect($_SERVER['REQUEST_URI']);
            }
        }

        $view = new View\Admin\User\Edit($model);
        $this->response->setBody($view->render());
    }

    public function deleteUser($userId)
    {

        if ($userId != '') {
            $model = new Model\User\User();
            $model->load($userId);
            if ($model->id !== null) {
                // Delete all posts / page written by user
                $postList = new Model\Blog\PostList;
                $postList->LoadForUserId($model->id);
                foreach ($postList as $currentPost) {
                    $currentPost->delete();
                }

                $pageList = new Model\Blog\PageList();
                $pageList->LoadForUserId($model->id);
                foreach ($pageList as $currentPage) {
                    $currentPage->delete();
                }

                // And then delete the user
                $model->delete();

                Notification::write('success', 'All good !');
                $this->response->redirect(url('/admin/users'));
            } else {
                Notification::write('error', Helper::i18n('error.users.unknown_user'));
                $this->response->redirect(url('/admin/users'));
            }
        }

    }
}
