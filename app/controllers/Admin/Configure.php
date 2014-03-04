<?php
namespace App\Controllers\Admin;

use \Esperluette\Model;
use \Esperluette\Model\Helper;
use \Esperluette\View;
use \Esperluette\Model\Notification;
use \Fwk\Validator;
use \Fwk\Fwk;

class Configure extends \App\Controllers\Base
{
    public function getHomepage()
    {
        if (isset($_POST['save_configuration'])) {
            $configOptions = array(
                'site_name'                     => '',
                'site_description'              => '',
                'language'                      => '',
                'timezone'                      => '',
                'date_format'                   => '',
                'date_format_custom'            => '',
                'time_format'                   => '',
                'time_format_custom'            => '',
                'admin_email'                   => '',
                'posts_default_category'        => '',
                'posts_per_page'                => 0,
                'comments_enabled'              => 0,
                'comments_name_email_required'  => 0,
                'comments_autoclose_after'      => 0,
                'comments_order'                => 'ASC',
                'comments_autoallow'            => 0,
                'comments_notify'               => 0,
                'comments_hold_links_nb'        => 0,
                'comments_wordlist_hold'        => '',
                'comments_wordlist_spam'        => '',
                'theme'                         => '',
            );

            foreach ($configOptions as $item => $defaultValue) {
                $config[$item] = Fwk::Request()->getPostParam($item, $defaultValue);
            }

            $validator = new Validator($config);

            $validator
                ->validate('site_name')
                ->longerThan(2, Helper::i18n('error.config.site_name_empty'));

            $validator
                ->validate('site_description')
                ->longerThan(2, Helper::i18n('error.config.site_description_empty'));

            $validator
                ->validate('admin_email')
                ->email(Helper::i18n('error.config.admin_email_invalid'));
            $validator
                ->validate('posts_default_category')
                ->notBlank(Helper::i18n('error.config.posts_default_category_empty'));

            $validator
                ->validate('posts_per_page')
                ->digit(Helper::i18n('error.config.post_per_page_number'));

            $validator
                ->validate('comments_autoclose_after')
                ->digit(Helper::i18n('error.config.comments_autoclose_after_number'))
                ->notBlank(Helper::i18n('error.config.comments_autoclose_after_empty'));

            if ($config['date_format'] == 'custom') {
                $validator
                    ->validate('date_format_custom')
                    ->notBlank(Helper::i18n('error.config.date_format_empty'));
            }

            if ($config['time_format'] == 'custom') {
                $validator
                    ->validate('time_format_custom')
                    ->notBlank(Helper::i18n('error.config.time_format_empty'));
            }

            if ($errors = $validator->getErrors()) {
                Notification::write('error', $errors);
            } else {
                Model\Meta\MetaList::buildFromArray($config)->save();
                Notification::write('success', 'All good !');
                $this->response->redirect($_SERVER['REQUEST_URI']);
            }
        }

        $view = new View\Admin\Configure\Homepage($model);

        $this->response->setBody($view->render());
    }
}
