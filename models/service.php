<?php
//提供服务，向对应的对象发送消息(socket)，而不是自己做
class service{
  public $error = array();

  private $actuators = array(
    'music'=>array('address'=>'10.214.36.31','port'=>2013)
  );

  public function play_music($name){
    $file = str_replace(' ','_',$name).'.mp3';
    return $this->request('music','start',$file);
  }

  public function stop_music(){
    return $this->request('music','stop');
  }

  private function request($actuator,$command,$param = null){
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if(isset($this->actuators[$actuator]) && socket_connect($socket,$this->actuators[$actuator]['address'],$this->actuators[$actuator]['port'])){
      socket_write($socket, "{$command}\n");
      
      socket_write($socket, (is_null($param)?"":$param)."\n");
      
      return true;
    }else{
      $this->error = "cannot connect to the actuator.";
    }
    return false;
  }

  //微博API
  public function weibo($action,$key_values,$method = 'get'){

    $access_token = "2.00XvanVD0Oc3fm6279e7cfd64VbfeC";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $url = "https://api.weibo.com/2/{$action}.json?access_token={$access_token}";
    if($method == 'post'){
      curl_setopt($ch, CURLOPT_POST, true);
      $params = '';
      foreach ($key_values as $key=>$value){
        $params .= "&{$key}=".urlencode($value);
      }
      curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim($params,'&'));
    }else{
      foreach ($key_values as $key=>$value){
        $url .= "&{$key}=".urlencode($value);
      }
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);
    curl_close($ch);
    
    $info = json_decode($ret,true);
    
    if(empty($info) || isset($info['error'])){
      $this->error = $info;
      return false;
    }
    
    return $info;
  }
}