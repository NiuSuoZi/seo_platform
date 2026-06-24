<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 0:45
 */

namespace Common\Model;
use Common\Model\BaseModel;

class KeywordsModel extends BaseModel
{
        // 定义表名
    protected  $tableName = 'keywords_access';
    /**
     *
     * @author y0umer <jackie.zmy@gmail.com>
     * @return array
     *
     */
    public function getAllKeywords_group(){
        // 查询是否有缓存数据
        $cache_name = base64_encode(md5(__METHOD__));
        if(empty(S($cache_name))) {
            $data = $this->table(C('DB_PREFIX') . 'keywords_group')->field('keywords_gid,keywords_name')->select();
            // 写入缓存数据
            S($cache_name,$data,120);
            return $data;
        }else{
            return S($cache_name);
        }
    }
    /**
     * @param $gid 关键词分类的ID
     * @author y0umer <jackie.zmy@gmail.com>
     * @return array
     *
     */
    public function getAllKeywords_bygid_count($gid){
        $getAllKeywords_bygid_count_cache = S('getAllKeywords_bygid_count');
        if(!empty($gid)) {
            if(empty($getAllKeywords_bygid_count_cache)) {
                $data = $this->table(C('DB_PREFIX') . 'keywords_group')->where(array('keywords_gid' => (int)$gid))->field("keywords_name")->find();
                S('getAllKeywords_bygid_count',$data,120);
                return $data;
            }
            return S('getAllKeywords_bygid_count');
        }
    }

    private function getCount_for_access($gid){
        $getCount_for_access_cache = S('getCount_for_access');
        if(!empty($gid)) {
            if (empty($getCount_for_access_cache)) {
                $data = $this->table(C('DB_PREFIX') . 'keywords_access')->where(array('keywords_gid' => (int)$gid))->count();
                S('getCount_for_access', $data,120);
                return $data;
            }
            return S('getCount_for_access');
        }
    }

    /**
     * @names getAlltables 获取一个包含关键词总数及分类详细信息
     * @author y0umer <jackie.zmy@gmail.com>
     * @return mixed 返回一个二维数组
     */

    public function getAlltable(){
        // 主词
        $sqli1 = "SELECT DISTINCT seo_keywords_group.keywords_gid,seo_keywords_group.keywords_create_time, seo_keywords_group.keywords_name,seo_keywords_group.keywords_remarks,count(*) as title_count FROM seo_keywords_group INNER JOIN seo_keywords_access AS t ON t.keywords_gid = seo_keywords_group.keywords_gid WHERE t.keywords_types = 1 GROUP BY seo_keywords_group.keywords_gid ORDER BY seo_keywords_group.keywords_gid ASC LIMIT 0,20";
        // 附词
        $sqli2 = "SELECT DISTINCT seo_keywords_group.keywords_gid,seo_keywords_group.keywords_create_time, seo_keywords_group.keywords_name,seo_keywords_group.keywords_remarks,count(*) as lf_count FROM seo_keywords_group INNER JOIN seo_keywords_access AS t ON t.keywords_gid = seo_keywords_group.keywords_gid WHERE t.keywords_types = 0 GROUP BY seo_keywords_group.keywords_gid ORDER BY seo_keywords_group.keywords_gid ASC LIMIT 0,20";
        // 接收结果集
        $response_1 = $this->query($sqli1);
        $response_2 = $this->query($sqli2);

        // 刷新内存堆区
        $this->finish_ob();

        for($i=0;$i<count($response_1);$i++){
            foreach($response_2 as $key=>$value){
                // 二叉树算法
                if($response_1[$i]['keywords_gid'] == $response_2[$key]['keywords_gid'])
                {
                    $response_1[$i]['lf_count'] = $response_2[$key]['lf_count'];
                }

            }
        }
        //释放结果集2
        unset($response_2);
        return $response_1;
    }

    private function getAllKeywords_tables(){
        $data =  $this->table(C('DB_PREFIX') . 'keywords_group')->field('keywords_gid,keywords_name,keywords_create_time')->order('keywords_gid ASC')->select();
        // 修复数组索引下标问题
        return $this->reArray_number($data);
}

    /**
     *  getRandskeywords 获取随机关键词
     * @author y0umer <jackie.zmy@gmail.com>
     * @param $keywords_class int
     * @param $keywords_type string title,lf_title 获取缓存类型,可指定title 或 lf_title
     * @param $limit int 5 默认获取几条数据
     * @param $addCache bool true 是否加入缓存
     * @param $order int 1 or 0  随机打乱结果数组
     * @return array 返回一个关联数组
     */
    public function getRandskeywords($keywords_class = 1,$keywords_type = 'title',$limit = 5,$addCache = true,$order = 1)
    {
        // 刷新内存堆区
        // 对$keywords_class 进行数据验证
        $is_keywords_class = $this->getAllKeywords_bygid_count($keywords_class);

        if($is_keywords_class == NULL){
            return ;
        }

        $keywords_types = array('title','lt_titie');
        if(!in_array($keywords_type,$keywords_types)){
            return ;
        }
        // 还原数据库操作
        if($keywords_type == 'title')
        {
            $keywords_type = 1;
        }else{
            $keywords_type = 0;
        }

        // 根据limit循环取数组
        $limit = (int)$limit;
        // 获取某个分类的关键词总数
        $getSome_count = $this->getCount_for_access($keywords_class);
        // 临时存放结果数组
        $limit_tmp = [];

        if($limit > 1 and $limit < 50)
        {
            $sql = "SELECT keywords_text FROM seo_keywords_access where keywords_gid = '{$keywords_class}' AND keywords_types = '{$keywords_type}' GROUP BY keywords_id ORDER BY RAND() DESC LIMIT {$limit}";
            // 刷新内存堆区
            $this->finish_ob();

            $limit_tmp = $this->query($sql);
        }

        // echo $this->getLastSql();
        return $limit_tmp;


        // $this->table('seo_keywords_access')->where(array('keywords_gourp_id' => $keywords_class));

        // return array();
    }
    private function finish_ob(){
        ob_end_flush();
        ob_flush();
        flush();
        ob_start();
    }
}