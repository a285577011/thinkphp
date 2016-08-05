<?php
/**
 * Shpinx搜索模型
 */
namespace Common\Model;

use Think\Model;

class SphinxModel extends Model {
    
    public $limit = 10;
    public $s = 0;
    function __construct(){
        vendor('Coreseek.api.sphinxapi');
        // 加载第三方扩展包的文件 文件名不包含class
        $this->spx = new \SphinxClient();
        $this->spx->SetServer('10.0.0.188', 9312); // ip地址及端口可放在构造函数中当参数
        $this->spx->SetConnectTimeout(3);
        
        //SPH_MATCH_ALL, 匹配所有查询词(默认模式); SPH_MATCH_ANY, 匹配查询词中的任意一个; SPH_MATCH_EXTENDED2, 支持特殊运算符查询
        $this->spx->setMatchMode(SPH_MATCH_EXTENDED2);
        $this->spx->setMaxQueryTime(10);										//设置最大搜索时间
        $this->spx->SetArrayResult(true);										//是否将Matches的key用ID代替
        $this->spx->SetSelect ( "*" );											//设置返回信息的内容,等同于SQL
        //$this->spx->SetRankingMode(SPH_RANK_BM25);							//设置评分模式，SPH_RANK_BM25可能使包含多个词的查询的结果质量下降。
    }
    
    /**
     +----------------------------------------------------------
     * sphinx 检索
     +----------------------------------------------------------
     * @access public
     * @param $idx string 索引名称
     * @param $kw string 关键字
     * @param $filter mixed 过滤条件
     +----------------------------------------------------------
     * @return array('id串','检索到的总数','花费时间','匹配结果')
     +----------------------------------------------------------
     * @throws ThinkException
     +----------------------------------------------------------
     */
    
    public function search ($idx, $kw, $filter = null, $sql=false)
    {
    	$sql && $this->spx->SetSelect($sql);
        isset($filter['limit']) && $this->limit = $filter['limit'];
        isset($filter['page']) && $this->s = $filter['page']-1;
        $this->spx->limit = $this->limit;
        $this->spx->startRc = $this->s * $this->limit;
        //$this->spx->SetMatchMode(SPH_MATCH_ALL);
        // 设置过滤条件
        if (! is_null($filter['where'])) {
            foreach ($filter['where'] as $attr => $value) {
                $value=(array)$value;
                switch (strtolower($value[0])){
                    case 'gt':
                        $this->spx->SetFilterRange($attr, $value[1] + 1, PHP_INT_MAX);
                        break;
                    case 'egt':
                        $this->spx->SetFilterRange($attr, $value[1], PHP_INT_MAX);
                        break;
                    case 'lt':
                        $this->spx->SetFilterRange($attr, 0, $value[1] - 1);
                        break;
                    case 'elt':
                        $this->spx->SetFilterRange($attr, 0, $value[1]);
                        break;
                    default:
                        $this->spx->SetFilter($attr, $value); // $attr为要过滤的属性,$value为属性值
                }
            }
        }
        // 排序
        if (! is_null($filter['order'])) {
            $this->spx->SetSortMode(SPH_SORT_EXTENDED, $filter['order']); // $attr为要排序的属性,$value为排序值(升\降)
        }
       // echo $this->spx->startRc.'----'.$this->spx->limit;
        $this->spx->SetLimits($this->spx->startRc, $this->spx->limit, 1000, 0);
        //$this->spx->SetMatchMode(SPH_MATCH_PHRASE); // 使用多字段模式
        
        $res = $this->spx->query(''.$kw, $idx);
        // 当没有查询到数据时
        if ($res['total'] == 0)
            return false;
        
        $idStr = implode(array_keys($res['matches']), ',');
        return array( 'ids' => $idStr, 'total' => $res['total_found'], 'list' => $res['matches'] );
    }
    
    /**根据属性返回统计数据返回值如
     * array(3) {
     [3] => string(1) "1"
     [2] => string(1) "4"
     [1] => string(1) "1"
     }
     * 键名即为属性，值为该属性下的统计数量
     *
     * */
    public function countByAttr ($idx, $kw, $attr, $filter = null)
    {
        
        // 设置过滤条件
        if (! is_null($filter) && is_array($filter)) {
            foreach ($filter as $attrKey => $value) {
                $this->spx->SetFilter($attrKey, $value);
            }
        }
        
        $this->spx->SetMatchMode(SPH_MATCH_ALL);
        $this->spx->SetGroupBy($attr, SPH_GROUPBY_ATTR);
        $res = $this->spx->Query($kw, $idx);
        
        $matches = $res['matches'];
        
        $rsArr = array();
        foreach ($matches as $value) {
            $rsArr[$value['attrs'][$attr]] = $value['attrs']['@count'];
        }
        return $rsArr;
    }
    /**
     * 
     * @param unknown $index 索引
     * @param array $value 值 KEY=>VALUE
     * @param array $id 主键ID
     * @param array where 查询ID条件
     * @return Ambigous <number, multitype:>
     */
    public function update($index,array $value,array $id=array(),$where=array()){
        $attrs = array_keys($value);
        if (! $id) {
            $res = $this->search($index, '', array( 'where' => $where ));
            if ($res['total']) {
                foreach ($res['list'] as $k => $v) {
                    $id[] = $res['list'][$k]['id'];
                }
            } else {
                return 0;
            }
        }
        $id = (array) $id;
        $values = array();
        $value = array_values($value);
        foreach ($id as $k => $v) {
            $values[$v] = $value;
        }
        return $this->spx->UpdateAttributes($index, $attrs, $values);
    }
}