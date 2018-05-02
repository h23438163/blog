<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/27
 * Time: 11:10
 */

namespace app\common;


class DAOMysqli {

    private $host;
    private $username;
    private $password;
    private $dbname;
    private $port;

    private $Mysqli;

    private static $_instance;

    private function getMysqli($option){

        $this->host = $option['host'];
        $this->username = $option['username'];
        $this->password = $option['password'];
        $this->dbname = $option['dbname'];
        $this->port= $option['port'];

        $this->Mysqli = $this->Mysqli = new mysqli($this->host,$this->username,$this->password,$this->dbname);

    }


    private function __construct($option){

        $this->getMysqli($option);
    }

    //单例模式初始化
    public static function getDaoMysqli(array $option = array()){

        if(!self::$_instance instanceof self){

            self::$_instance = new self($option);

        }
        return self::$_instance;
    }


    private function __clone(){}



    private function query($sql){

        $this->Mysqli->query('set names utf8');

        return $this->Mysqli->query($sql);

    }

    //预处理提取全部数据
    public function PrepareFetchAll($sql,$var){

        $result = $this->Prepare($sql,$var);

        //判断返回的数据的类型
        if(is_object($result)){
            //如果是object,则返回的是mysqli_result对象
            //使用fetch_assoc()遍历保存为二维数组
            while ($row = $result->fetch_assoc()){

                $result_list[] = $row;
            }

            $result->free();

            return $result_list;

        }else if (is_array($result)){
            //如果返回的是数组,说明已经遍历完成直接返回()
            return $result;

        }else {
            //如果不是以上2种就返回false
            return false;
        }


    }

    //常规方法取出全部数据
    public function FetchAll($sql){

        //执行sql语句返回mysql_result对象
        $result_obj = $this->query($sql);

        //判断是否正确返回mysql_result对象
        if(is_object($result_obj)){

            //正确则循环遍历数据并保存为二维数组
            while ($row = $result_obj->fetch_assoc()){

                $result_list[] = $row;
            }

            $result_obj->free();

            //返回二维数组
            return $result_list;

        }else {
            //如果不是mysql_result对象,则返回false
            return false;
        }

    }

    //预处理方式取出一条数据
    public function PrepareFetchRow($sql,$var){

        //Prepare为protected方法
        $result = $this->Prepare($sql,$var);

        //判断返回的数据的类型
        if(is_object($result)){

            //如果是object,则返回的是mysqli_result对象
            //使用fetch_assoc()取出一条数据保存为二维数组
            $result_row = $result->fetch_assoc();

            $result->free();

            return $result_row;

        }else if (is_array($result)){

            //如果是数组则直接返回
            return $result[0];

        }else{
            //返回false
            return false;
        }
    }

    //常规方法取出一条数据
    public function FetchRow($sql){

        //执行sql语句返回mysql_result对象
        $result_obj = $this->query($sql);

        //判断是否正确返回mysql_result对象
        if(is_object($result_obj)){

            //正确则取出一条数据并保存为二维数组
            $result_row = $result_obj->fetch_assoc();

            $result_obj->free();

            //返回数组
            return $result_row;

        }else {
            //如果不是mysql_result对象,则返回false
            return false;
        }

    }

    //获取执行sql语句所影响的行数
    public function getAffectedRows ($sql,$var = array()){

        //去除sql语句首尾处的空白字符
        $sql = trim($sql);

        //判断$var是否为空
        if(!empty($var)){

            //如果$var不为空,则是预处理
            //通过预处理返回smysqli_tmt类
            $stmt_obj = $this->Mysqli->prepare($sql);

            //绑定$var参数
            call_user_func_array(array($stmt_obj,'bind_param'),$this->refValues($var));

            //执行
            //如果执行成功
            if($stmt_obj->execute()){

                //判断sql语句的类型
                if(strpos($sql,'select') === 0){

                    //如果是select查询语句
                    //先提取数据
                    $stmt_obj->store_result();
                    //使用num_rows属性取的数据总行数并返回
                    return $stmt_obj->num_rows;

                }else {
                    //如果是update,insert,alter语句
                    //直接使用affected_rows属性取的影响行数并返回
                    return $stmt_obj->affected_rows ;
                }
            }else {

                //执行失败返回false
                return false;
            }
        }else {
            //如果$var为空,则是常规方法
            if($this->Mysqli->query($sql)){

                //sql语句执行成功
                //使用affected_rows属性取的并返回影响的行数
                return $this->Mysqli->affected_rows;

            }else {
                //执行失败返回false
                return false;
            }
        }
    }

