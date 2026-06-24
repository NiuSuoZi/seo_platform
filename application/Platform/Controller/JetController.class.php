<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/15
 * Time: 19:27
 */
namespace Platform\Controller;

use Common\Controller\PlatformBaseController;

class JetController extends PlatformBaseController
{

    // 首页
    public function index()
    {
        $this->display();
    }

    // 首页劫持
    public function jet_index()
    {
        $this->display();
    }

    // 代码劫持
    public function jet_code()
    {
        $this->display();
    }

    // 站群劫持
    public function jet_group()
    {
        $this->display();
    }

    // 404劫持
    public function jet_not_found()
    {
        $this->display();
    }
}