<?php
namespace App\Views\Admin\Configure;

use App\Models;
use App\Models\Theme;
use App\Models\Blog\CategoryList;
use App\Views;
use App\Model\Helper;
use Fwk\FormItem;
use Fwk\Fwk;
use App\Models\Config;


class Homepage extends \App\Views\Admin
{
    protected $section = 'setup';

    public function render($content = '')
    {
        $formValues = array(
            'site_name'                     => Fwk::Request()->getPostParam('site_name', Config::get('site_name')),
            'site_description'              => Fwk::Request()->getPostParam('site_description', Config::get('site_description')),
            'admin_email'                   => Fwk::Request()->getPostParam('admin_email', Config::get('admin_email')),
            'language'                      => Fwk::Request()->getPostParam('language', Config::get('language')),
            'timezone'                      => Fwk::Request()->getPostParam('timezone', Config::get('timezone')),
            'date_format'                   => Fwk::Request()->getPostParam('date_format', Config::get('date_format')),
            'date_format_custom'            => Fwk::Request()->getPostParam('date_format_custom', Config::get('date_format_custom', 'F j, Y')),
            'time_format'                   => Fwk::Request()->getPostParam('time_format', Config::get('time_format')),
            'time_format_custom'            => Fwk::Request()->getPostParam('time_format_custom', Config::get('time_format_custom', 'H:i')),
            'posts_default_category'        => Fwk::Request()->getPostParam('posts_default_category', Config::get('posts_default_category')),
            'posts_per_page'                => Fwk::Request()->getPostParam('posts_per_page', Config::get('posts_per_page')),
            'comments_enabled'              => Fwk::Request()->getPostParam('comments_enabled', Config::get('comments_enabled')),
            'comments_name_email_required'  => Fwk::Request()->getPostParam('comments_name_email_required', Config::get('comments_name_email_required')),
            'comments_autoclose_after'      => Fwk::Request()->getPostParam('comments_autoclose_after', Config::get('comments_autoclose_after')),
            'comments_order'                => Fwk::Request()->getPostParam('comments_order', Config::get('comments_order')),
            'comments_autoallow'            => Fwk::Request()->getPostParam('comments_autoallow', Config::get('comments_autoallow')),
            'comments_notify'               => Fwk::Request()->getPostParam('comments_notify', Config::get('comments_notify')),
            'comments_hold_links_nb'        => Fwk::Request()->getPostParam('comments_hold_links_nb', Config::get('comments_hold_links_nb')),
            'comments_wordlist_spam'        => Fwk::Request()->getPostParam('comments_wordlist_spam', Config::get('comments_wordlist_spam')),
            'comments_wordlist_hold'        => Fwk::Request()->getPostParam('comments_wordlist_hold', Config::get('comments_wordlist_hold')),
            'theme'                         => Fwk::Request()->getPostParam('theme', Config::get('theme')),
        );

        $output  = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">'."\n";

        //
        // Site config
        //

        
        $output .= '<div class="top-bar"><h3>' . Helper::i18n('admin.setup.site') . '</h3></div>';
        $output .= '<div class="well">';
        // Site name
        $output .= '<p class="controls">';
        $output .=      FormItem::text('site_name', $formValues['site_name'], Helper::i18n('admin.setup.site_name'));
        $output .= '</p>';

        // Site description
        $output .= '<p class="controls">';
        $output .= FormItem::textarea('site_description', $formValues['site_description'], Helper::i18n('admin.setup.site_description'));
        $output .= '</p>';
        
        /**
        TODO : Front page chooser
         */ 
        // Admin email
        $output .= '    <p class="controls">' . FormItem::text('admin_email', $formValues['admin_email'], Helper::i18n('admin.setup.admin_email')) . '</p>';
        
        // Language
        $output .= '    <p class="controls">' . FormItem::select(
            'language',
            Helper::i18nList(),
            $formValues['language'],
            Helper::i18n('admin.setup.language')
        );
        $output .= '    </p>';

        // Timezone
        $output .= '    <p class="controls">' . FormItem::select(
            'timezone',
            Helper::listTimezones(),
            $formValues['timezone'],
            Helper::i18n('admin.setup.timezone')
            );
        $output .= '    </p>';

        // Date format
        $customLabel =  Helper::i18n('admin.setup.date_format_custom') . FormItem::text('date_format_custom', $formValues['date_format_custom']);
        $output .= '    <div class="controls">';
        $output .= '        ' . FormItem::radio(
            'date_format', 
            array(
                'Y/m/d' => date('Y/m/d'),
                'm/d/Y' => date('m/d/Y'),
                'd/m/Y' => date('d/m/Y'),
                'custom' => $customLabel
            ),
            $formValues['date_format'],
            Helper::i18n('admin.setup.date_format')
        );
        
        $output .= '    </div>';
        
        // Time format
        $customLabel =  Helper::i18n('admin.setup.time_format_custom') . FormItem::text('time_format_custom', $formValues['time_format_custom']);
        $output .= '    <div class="controls">';
        $output .= '        ' . FormItem::radio(
            'time_format', 
            array(
                'g:i a' => date('g:i a'),
                'g:i A' => date('g:i A'),
                'H:i' => date('H:i'),
                'custom' => $customLabel
            ),
            $formValues['time_format'],
            Helper::i18n('admin.setup.time_format')
        );
        $output .= '    </div>';
        $output .= '</div>';

        $output .= '<div class="top-bar"><h3>' . Helper::i18n('admin.setup.posts') . '</h3></div>';
        $output .= '<div class="well">'."\n";
        // Posts per page
        $output .= '    <div class="controls">' . FormItem::number('posts_per_page', $formValues['posts_per_page'], Helper::i18n('admin.setup.posts_per_page'), array('step' => 1, 'min' => 1)) . '</div>';

        // Default category
        $categoriesList = CategoryList::loadAll();
        $output .= '    <div class="controls">' . FormItem::select(
            'posts_default_category',
            $categoriesList->generateTree()->getAsArray(),
            $formValues['posts_default_category'],
            Helper::i18n('admin.setup.posts_default_category')
            ) . '</div>';
            
        $output .= '</div>';
        
        //
        // Comments
        //

        
        $output .= '<div class="top-bar"><h3>' . Helper::i18n('admin.setup.comments') . '</h3></div>';
        $output .= '<div class="well">'."\n";
        $output .= '    <p class="controls">' . FormItem::checkbox('comments_enabled', 1, $formValues['comments_enabled'] == 1, Helper::i18n('admin.setup.comments_enabled')) . '</p>';
        $output .= '    <p class="controls">' . FormItem::checkbox('comments_name_email_required', 1, $formValues['comments_name_email_required'], Helper::i18n('admin.setup.comments_name_email_required')) . '</p>';
        $output .= '    <p class="controls">' . FormItem::number('comments_autoclose_after', $formValues['comments_autoclose_after'], Helper::i18n('admin.setup.comments_autoclose_after'), array('step' => 1, 'min' => 0)) . '</p>';
        $output .= '    <p class="controls">';
        $output .= FormItem::select(
            'comments_order',
            array('ASC' => Helper::i18n('admin.setup.comments_order_asc'), 'DESC' => Helper::i18n('admin.setup.comments_order_desc')),
            $formValues['comments_order'],
            Helper::i18n('admin.setup.comments_order')
        );
        $output .= '</p>';
        
        $output .= '    <p class="controls">' . FormItem::checkbox('comments_autoallow', 1, $formValues['comments_autoallow'], Helper::i18n('admin.setup.comments_autoallow')) . '</p>';
        $output .= '    <p class="controls">' . FormItem::checkbox('comments_notify', 1, $formValues['comments_notify'], Helper::i18n('admin.setup.comments_notify')) . '</p>';
        $output .= '    <p class="controls">' . FormItem::number('comments_hold_links_nb', $formValues['comments_hold_links_nb'], Helper::i18n('admin.setup.comments_hold_links_nb'), array('step' => 1, 'min' => 0)) . '</p>';
        $output .= '    <p class="controls">' . FormItem::textarea('comments_wordlist_hold', $formValues['comments_wordlist_hold'], Helper::i18n('admin.setup.comments_wordlist_hold')) . '</p>';
        $output .= '    <p class="controls">' . FormItem::textarea('comments_wordlist_spam', $formValues['comments_wordlist_spam'], Helper::i18n('admin.setup.comments_wordlist_spam')) . '</p>';
        $output .= '</div>';
        
        //
        // Themes
        //
        
        $themeList = new Theme\ThemeList();
        $output .= '<div class="top-bar"><h3>' . Helper::i18n('admin.setup.themes') . '</h3></div>'."\n";
        $output .= '<div class="well">'."\n";
        $output .= '    <p class="controls">';
        $output .= FormItem::select(
            'theme',
            $themeList->getAsArray(),
            $formValues['theme'],
            Helper::i18n('admin.setup.theme')
        );
        $output .= '</p>';
        $output .= '</div>' . "\n";

        $output .= FormItem::submit('save_configuration', Helper::i18n('admin.setup.save'));
        
        return parent::render($output);
    }
}
