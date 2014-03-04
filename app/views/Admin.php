<?php
namespace Esperluette\View;

use Esperluette\Model\Helper;
use Esperluette\Model\Notification;
use Fwk\Fwk;

class Admin extends \Fwk\Page
{
    protected $model;
    protected $removeTemplate = false;

    private $sections = array(
        'posts'         => array('name' => 'admin.posts', 'accessLevel' => 'editor', 'url' => '/admin/posts', 'icon' => ''),
        'pages'         => array('name' => 'admin.pages', 'accessLevel' => 'editor', 'url' => '/admin/pages', 'icon' => ''),
        'comments'      => array('name' => 'admin.comments', 'accessLevel' => 'user', 'url' => '/admin/comments', 'icon' => 'icon-comments'),
        'categories'    => array('name' => 'admin.categories', 'accessLevel' => 'editor', 'url' => '/admin/categories', 'icon' => 'icon-open-folder'),
        'users'         => array('name' => 'admin.users', 'accessLevel' => 'admin', 'url' => '/admin/users', 'icon' => 'icon-user'),
        'setup'         => array('name' => 'admin.setup', 'accessLevel' => 'admin', 'url' => '/admin/configure', 'icon' => 'icon-cog')
    );

    private $subSections = array(
        'posts' => array(
            array('icon' => '', 'name' => 'admin.posts.add', 'url' => '/admin/posts/add')
            ),
        'pages' => array(
            array('icon' => '', 'name' => 'admin.pages.add', 'url' => '/admin/pages/add')
            ),
        );
    protected $section;

    public function __construct(&$model = null)
    {
        parent::__construct();
        $this->model = $model;
        /**
        TODO : handle stylesheet + scripts
         */
        $this->addStylesheet(
            'font-awesome',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/fonts/font-awesome/css/font-awesome.min.css'
        );
        $this->addStylesheet(
            'normalize',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/css/normalize.css'
        );
        $this->addStylesheet(
            'component',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/css/component.css'
        );

        $this->addStylesheet(
            'admin',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/css/admin.css'
        );


        $this->addScript(
            'zepto',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/scripts/zepto.min.js'
        );
        $this->addScript(
            'mondernizr',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/scripts/modernizr.custom.min.js'
        );
        $this->addScript(
            'gnmenu',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/scripts/gnmenu.js'
        );
        $this->addScript(
            'admin',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/scripts/admin.js');
        $this->addMeta('viewport', 'initial-scale=1, minimum-scale=1, user-scalable=no, maximum-scale=1, width=device-width');
        $this->addMeta('apple-mobile-web-app-capable', 'yes');
    }

    private function renderNavigation()
    {
        /**
            TODO : manage users level 
         */
        

        $output = '<ul id="gn-menu" class="gn-menu-main">'."\n";
        $output .= '    <li class="gn-trigger">'."\n";
        $output .= '        <a class="gn-icon gn-icon-menu"><span>Menu</span></a>'."\n";
        $output .= '        <nav class="gn-menu-wrapper">'."\n";
        $output .= '            <div class="gn-scroller">'."\n";
        $output .= '                <ul class="gn-menu">'."\n";
        foreach ($this->sections as $sectionId => $sectionData) {
            $class = ($this->section == $sectionId) ? ' class="selected"' : '';
            $output .= '<li' . $class . '>' . "\n";
            $output .= '   <a href="' . url($sectionData['url']) . '"><i class="' . $sectionData['icon'] . '"></i> ' . Helper::i18n($sectionData['name'])  . '</a>' . "\n";
            if (isset($this->subSections[$sectionId])) {
                $output .= '<ul class="gn-submenu">'."\n";
                foreach ($this->subSections[$sectionId] as $currentSubSection) {
                    $output .= '<li><a href="' . url($currentSubSection['url']) . '">' . Helper::i18n($currentSubSection['name']) . '</a></li>'."\n";
                }
                $output .= '</ul>'."\n";
            }
            $output .= '</li>' . "\n";
        }
        $output .= '                </ul>'."\n";
        $output .= '            </div>'."\n";
        $output .= '        </nav>'."\n";
        $output .= '    </li>'."\n";
        $output .= '    <li><a href="" class="top-nav"><span>Esperluette</span></a></li>';
        $output .= '    <li><a href="" class="top-nav"><i class="icon-power-off"></i> <span>' . Helper::i18n('admin.logout') . '</span></a></li>';
        $output .= '</ul>'."\n";

        return $output;
    }

    public function render($content = '')
    {
        $output = '';

        if (!$this->removeTemplate) {
            $output  = '<header>'."\n";
            $output .=      $this->renderNavigation();
            $output .= '</header>' ."\n";

            $notifications = Notification::read();
            if ($notifications !== '') {
                // Output notifications
                $output .= $notifications;
            }
        }


        $output .= $content;

        $output .= '<script>'."\n";
        $output .= '    new gnMenu( document.getElementById(\'gn-menu\'));'."\n";
        $output .= '    setTimeout(function(){'."\n";
        $output .= '        scrollTo(0,1);'."\n";
        $output .= '    }, 100);'."\n";
        $output .= '</script>'."\n";
        return parent::render($output);
    }
}