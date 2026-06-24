<?php
namespace Common\Controller;
use Common\Controller\BaseController;
/**
 * admin 基类控制器
 */
class PlatformBaseController extends BaseController{
	/**
	 * 初始化方法
	 */
	public function _initialize(){
		parent::_initialize();
		if(!check_login()){
            $this->redirect('login/index/index','',0);
            exit;
        }

		if(IS_POST){
		    if(strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']) === false){
		        exit('anit csrf.');
            }
        }

		$auth=new \Think\Auth();
		$rule_name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		$result=$auth->check($rule_name,$_SESSION['user']['id']);
		if(!$result){
			$this->error('您没有权限访问');
		}
		// 分配菜单数据
		$nav_data=D('PlatformNav')->getTreeData('level','order_number,id');
		$assign=array(
			'nav_data'=>$nav_data
			);
		$this->assign($assign);
	}

	public function passwordHash($password)
    {
        if ($password !== '') {
            $data['salt'] = $salt = get_salt();
            $data['password'] = md5(md5($password) . $salt);
            return $data;
        }
    }



}

