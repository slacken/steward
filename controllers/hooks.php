<?php
//实现钩子动作
class hooks extends controller{
  public $time;//钩子方法与时间是有关系的

  private $db;

  public function __construct(){
    parent::__construct();
    $this->time = localtime();//数组，前3元素分别为秒、分、小时
    if(class_exists('SQLite3')){
      $this->db = new SQLite3('sessions.db',SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
      //数据库生成
      $create_table_sql = "CREATE TABLE IF NOT EXISTS `sessions` (`id` INTEGER PRIMARY KEY ASC,`device_id` STRING,`timestamp` INTEGER,`active` INTEGER);"
      $this->db->exec($create_table_sql);
      $this->db->exec("insert into sessions(device_id,timestamp,active) values ('sajisjdisj',10000,1)");
      
    }else{
      $this->data['error_msg'] = "cannot connect to database";
    }

  }
  //连接成功,返回一个连接标记，用以后面的操作（暂无）
  public function signin(){
    
  }
  //
  public function query(){

  }

  public function signout(){

  }
}