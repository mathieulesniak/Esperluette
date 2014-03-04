<?php
namespace App\Models\User;

use App\Models;
use Fwk\Fwk;

class UserList extends \Fwk\Collection
{
    const TABLE_NAME        = 'blog_users';
    const ITEM_TYPE         = '\App\Models\User\User';
    const PARENT_ID_NAME    = '';

}
