<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 0:10
 */
namespace Platform\Controller;

use Common\Controller\PlatformBaseController;

class CilentController extends PlatformBaseController
{

    public function index()
    {
        $KeywordsModel = new \Common\Model\KeywordsModel();
        $data = $KeywordsModel->getAllKeywords_group();
        $this->assign("data", $data);
        $this->display();
    }
}