<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//DB操作公共模型
class Dbcommon extends Model
{
    /*
     * 添加数据公共方法
     * @param $table 表名 string
     * @param $data 要添加的数据 array()
     * @return boolean
     * */
    public function common_insert($table,$data){
        $result=DB::table($table)
            ->insert($data);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * 删除数据公共方法 !!!!!!!!!除非特殊情况,尽量不要用真删除!!!!!!!!
     * @param $where 条件 array()
     * @param $table 表名 string
     * @param return int
     */
    public function common_delete($table,$where){
        $result= DB::table($table)
            ->where($where)
            ->delete();
        return $result;
    }
    /*
     * 修改数据公共方法
     * @param $data 待修改的数据 array()
     * @param $where 修改的条件  array()
     * @param $table 修改的表  string
     * @return boolean
     */
    public function common_update($table,$where,$data){
        $affected = DB::table($table)
            ->where($where)
            ->update($data);
        if($affected<0){
            return false;
        }else{
            return true;
        }
    }
    /*
     * 公共方法简单查询单条数据(带排序)
     * @param $table 表名 字符串
     * @param $select 待查询的字段
     * @param $where 条件
     * @param $order 排序字段
     * @param $orderway 排序方式
     */
    public function common_select($table,$where,$select,$order=null,$orderway='desc'){
        if(!is_null($order)){
            $data = DB::table($table)
                ->where($where)
                ->select($select)
                ->orderby($order,$orderway)
                ->first();
        }else{
            $data = DB::table($table)
                ->where($where)
                ->select($select)
                ->first();
        }
        return $data;
    }

    /*
     *公共方法简单查询多条数据带排序
     * @param  $table 表名 string
     * @param $where 条件 array()
     * @param $select 待查询的字段 array() or string
     * @param $page 页数 int !!!!!!!传过来的数据从1开始,方便前端理解!!!!
     * @param $limit 每页限制条数 默认为10 int
     * @param $order 待排序的字段  string
     * @param $orderway 排序的方式 默认为desc  string
     * @return array object
     */
    public function  common_selects($table,$where,$select,$page=0,$limit=10,$order=null,$orderway='desc'){
        if($page!=0){//要进行分页
            $offect=($page-1)*$limit;
            if(!is_null($order)){//要进行排序操作
                $data = DB::table($table)
                    ->where($where)
                    ->select($select)
                    ->orderby($order,$orderway)
                    ->offset($offect)
                    ->limit($limit)
                    ->get();
            }else{//不要进行排序操作
                $data = DB::table($table)
                    ->where($where)
                    ->select($select)
                    ->offset($offect)
                    ->limit($limit)
                    ->get();
            }
        }else{//不用进行分页
            if(!is_null($order)){//要进行排序操作
                $data = DB::table($table)
                    ->where($where)
                    ->select($select)
                    ->orderby($order,$orderway)
                    ->get();
            }else{//不要进行排序操作
                $data = DB::table($table)
                    ->where($where)
                    ->select($select)
                    ->get();
            }
        }
        return $data;
    }

    /*
     * 聚合函数 count()  统计
     * @param $table 表名 string
     * @param $where 计算条件 array()
     * @return int
     */
    public function common_count($table,$where){
        $num = DB::table($table)
            ->where($where)
            ->count();
        if(is_null($num)){
            $num=0;
        }
        return $num;
    }

    /*
     * 聚合函数 count()  统计其中两个字段相等的条数
     * @param $table 表名 string
     * @param $where 计算条件 array()
     * @param $whereColumn1 第一个相等字段
     * @param $whereColumn2 第二个相等字段
     * @return int
     */
    public function common_count_wherecolumn($table,$where,$whereColumn1,$whereColumn2){
        $num = DB::table($table)
            ->where($where)
            ->whereColumn($whereColumn1,$whereColumn2)
            ->count();
        if(is_null($num)){
            $num=0;
        }
        return $num;
    }
    /*
     * 模糊查询数据公共方法
     * @param  $table 表名 string
     * @param $where 条件 array()
     * @param $select 待查询的字段 array() or string
     * @param $page 页数 int !!!!!!!传过来的数据从1开始,方便前端理解!!!!
     * @param $limit 每页限制条数 默认为10 int
     * @param $item 待比较的字段  string
     * @param $condition 待比较的字段的值  string
     * */
    public function common_like($table,$where,$item,$condition,$select,$page=0,$limit=10){
        if($page>0){
            $offect=($page-1)*$limit;
            return DB::table($table)
                ->where($where)
                ->where($item, 'like', '%'.$condition.'%')
                ->select($select)
                ->offset($offect)
                ->limit($limit)
                ->get();
        }else{
            return DB::table($table)
                ->where($where)
                ->where($item, 'like', '%'.$condition.'%')
                ->select($select)
                ->get();
        }

    }
    /*
     * 公共方法简单连接查询多条数据带排序和分页
     * @param  $table_1 表1表名    string
     * @param  $table_2 表2表名    string
     * @param  $item_1 表1连接字段 string
     * @param  $item_2 表2连接字段 string
     * @param  $where 查询条件 array()
     * @param  $select 查询条件 array()
     * @param $page 页数 int !!!!!!!传过来的数据从1开始,方便前端理解!!!!
     * @param $limit 每页限制条数 默认为10 int
     * @param $order 待排序的字段  string
     * @param $orderway 排序的方式 默认为desc  string
     * @return array object
     */
    public function common_join_selects($table_1,$table_2,$item_1,$item_2,$where,$select,$page=0,$limit=10,$order=null,$orderway='desc')
    {
        if($page!==0){//要进行分页
            $offect=($page-1)*$limit;
            if(!is_null($order)){//要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->where($where)
                    ->select($select)
                    ->orderby($order,$orderway)
                    ->offset($offect)
                    ->limit($limit)
                    ->get();
            }else{//不要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->where($where)
                    ->select($select)
                    ->offset($offect)
                    ->limit($limit)
                    ->get();
            }
        }else{//不用进行分页
            if(!is_null($order)){//要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->where($where)
                    ->select($select)
                    ->orderby($order,$orderway)
                    ->get();
            }else{//不要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->where($where)
                    ->select($select)
                    ->get();
            }
        }
        return $data;
    }

    /*
     * 公共方法简单连接查询单条数据
     * @param  $table_1 表1表名    string
     * @param  $table_2 表2表名    string
     * @param  $item_1 表1连接字段 string
     * @param  $item_2 表2连接字段 string
     * @param  $where 查询条件 array()
     * @param  $select 查询条件 array()
     * @return array object
     */
    public function common_join_select($table_1,$table_2,$item_1,$item_2,$where,$select)
    {

        return DB::table($table_1.' as t1')
            ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
            ->where($where)
            ->select($select)
            ->first();

    }

    /*
* 公共方法简单连接查询多条数据带排序和分页 三表连查询
* @param  $table_1 表1表名    string
* @param  $table_2 表2表名    string
* @param  $table_3 表3表名    string
* @param  $item_1 表1连接字段 string
* @param  $item_2 表2连接字段 string
* @param  $item_3 表3连接字段 string
* @param  $where 查询条件 array()
* @param  $select 查询条件 array()
* @param $page 页数 int !!!!!!!传过来的数据从1开始,方便前端理解!!!!
* @param $limit 每页限制条数 默认为10 int
* @param $order 待排序的字段  string
* @param $orderway 排序的方式 默认为desc  string
* @return array object
*/
    public function common_join_three_selects($table_1,$table_2,$table_3,$item_1,$item_2,$item_3,$item_4,$where,$select,$page=0,$limit=10,$order=null,$orderway='desc')
    {
        if($page!==0){//要进行分页
            $offect=($page-1)*$limit;
            if(!is_null($order)){//要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->leftjoin($table_3.' as t3','t1.'.$item_3,'=','t3.'.$item_4)
                    ->where($where)
                    ->select($select)
                    ->orderby($order,$orderway)
                    ->offset($offect)
                    ->limit($limit)
                    ->get();
            }else{//不要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->leftjoin($table_3.' as t3','t1.'.$item_3,'=','t3.'.$item_4)
                    ->where($where)
                    ->select($select)
                    ->offset($offect)
                    ->limit($limit)
                    ->get();
            }
        }else{//不用进行分页
            if(!is_null($order)){//要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->leftjoin($table_3.' as t3','t1.'.$item_3,'=','t3.'.$item_4)
                    ->where($where)
                    ->select($select)
                    ->orderby($order,$orderway)
                    ->get();
            }else{//不要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->leftjoin($table_3.' as t3','t1.'.$item_3,'=','t3.'.$item_4)
                    ->where($where)
                    ->select($select)
                    ->get();
            }
        }
        return $data;
    }

    /*
* 公共方法简单连接查询单条数据 三表连查询
* @param  $table_1 表1表名    string
* @param  $table_2 表2表名    string
* @param  $table_3 表3表名    string
* @param  $item_1 表1连接字段 string
* @param  $item_2 表2连接字段 string
* @param  $item_3 表3连接字段 string
* @param  $where 查询条件 array()
* @param  $select 查询条件 array()
* @param $order 待排序的字段  string
* @param $orderway 排序的方式 默认为desc  string
* @return array object
*/
    public function common_join_three_select($table_1,$table_2,$table_3,$item_1,$item_2,$item_3,$item_4,$where,$select,$order=null,$orderway='desc')
    {
        if(!is_null($order)){//要进行排序操作
            $data = DB::table($table_1.' as t1')
                ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                ->leftjoin($table_3.' as t3','t1.'.$item_3,'=','t3.'.$item_4)
                ->where($where)
                ->select($select)
                ->orderby($order,$orderway)
                ->first();
        }else{//不要进行排序操作
            $data = DB::table($table_1.' as t1')
                ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                ->leftjoin($table_3.' as t3','t1.'.$item_3,'=','t3.'.$item_4)
                ->where($where)
                ->select($select)
                ->first();
        }
        return $data;
    }

    /*
     * 公共方法连接查询多条数据并根据字段分组
     * @param  $table_1 表1表名    string
     * @param  $table_2 表2表名    string
     * @param  $item_1 表1连接字段 string
     * @param  $item_2 表2连接字段 string
     * @param  $where 查询条件 array()
     * @param  $select 查询字段 array()
     * @param $groupby 分组字段
     * @return array object
     */
    public function common_join_groupby($table_1,$table_2,$item_1,$item_2,$where,$select,$groupby)
    {

        $data = DB::table($table_1.' as t1')
            ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
            ->where($where)
            ->select($select)
            ->groupBy($groupby)
            ->get();
        return $data;
    }

    /*
     * 公共方法插入单条数据并且获取该条数据的自增ID
     * @param  $table 表名    string
     * @param  $data  插入数据 array()
     * @return int   自增ID（该条数据的ID）
     */
    public function insert_and_get_id($table,$data){
        return DB::table($table)
            ->insertGetId($data);
    }

    /*
     * 公共方法设置字段自增并更改若干条其他字段
     * @param  $table 表名   string
     * @param  $where 更新的条件 array()
     * @param  $item 更新的字段名 string
     * @param  $incre  要增加的值 int
     * @param  $update 需要更新的其它字段 array()
     * @return int  受影响的行数
     */
    public function common_increment($table,$where,$item,$incre,$update=array()){
        if(empty($update)){
            return DB::table($table)
                ->where($where)
                ->increment($item,$incre);
        }else{
            return DB::table($table)
                ->where($where)
                ->increment($item,$incre,$update);
        }
    }


    /*
     * 公共方法设置字段自减并更改若干条其他字段(试用前自己判断字段是否会小于零)
     * @param  $table 表名   string
     * @param  $where 更新的条件 array()
     * @param  $item 更新的字段名 string
     * @param  $decre  要减少的值 int
     * @param  $update 需要更新的其它字段 array()
     * @return int  受影响的行数
     */
    public function common_decrement($table,$where,$item,$decre,$update=array()){
        if(empty($update)){
            return DB::table($table)
                ->where($where)
                ->decrement($item,$decre);
        }else{
            return DB::table($table)
                ->where($where)
                ->decrement($item,$decre,$update);
        }
    }
    /*
     * 公共方法wherein查询
     * @param  $table 表名   string
     * @param  $where 查询条件 array()
     * @param  $item 更新的字段名 string
     * @param  $in   in条件 array()
     * @param  $select 选择条件 array() or string
     * @return boolean
     */
    public function wherein_selects($table,$where,$item,$in,$select)
    {
        return DB::table($table)
            ->where($where)
            ->whereIn($item,$in)
            ->select($select)
            ->get();
    }
    /*
     * 公共方法简单连接查询多条数据带排序和分页
     * @param  $table_1 表1表名    string
     * @param  $table_2 表2表名    string
     * @param  $item_1 表1连接字段 string
     * @param  $item_2 表2连接字段 string
     * @param  $where 查询条件 array()
     * @param  $select 查询条件 array()
     * @param $page 页数 int !!!!!!!传过来的数据从1开始,方便前端理解!!!!
     * @param $limit 每页限制条数 默认为10 int
     * @param $order 待排序的字段  string
     * @param $orderway 排序的方式 默认为desc  string
     * @return array object
     * @param  $item 更新的字段名 string
     * @param  $in   in条件 array()
     */
    public function wherein_join_selects($table_1,$table_2,$item_1,$item_2,$where,$item,$in,$select,$page=0,$limit=10,$order=null,$orderway='desc')
    {
        if($page!==0){//要进行分页
            $offect=($page-1)*$limit;
            if(!is_null($order)){//要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->where($where)
                    ->whereIn($item,$in)
                    ->select($select)
                    ->orderby($order,$orderway)
                    ->offset($offect)
                    ->limit($limit)
                    ->get();
            }else{//不要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->where($where)
                    ->whereIn($item,$in)
                    ->select($select)
                    ->offset($offect)
                    ->limit($limit)
                    ->get();
            }
        }else{//不用进行分页
            if(!is_null($order)){//要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->where($where)
                    ->whereIn($item,$in)
                    ->select($select)
                    ->orderby($order,$orderway)
                    ->get();
            }else{//不要进行排序操作
                $data = DB::table($table_1.' as t1')
                    ->leftjoin($table_2.' as t2','t1.'.$item_1,'=','t2.'.$item_2)
                    ->where($where)
                    ->whereIn($item,$in)
                    ->select($select)
                    ->get();
            }
        }
        return $data;
    }

    /**
     * 字段加1操作
     * @param    $table   表名     string
     * @param    $where   条件      []
     * @param    $field   字段名称   string
     * @return    bool
     */
    public function common_increase($table,$where,$field){
        $data =  DB::table($table)
            ->where($where)
            ->increment($field);
        return $data;
    }

    /**
     * 字段减1操作
     * @param    $table   表名     string
     * @param    $where   条件      []
     * @param    $field   字段名称   string
     * @return    bool
     */
    public function common_decrease($table,$where,$field){
        $data =  DB::table($table)
            ->where($where)
            ->decrement($field);
        return $data;
    }
}