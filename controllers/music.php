<?php

class music extends controller{
  private $list = array("life is a boat","再见二丁目");
  //输出播放列表
  public function index(){
    $this->data['succeed'] = true;
    $this->data['list'] = $this->list;//id=>name
  }

  public function play(){
    if(isset($_POST['id']) && is_numeric($_POST['id'])){
      $id = abs(intval($_POST['id']))%count($this->list);
      $song = $this->list[$id];
      //播放
      if($this->service->play_music($song)){
        $this->data['succeed'] = true;
        $this->data['weibo'] = "主人正在听《{$song}》，是不是很好听啊？ ^_^ @城_主 ";
      }else{
        $this->data['error_msg'] = $this->service->error;
      }
    }
  }

  public function stop(){
    if($this->service->stop_music()){
      $this->data['succeed'] = true;
      $this->data['weibo'] = "主人把音乐关了，要开始学习咯？ @城_主 ";
    }else{
      $this->data['error_msg'] = $this->service->error;
    }
  }
}