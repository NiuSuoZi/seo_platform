<?php

namespace Platform\Controller;

use Common\Controller\PlatformBaseController;

/**
 * 侧栏一级菜单入口（仅做跳转，实际功能在各业务控制器）
 */
class ShowNavController extends PlatformBaseController
{
    public function config()
    {
        $this->redirect('Platform/Nav/index');
    }

    public function rule()
    {
        $this->redirect('Platform/Rule/index');
    }

    public function spider()
    {
        $this->redirect('Platform/Spider/show_today');
    }

    public function jet()
    {
        $this->redirect('Platform/Jet/index');
    }

    public function Server()
    {
        $this->redirect('Platform/Server/index');
    }

    public function Cilent()
    {
        $this->redirect('Platform/Cilent/index');
    }

    public function Tools()
    {
        $this->redirect('Platform/Tools/index');
    }

    public function project()
    {
        $this->redirect('Platform/Project/table');
    }

    public function Project()
    {
        $this->redirect('Platform/Project/table');
    }

    public function ProjectApi()
    {
        $this->redirect('Platform/ProjectApi/mix');
    }

    public function website()
    {
        $this->redirect('Platform/Website/management');
    }

    public function Zqmanager()
    {
        $this->redirect('Platform/Zqmanager/panel');
    }
}
