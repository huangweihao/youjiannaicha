<?php
/**
 * 模型基础入口类
 * Class Base
 * @package app\model
 */
namespace app\model;

use think\Model;
use think\facade\Db;

class Base extends Model{
    private $dbConnect = '';
    /**
     * 获取完整的数据表名
     * @param string $key 数据表的key
     * @param array $suffix
     * @param boolean $needConnect
     * @return string 数据表名
     */
    protected function tableInit($key,$suffix=[],$needConnect=true){
        $initTable = [
            'upload_file' => ['table'=>'sj_upload_file','db'=>'db_config'],
            'upload_group' => ['table'=>'sj_upload_group','db'=>'db_config'],
            'role' => ['table'=>'sj_role','db'=>'db_config'],
            'manager' => ['table'=>'sj_manager','db'=>'db_config'],
            'manager_work_log' => ['table'=>'sj_manager_work_log','db'=>'db_config'],
            'manager_login_log' => ['table'=>'sj_manager_login_log','db'=>'db_config'],
            'volunteer' => ['table'=>'sj_volunteer_user','db'=>'db_config'],
            'volunteer_team' => ['table'=>'sj_volunteer_team','db'=>'db_config'],
            'organize' => ['table'=>'sj_organize','db'=>'db_config'],
            'train' => ['table'=>'sj_train','db'=>'db_config'],
            'course' => ['table'=>'sj_course','db'=>'db_config'],
            'message' => ['table'=>'sj_message','db'=>'db_config'],
            'activity' => ['table'=>'sj_activity','db'=>'db_config'],
            'schedule' => ['table'=>'sj_schedule','db'=>'db_config'],
            'user' => ['table'=>'sj_user','db'=>'db_config'],
            'activity_join' => ['table'=>'sj_activity_join','db'=>'db_config'],
            'level' => ['table'=>'sj_volunteer_level','db'=>'db_config'],
            'comment' => ['table'=>'sj_comment','db'=>'db_config'],
            'thirdparty_user' => ['table'=>'sj_thirdparty_user','db'=>'db_config'],
            'third_activity' => ['table'=>'sj_activity','db'=>'db_config'],
            'activity_option' => ['table'=>'sj_activity_option','db'=>'db_config'],
            'activity_op_value' => ['table'=>'sj_activity_op_value','db'=>'db_config'],
            'reward' => ['table'=>'sj_reward','db'=>'db_config'],
            'article_type' => ['table'=>'sj_article_type','db'=>'db_config'],
            'article' => ['table'=>'sj_article','db'=>'db_config'],
            'advert' => ['table'=>'sj_advert','db'=>'db_config'],
            'advert_type' => ['table'=>'sj_advert_type','db'=>'db_config'],
            'volunteer_reward' => ['table'=>'sj_volunteer_reward','db'=>'db_config'],
        ];
        if(!empty($initTable[$key])){
            $tableDb = $initTable[$key];
            if($needConnect)$this->dbConnect = $tableDb['db'];
            return $tableDb['table'];
        }else{
            return null;
        }
    }
    /**
     * 生成后缀
     * @param string $type
     * @param array $suffix
     * @return string
     */
    protected function generateTableSuffix($type,$suffix){
        switch ($type){
            case 'role':
            case 'manager':
            case 'manager_work_log':
            case 'manager_login_log':
                return $type;
                break;
            case 'test'://分表测试
                $last = floor($suffix[1]/3000);
                return (int)$suffix[0].'_'.$last;
                break;
        }
    }
    /**
     * MYSQL函数重组数据
     * @param array $field 更新的字段
     * @return array 重组后的字段数组
     */
    protected function reformRaw($field){
        $backData = [];
        foreach($field as $key=>$value){
            if(is_array($value)){
                switch ($value['rule']){
                    case 'raw':
                        $backData[$key] = Db::raw($value['val']);
                        break;
                }
            }else{
                $backData[$key] = $value;
            }
        }
        return $backData;
    }
    /**
     * 执行SQL语句
     * @param string $sql
     * @param array $data sql中的占位符数据
     * @return boolean
     */
    public function executeDbData($sql,$data=[]){
        try{
            return DB::connect($this->dbConnect)->execute($sql,$data);
        }catch(\Exception $e){
            $logData = [
                '语句' => $sql,
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('executeDbData',$logData);
            return false;
        }
    }
    /**
     * 查询SQL语句
     * @param boolean $isProcedure 是否存储过程
     * @param string $sql
     * @param array $data sql中的占位符数据
     * @return boolean
     */
    public function queryDbData($isProcedure=false,$sql,$data=[]){
        try{
            if($isProcedure){
                return DB::connect($this->dbConnect)->procedure(true)->query($sql,$data);
            }else{
                return DB::connect($this->dbConnect)->query($sql,$data);
            }
        }catch(\Exception $e){
            $logData = [
                '过程' => $isProcedure,
                '语句' => $sql,
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('queryDbData',$logData);
            return false;
        }
    }
    /**
     * join查询构造
     * @param string $table DB对象实例
     * @return mixed
     */
    protected function generateInstance(&$table){
        try{
            $dbInstance = null;
            if(is_array($table)){
                $dbInstance = DB::connect($this->dbConnect);
                foreach($table as $mainTable=>$mainValue){
                    $dbInstance->table($mainTable);
                    $dbInstance->alias($mainValue['alias']);
                    foreach($mainValue['joins'] as $childTable=>$childValue){
                        switch ($childValue['rule']){
                            case 'inner':
                                $dbInstance->join($childTable.' '.$childValue['alias'],$childValue['val']);
                                break;
                            case 'left':
                                $dbInstance->leftJoin($childTable.' '.$childValue['alias'],$childValue['val']);
                                break;
                            case 'right':
                                $dbInstance->rightJoin ($childTable.' '.$childValue['alias'],$childValue['val']);
                                break;
                            case 'full':
                                $dbInstance->fullJoin($childTable.' '.$childValue['alias'],$childValue['val']);
                                break;
                        }
                    }
                }
            }else{
                $dbInstance = DB::connect($this->dbConnect)->table($table);
            }
            return $dbInstance;
        }catch(\Exception $e){
            $logData = [
                '表' => $table,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('generateInstance',$logData);
            return false;
        }
    }
    /**
     * 查询条件构造
     * @param object $instance DB对象实例
     * @param array $condition 查询条件
     */
    protected function generateCondition(&$instance,&$condition){
        foreach($condition as $key=>$value){
            switch ($value['rule']){
                case 'equal':
                    $instance->where($key,$value['val']);
                    break;
                case 'notequal':
                    $instance->where($key,'<>',$value['val']);
                    break;
                case 'in':
                    $instance->whereIn($key,$value['val']);
                    break;
                case 'notIn':
                    $instance->whereNotIn($key,$value['val']);
                    break;
                case 'large':
                    $instance->where($key,'>',$value['val']);
                    break;
                case 'largeEqual':
                    $instance->where($key,'>=',$value['val']);
                    break;
                case 'small':
                    $instance->where($key,'<',$value['val']);
                    break;
                case 'smallEqual':
                    $instance->where($key,'<=',$value['val']);
                    break;
                case 'like':
                    $instance->where($key,'like','%'.$value['val'].'%');
                    break;
                case 'like_right':
                    $instance->where($key,'like',$value['val'].'%');
                    break;
                case 'or':
                    $instance->whereOr($key,$value['val']);
                    break;
                case 'between':
                    $instance->whereBetween($key,[$value['val'][0],$value['val'][1]]);
                    break;
            }
        }
    }
    /**
     * 获取某个查询的总量
     * @param string $table 表名
     * @param array $condition 查询条件
     * @param object $dbInstance
     * @return int 数量
     */
    protected function getCountData($table,&$condition,&$dbInstance=null){
        try{
            if(empty($dbInstance)){
                $dbInstance = $this->generateInstance($table);
            }
            $this->generateCondition($dbInstance,$condition);
            return $dbInstance->count();
        }catch(\Exception $e){
            $logData = [
                '表' => $table,
                '条件' => $condition,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('getCountData',$logData);
            return false;
        }
    }
    /**
     * 获取列表数据
     * @param string $table 表名
     * @param string $field 需要的字段
     * @param array $condition 查询条件
     * @param array $order 排序规则
     * @param int $limit 每页显示条数
     * @return array 查询结果
     */
    protected function getListData($table,&$field,&$condition,&$order,$limit=1){
        try{
            $dbInstance = $this->generateInstance($table);
            $this->generateCondition($dbInstance,$condition);
            if(!empty($field['rule'])){
                $dbInstance->fieldRaw($field['data']);
            }else{
                $dbInstance->field($field);
            }

            if(!empty($order)){
                $dbInstance->order($order);
            }
            if(!empty($limit)){
                $dbInstance->limit($limit);
            }
            return $dbInstance->select()->toArray();
        }catch(\Exception $e){
            $logData = [
                '表' => $table,
                '字段' => $field,
                '条件' => $condition,
                '排序' => $order,
                '条数' => $limit,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('getListData',$logData);
            return false;
        }
    }
    /**
     * 获取列表带TP分页数据
     * @param string $table 表名
     * @param string $field 需要的字段
     * @param array $condition 查询条件
     * @param array $order 排序规则
     * @param int $pageSize 每页显示条数
     * @param string $query 分页参数
     * @return array 查询结果
     */
    protected function getListPaginateData($table,&$field,&$condition,&$order,$pageSize=0,$query=''){
        try{
            $dbInstance = $this->generateInstance($table);
            $this->generateCondition($dbInstance,$condition);
            $dbInstance->field($field);
            if(!empty($order)){
                $dbInstance->order($order);
            }
            return $dbInstance->paginate($pageSize, false, ['query' => $query]);
        }catch(\Exception $e){
            $logData = [
                '表' => $table,
                '字段' => $field,
                '条件' => $condition,
                '排序' => $order,
                '分页' => $pageSize,
                '参数' => $query
            ];
            $this->recordLog('getListPaginateData',$logData);
            return false;
        }
    }
    /**
     * 获取列表分页数据
     * @param string $table 表名
     * @param string $field 需要的字段
     * @param array $condition 查询条件
     * @param array $order 排序规则
     * @param int $page 页码
     * @param int $pageSize 每页显示条数
     * @return array 查询结果
     */
    protected function getListPageData($table,&$field,&$condition,&$order,$page=1,$pageSize=20){
        try{
            $dbInstance = $this->generateInstance($table);
            $this->generateCondition($dbInstance,$condition);
            if(!empty($order)){
                $dbInstance->order($order);
            }
            return $dbInstance->field($field)->paginate([
                'list_rows'=> $pageSize,
                'page' => $page,
            ])->toArray();
        }catch(\Exception $e){
            $logData = [
                '表' => $table,
                '字段' => $field,
                '条件' => $condition,
                '排序' => $order,
                '分页' => $page.'-'.$pageSize,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('getListPageData',$logData);
            return false;
        }
    }
    /**
     * 获取某条数据详情
     * @param string $table 表名
     * @param string $field 需要的字段
     * @param array $condition 查询条件
     * @param array $order 排序规则
     * @return array 查询结果
     */
    protected function getDetailData($table,&$field,&$condition,$order=[]){
        try{
            $dbInstance = $this->generateInstance($table);
            $this->generateCondition($dbInstance,$condition);
            $dbInstance->field($field);
            if(!empty($order)){
                $dbInstance->order($order);
            }
            return $dbInstance->find();
        }catch(\Exception $e){
            $logData = [
                '表' => $table,
                '字段' => $field,
                '条件' => $condition,
                '排序' => $order,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('getDetailData',$logData);
            return false;
        }
    }
    /**
     * 添加数据
     * @param string $table 表名
     * @param array $field 更新的字段
     * @return int 添加的ID
     */
    protected function insertDbData($table,&$field){
        try{
            $dbInstance = $this->generateInstance($table);
            return $dbInstance->insertGetId($field);
        }catch(\Exception $e){
            $logData = [
                '表' => $table,
                '字段' => $field,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('insertDbData',$logData);
            return false;
        }
    }
    /**
     * 更新数据
     * @param string $table 表名
     * @param array $field 更新的字段
     * @param array $condition 查询条件
     * @return array 更新结果
     */
    protected function updateDbData($table,&$field,&$condition){
        try{
            $dbInstance = $this->generateInstance($table);
            $this->generateCondition($dbInstance,$condition);
            $dbInstance->data($field);
            return $dbInstance->update();
        }catch(\Exception $e){
            $logData = [
                '表' => $table,
                '字段' => $field,
                '条件' => $condition,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('updateDbData',$logData);
            return false;
        }
    }
    /**
     * 删除数据
     * @param string $table 表名
     * @param array $condition 查询条件
     * @return mixed
     */
    protected function delDbData($table,&$condition){
        try{
            $dbInstance = $this->generateInstance($table);
            $this->generateCondition($dbInstance,$condition);
            return $dbInstance->delete();
        }catch(\Exception $e){
            $logData = [
                '表' => $table,
                '条件' => $condition,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('delDbData',$logData);
            return false;
        }
    }
    /**
     * Duplicate执行SQL语句,添加存在则更新数据
     * @param string $table 表名
     * @param array $data ['insert','except','data']
     * @return boolean
     */
    public function insertDuplicate($table,&$data){
        try{
            $dbInstance = $this->generateInstance($table);
            return $dbInstance->duplicate($data['update'])->insert($data['insert']);
        }catch(\Exception $e){
            $logData = [
                '表' => $table,
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('insertDuplicate',$logData);
            return false;
        }
    }
    /**
     * 执行添加事务
     * @param array $data 入库的数据
     * @return integer 上次插入的ip
     */
    public function transInsertWithId($data){
        DB::connect($this->dbConnect)->startTrans();
        try {
            $insertId = $this->insertDbData($data['insertId']['table'],$data['insertId']['data']);
            if(!empty($insertId)){
                foreach ($data['other'] as $table=>$value){
                    switch ($value['rule']){
                        case 'duplicate':
                            $value['data']['insert'][$value['useInsertIdField']] = $insertId;
                            $value['data']['update'][$value['useInsertIdField']] = $insertId;
                            $dbInstance = $this->generateInstance($table);
                            $dbInstance->duplicate($value['data']['update'])->insert($value['data']['insert']);
                            break;
                        case 'update':
                            $value['data'][$value['useInsertIdField']] = $insertId;
                            $dbInstance = $this->generateInstance($table);
                            $this->generateCondition($dbInstance,$value['condition']);
                            $dbInstance->data($value['data']);
                            $dbInstance->update();
                            break;
                        default:
                            $value['data'][$value['useInsertIdField']] = $insertId;
                            $dbInstance = $this->generateInstance($table);
                            $dbInstance->insert($value['data']);
                            break;
                    }
                }
                DB::connect($this->dbConnect)->commit();
            }
            return $insertId;
        } catch (\Exception $e) {
            DB::connect($this->dbConnect)->rollback();
            $logData = [
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('transInsertWithId',$logData);
            return false;
        }
    }
    /**
     * 执行更新事务
     * @param array $data 修改的数据
     * @return mixed
     */
    public function transWork($data){
        DB::connect($this->dbConnect)->startTrans();
        try {
            foreach ($data as $table=>$value){
                switch ($value['rule']){
                    case 'duplicate':
                        $dbInstance = $this->generateInstance($table);
                        $dbInstance->duplicate($value['data']['update'])->insert($value['data']['insert']);
                        break;
                    case 'insertUpdate':
                        $dbInstance = $this->generateInstance($table);
                        $count = $this->getCountData($table,$value['condition'],$dbInstance);
                        if($count > 0){
                            $dbInstance->data($value['data']);
                            $dbInstance->update();
                        }else{
                            $dbInstance->insert($value['data']);
                        }
                        break;
                    case 'update':
                        $dbInstance = $this->generateInstance($table);
                        $this->generateCondition($dbInstance,$value['condition']);
                        $dbInstance->data($value['data']);
                        $dbInstance->update();
                        break;
                    case 'del':
                        $dbInstance = $this->generateInstance($table);
                        $this->generateCondition($dbInstance,$value['condition']);
                        $dbInstance->delete();
                        break;
                    case 'insert':
                        $dbInstance = $this->generateInstance($table);
                        $dbInstance->insert($value['data']);
                        break;
                }
            }
            DB::connect($this->dbConnect)->commit();
            return true;
        } catch (\Exception $e) {
            DB::connect($this->dbConnect)->rollback();
            $logData = [
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('transWork',$logData);
            return false;
        }
    }
    /**
     * 记录日志
     * @param string $errName
     * @param array $logData
     */
    private function recordLog($errName,&$logData){
        doRecordLog($errName,$logData,'sql');
    }
}