<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/19
 * Time: 21:01
 */

namespace Common\Model;
use Common\Model\BaseModel;

class ArticleModel extends BaseModel
{
    protected $connection = 'ARTICLE_DB';
    protected $tablePrefix = 'article_';
    protected $tableName = 'contents';

    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct($name, $tablePrefix, $connection);
    }

    /** 统计文章数量
     * @return int
     */
    protected function a_count(){
       $cached_name = 'platform_article_model_count_for_ArticleModel.class.php';
       $cache = S($cached_name);
       if(empty($cache)) {
           $sql = 'SELECT COUNT(*) AS count FROM article_contents ORDER BY `article_id` DESC LIMIT 0,1';
           $query = $this->query($sql);
           S($cache,$query,3600);
       }else{
           $query = S($cache);
       }
       return (int)$query[0]['count'];

    }

    /**
     *  获取一篇随机文章
     * @return mixed
     */
    public function get_article()
    {
        $random = mt_rand(1,$this->a_count() -1 / 5);
        $sql = 'SELECT article_id,article_title,article_text FROM "article_contents" WHERE article_id = '.$random. ' LIMIT 1';
        $cache_name = 'platform_article_model_get_article_for_ArticleModel.class.php' . md5($sql);
        $cache = S($cache_name);
        if(empty($cache)) {
            $row = $this->query($sql);
            S($cache_name,$row,3600);
        }else{
            $row = S($cache_name);
        }

            return $row;
    }

    /**
     *  随机获取文章标题
     * @param $limit
     * @return array|mixed
     */
    public function get_article_title($limit)
    {
        $results_now = array();
        $sql = "SELECT article_title FROM `article_contents` ORDER BY random() DESC LIMIT {$limit}";
        $results_now = $this->query($sql);
        return $results_now;
    }
}