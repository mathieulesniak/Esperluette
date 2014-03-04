<?php
namespace App\Controllers;

use \Esperluette\Model;
use \Esperluette\View;

class Base
{
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    /*public function checkAndRedirect($rightUrl)
    {

        $currentUrl = \Fwk\Fwk::getService('request')->getRequestUri();

        if ($currentUrl !== $rightUrl) {
            $this->response->redirect($rightUrl, 301);
            $this->response->write();
            die();
        }
    }*/

    public function send404()
    {
        $this->response->setHttpCode(404);

        $output = View\CommonUtils::render404();

        $this->response->setBody($output);
        /**
        HANDLE 404 TEMPLATE
         */
    }
}
