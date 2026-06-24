<?php
namespace Platform\Controller;
use Common\Controller\PlatformBaseController;
/**
 * 后台菜单管理
 */
class NavController extends PlatformBaseController{
	/**
	 * 菜单列表
	 */
	public function index(){
		$data=D('PlatformNav')->getTreeData('tree','order_number,id');
		$assign=array(
			'data'=>$data
			);
		$this->assign($assign);
		$this->display();
	}

	/**
	 * 添加菜单
	 */
	public function add(){
		$data=I('post.');
		unset($data['id']);
		$result=D('PlatformNav')->addData($data);
		if ($result) {
			$this->success('添加成功',U('Platform/Nav/index'));
		}else{
			$this->error('添加失败');
		}
	}

	/**
	 * 修改菜单
	 */
	public function edit(){
		$data=I('post.');
		$map=array(
			'id'=>$data['id']
			);
		$result=D('PlatformNav')->editData($map,$data);
		if ($result) {
			$this->success('修改成功',U('Platform/Nav/index'));
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除菜单
	 */
	public function delete(){
		$id=I('get.id');
		$map=array(
			'id'=>$id
			);
		$result=D('PlatformNav')->deleteData($map);
		if($result){
			$this->success('删除成功',U('Platform/Nav/index'));
		}else{
			$this->error('请先删除子菜单');
		}
	}

	/**
	 * 菜单排序
	 */
	public function order(){
		$data=I('post.');
		$result=D('PlatformNav')->orderData($data);
		if ($result) {
			$this->success('排序成功',U('Platform/Nav/index'));
		}else{
			$this->error('排序失败');
		}
	}


}
