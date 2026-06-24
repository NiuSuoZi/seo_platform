<?php
/**
 * Created by PhpStorm.
 * User: 00
 * Date: 2018/11/14
 * Time: 20:00
 */

namespace Common\Model;
use Common\Model\BaseModel;

class InformationModel extends BaseModel
{

    protected $tableName='spider_information';
    protected $pk = 'id';
    protected $autoinc = true;
    /**
    protected $_validate = array(

    );
     **/

    /**
     * @param string $domain
     * @param string $spider_type
     * @return mixed|void
     */

    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct($name, $tablePrefix, $connection);
    }

    public function lists(){
        $Page_count = $this->order('last_times DESC')->count();
        $Page = new \Think\Page($Page_count,100);
        $data['data'] = $this->order('last_times DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();
        return $data;
    }


    public function add($data){
        $spider_list = array('BaiduSpider'=> 1,'SogouSpider'=> 2,'360Spider' => 3,'BingBot' => 4,'GoogleBot'=>5,'YisouSpider'=>6);
        if(is_array($data)){
            //extract($data);
            //echo $spider_domain;
            if($this->chenk_domain($data['spider_domain'],$data['types'])){
                $map = array(
                    'domain' => $data['spider_domain']
                );
                    switch ($data['spider_types']){
                        case 1: // baidu
                            $this->where($map)->setInc('baidu_count',1);
                            $this->where($map)->save(['last_times'=>$data['spider_times']]);
                            break;
                        case 2: // sogou
                            $this->where($map)->setInc('sogou_count',1);
                            $this->where($map)->save(['last_times'=>$data['spider_times']]);
                            break;
                        case 3: // 360
                            $this->where($map)->setInc('360_count',1);
                            $this->where($map)->save(['last_times'=>$data['spider_times']]);
                            break;
                        case 4: // bing
                            $this->where($map)->setInc('bing_count',1);
                            $this->where($map)->save(['last_times'=>$data['spider_times']]);
                            break;
                        case 5: // google
                            $this->where($map)->setInc('google_count',1);
                            $this->where($map)->save(['last_times'=>$data['spider_times']]);
                            break;
                        case 6: //
                            $this->where($map)->setInc('yisou_count',1);
                            $this->where($map)->save(['last_times'=>$data['spider_times']]);
                            break;

                    }
            }
        }
        //$spider_list = array('BaiduSpider'=> 1,'SogouSpider'=> 2,'360Spider' => 3,'BingBot' => 4,'GoogleBot'=>5,'YisouSpider'=>6);

        //$this->chenk_domain($data['domain']);


    }

    protected function chenk_domain($domain ='',$spider_type = '')
    {
        if (!empty($domain)) {
            $map = array(
                'domain' => $domain
            );
            $data = $this->where($map)->field('id,domain')->order('id DESC')->limit(1)->select();
            if (empty($data[0])) {
                if (!$this->create_domain($domain, $spider_type)) {
                    return false;
                }
            }
            return true;
        }
    }

    protected function create_domain($domain = '',$types = ''){
        $data = [
            'domain' => (string)$domain,
            'sogou_count' => (int)0,
            '360_count' => (int)0,
            'baidu_count' => (int)0,
            'types' => (string)$types,
            'create_times' => (int)time(),
            'last_times' => (int)0,
            'rank' => (string)''
        ];
        $query = "INSERT INTO `seo_platform`.`seo_spider_information`(`domain`, `sogou_count`, `360_count`, `baidu_count`,`yisou_count`, `bing_count`, `google_count`, `types`, `last_times`, `rank`, `create_times`) VALUES ('{$data['domain']}', 0, 0, 0 ,0 ,0 ,0,'{$data['types']}', 0, '', {$data['create_times']});";
        $return = $this->execute($query);
        if($return){
            return $this->getLastInsID();
        }
        //return $this->add($data);



    }


}