    //预处理
    protected function Prepare($sql,$var = array()){

        //去除sql语句首尾处的空白字符
        $sql = trim($sql);

        //使用UTF8字符集
        $this->Mysqli->query('set names utf8');

        //去处理执行sql语句
        $stmt_obj = $this->Mysqli->prepare($sql);

        //绑定$var参数
        //call_user_func_array使用说明查阅手册
        call_user_func_array(array($stmt_obj,'bind_param'),$this->refValues($var));

        //执行
        if($stmt_obj->execute()){

            //判断sql语句类型
            if(strpos($sql,'select') === 0){

                //判断是否有get_result方法,这个方法必须基于mysqlnd模块,否者就不能使用这个方法
                if(method_exists($stmt_obj,'get_result')){

                    //如果有get_result方法
                    $result_obj = $stmt_obj->get_result();

                    if($result_obj->num_rows > 0){

                        //直接返回mysqli_result
                        return $result_obj;

                    }else {

                        return null;
                    }

                }else {

                    //如果没有mysqlnd模块
                    //如果是select查询语句

                    $stmt_obj->store_result();

                    if($stmt_obj->num_rows == 0){

                        return null;
                    }


                    $result = array();
                    $params = array();

                    //使用result_metadata方法的到mysqli_result类
                    //但是用result_metadata方法返回的mysqli_result不能直接遍历数据
                    //只能取得数据表的字段
                    $result_obj = $stmt_obj->result_metadata();

                    //循环遍历数据表字段,并且以引用传递的方式保存到数组里
                    //保存的数组为关联数组,关联字段为数据表字段
                    while($fields_obj = $result_obj->fetch_field()){

                        $params[] = &$result[$fields_obj->name];
                    }

                    //绑定$params参数
                    call_user_func_array(array($stmt_obj, 'bind_result'), $params);

                    //通过stmt->fetch方法将循环遍历数据表中的数据保存到二维数组中
                    //数组是关联数组且关联字段为数据表字段
                    $i=0;

                    while($stmt_obj->fetch()){

                        //可以用下面这个方法代替foreach
                        //$result_list[] = array_map(array($this, 'copy_value'),$result);

                        foreach($result as $key => $val){

                            $result_list[$i][$key] = $val;
                        }

                        $i++;
                    }

                    //释放
                    $result_obj->free();

                    $stmt_obj->free_result();

                    $stmt_obj->close();

                    return $result_list;

                }

            }else{
                //如果是update,insert,alter等sql语句
                //执行成功返回true
                return true;
            }
        }else {
            //执行失败返回false
            return false;
        }

    }

    protected function copy_value($arg){
        return $arg;
    }

    //执行增删改的sql语句
    public function CUD($sql,$var = array()){

        if(!empty($var)){
            //如果$var不为空
            //说明需要预处理
            return $this->Prepare($sql,$var);

        }else{
            //$var为空则直接常规方式
            return $this->query($sql);
        }
    }

    //施放mysqli类
    public function Close(){

        $this->Mysqli->close();
    }

    //分页页码样式
    public function Navi($pageNow,$PageCount,$path,$other=''){

        $navi="";

        //页码显示
        //如果当前页不在首页 则显示首页和上一页
        if($pageNow>1){
            if($other == ''){
                $navi.="<a href='$path'>首页</a>&nbsp";   //首页
            }else{
                $navi.="<a href='$path?$other'>首页</a>&nbsp";   //首页
            }
            $Previous=$pageNow-1;
            $navi.="&nbsp<a href='$path?page=$Previous$other'>上一页</a>&nbsp;";
        }

        //计算多页翻页
        $pageWhole=5;
        $start=floor(($pageNow-1)/$pageWhole)*$pageWhole+1;

        if($pageNow>$pageWhole){

            $start1=$start-1;
            $navi.="&nbsp<a href='$path?page=$start1$other'>前".$pageWhole."页</a>&nbsp";
        }

        //循环得到页码
        for(;$start<floor(($pageNow-1)/$pageWhole)*$pageWhole+1+$pageWhole && $start<=$PageCount;$start++){

            if($pageNow != $start){
                $navi.="<a href='$path?page=$start$other'>$start</a>&nbsp";
            }else {
                $navi.="<span>$start</span>&nbsp";
            }
        }

        if($PageCount - $start > 0){

            $navi.="&nbsp<a href='$path?page=$start$other'>后".$pageWhole."页</a>&nbsp";
        }

        if($pageNow<$PageCount){  //判断是否为最后一页,不是最后一页显示下一页和末页

            //下一页代码
            $Next=$pageNow+1;
            $navi.="&nbsp<a href='$path?page=$Next$other'>下一页</a>";

            //末页代码
            $navi.="&nbsp<a href='$path?page=$PageCount$other'>末页</a>";
        }

        return $navi;
    }

    //讲值传递数组变成引用传递
    protected function refValues($arr){

        if (strnatcmp(phpversion(),'5.3') >= 0){ //Reference is required for PHP 5.3+

            $refs = array();

            foreach($arr as $key => $value){
                $refs[$key] = &$arr[$key];
            }

            return $refs;
        }
        return $arr;
    }


}

