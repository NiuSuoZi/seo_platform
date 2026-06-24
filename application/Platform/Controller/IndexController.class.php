<?php
namespace Platform\Controller;
use Common\Controller\PlatformBaseController;
/**
 * 后台首页控制器
 */
class IndexController extends PlatformBaseController{
	/**
	 * 首页
	 */
	public function index(){

	    if(I('get.act') == 'total' && IS_AJAX) {
            $SpiderLogModel = new \Common\Model\SpiderLogModel();
            $spiderCount = $SpiderLogModel->getAllCount();
            // $hourCount = $SpiderLogModel->getCountByHours();
            $getCountByHoursAndSpiderTypes = $SpiderLogModel->getFormattedSpiderStats();
            $hour = [];
            $total = [];
            $hour_v = [];
            $Count = 0;

            foreach ($spiderCount as $value){
                $Count = $Count + $value['count'];
            }
//            foreach ($hourCount as $value) {
//                $hour[] = $value['hour'] . ':00';
//                $total[] = $value['total'];
//            }

            $this->ajaxReturn(['all'=>$Count,'total' => $spiderCount,'line'=>$getCountByHoursAndSpiderTypes, 'label' => [
                'hour' => $hour,
                'total' => $total,
            ]]);
        }
        $license = C('license');

        $this->assign('getStorage', getStorage());
        $this->assign('center', is_connect_center());
        $this->assign('license', $license);
		$this->display();
	}
	/**
	 * elements
	 */
	public function elements(){

		$this->display();
	}
	
	/**
	 * welcome
	 */
	public function welcome(){
	    $this->display();
	}

    public function clear(){
        Api('system/clear_cache',[]); // 清除内容端系统缓存
        S('keyword_list',null);
        S('template_cache',null);

        if(!APP_DEBUG){
            unlink(RUNTIME_PATH . 'common~runtime.php');
        }
        $this->ajaxReturn(['code'=>200,'message'=>'缓存清除完成']);
    }

	public function logout(){
        session('user',null);
        session(null);
        session_destroy();
        $this->redirect('login/index/index','',0);
    }



}
