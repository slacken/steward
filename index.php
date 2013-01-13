<?php

$default = array('controller'=>'welcome','action'=>'index');

error_reporting(E_ALL);
define("BASE",dirname(__FILE__).'/');

function model_loader($model){
  $path = BASE."models/{$model}.php";
  if (is_readable($path)) require $path;
}

spl_autoload_register('model_loader');

//然后
$controller = isset($_GET['controller']) ? $_GET['controller'] : $default['controller'];
$action = isset($_GET['action']) ? $_GET['action'] : $default['action'];

if(!is_readable(BASE."controllers/{$controller}.php"))$controller = $default['controller'];

class controller{
  protected $data;
  protected $service;
  public function __construct(){
    $this->service = new service();
    $this->data = array('succeed'=>false);
  }
  public function output($action){//这里可实现views，暂无必要
    if(isset($this->data['weibo'])){
      $return = $this->service->weibo('statuses/update',array('status'=>$this->data['weibo']),'post');
      unset($this->data['weibo']);
    }
    echo json_encode($this->data);
  }

  public function error_404(){
    $this->data['error_msg'] = 'Action not exists.';
  }
}

require BASE."controllers/{$controller}.php";

$exec = new $controller();

if(! $exec instanceof controller){
  exit("Class {$controller} is not a controller");
}

if (!method_exists($exec, $action)) {
  $action = "error_404";
}

$exec->$action();

$exec->output($action);