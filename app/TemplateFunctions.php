<?php
use Esperluette\Model;
use Esperluette\Model\Config;
use Fwk\Registry;


function siteName()
{
    return Config::get('site_name');
}

function siteDescription()
{
    return Config::get('site_description');
}

function themeInclude($part)
{
    include THEME_DIR . DIRECTORY_SEPARATOR . Config::get('theme', 'default') . DIRECTORY_SEPARATOR . $part . '.php';
}

function getNextPost()
{
}

function getPermalink()
{

}

//
// Pages
// 

function pages()
{

}

function pageId()
{

}

function pageTitle()
{

}

function pageContent()
{
    return Registry::get('page')->renderContent();
}

//
// Posts
// 

function posts()
{
    $postList = Registry::get('posts');
    if ($postList !== null) {
        if ($result = $postList->valid()) {
            Registry::set('post', $postList->current());
            $postList->next();
        } else {
            $postList->rewind();
        }
        return $result;
     } else {
        return false;
     }
}

function postId()
{
    return Registry::getProperty('post', 'id');
}

function postTitle()
{
    return Registry::getProperty('post', 'title');
}

function postContent()
{
    return Registry::get('post')->renderContent();
}

function postAuthorId()
{
    return Registry::getProperty('post', 'owner')->id;
}

function postAuthorName()
{

}

function postSlug()
{
    return Registry::getProperty('post', 'slug');
}

function postDate()
{

}

function postTime()
{

}

function postCategoryName()
{
    return Registry::getProperty('post', 'category')->name;
}

function postCategorySlug()
{
    return Registry::getProperty('post', 'category')->slug;
}
//
// Comments
//

function commentsOpen()
{
    return Registry::getProperty('post', 'comments');
}

function comments()
{

}

function hasComments()
{

}

function commentAuthorName()
{
    return Registry::getProperty('comment', 'author');
}

function commentAuthorEmail()
{
    return Registry::getProperty('comment', 'email');
}

function commentAuthorSite()
{
    return Registry::getProperty('comment', 'site');
}

function commentText()
{
    return Registry::getProperty('comment', 'comment');
}

//
// Helpers
//
function isHomepage() {

}

function isPage()
{

}

function isPost()
{

}

function isPosts()
{
}