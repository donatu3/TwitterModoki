<?php
App::uses('AppController', 'Controller');

class TweetsController extends AppController {
    public $components = array('Session');
    public $uses = array('Tweet', 'Friend');
    
    //ホーム画面
    public function home($home_id=null,$only=null,$page=null){
        if(empty($home_id)){
            $home_id = $this->Session->read('userid');
        }
        if(empty($only)){
            $only = null;
        }
        $this->set('home_id',$home_id);
        $this->set('only',$only);
        $this->set('userid',$this->Session->read('userid'));
        $this->set('valerror', $this->Tweet->validationErrors);
        if($home_id == $this->Session->read('userid') && $only == null){
            //フォローしている人の検索（普通のホーム　フォローしている人のつぶやきも表示）
            $datas = $this->Friend->searchFollows($this->Session->read('userid'));
            foreach($datas as $key1 => $val1){
                foreach($val1 as $key2 => $val2){
                    foreach($val2 as $key3 => $val3){
                        if($key3 == 'followID'){
                            $follow_username[] = $val3;
                        }
                    }
                }
            }
            //ツイートの検索
            $this->paginate = $this->Tweet->searchTweetOption($follow_username);
            //最新ツイートの取得
            $datas = $this->Tweet->getRecentTweet($this->Session->read('userid'));
            if(!empty($datas)){
                //ツイートがある
                $recent['tweet'] = $datas['Tweet']['message'];
                $recent['tweet_time'] = $datas['Tweet']['created'];
            }else{
                //ツイートがない
                $recent['tweet'] = "まだツイートがありません";
                $recent['tweet_time'] = null;
            }
            $this->set('recent',$recent);
        }else{
            //ツイートの検索（対象ユーザーのみ）
            $this->paginate = $this->Tweet->searchTweetOption($home_id);
        }

        //値をセット
        $this->set('search_result',$this->paginate()); 
        
        //ユーザ情報（右側）のための情報取得
        //フォローしている数
        $this->set('user_info_following',$this->Friend->numberOfFollow($home_id));
        //フォローされてる数
        $this->set('user_info_followers',$this->Friend->numberOfFollower($home_id));
        //ツイート数
        $this->set('user_info_tweet',$this->Tweet->numberOfTweet($home_id));
    }
    
    //ツイートの削除用
    public function delete(){
        //ビューを使用しない
        $this->autoRender = false;
        $tweetid = $_POST['tweet_id'];
        $userid = $this->Session->read('userid') ;
        $delete_id = $tweetid;
        $result = array('zero' => false,'delete' => false);
        //deleteAllは件数が0であってもtrueを返してしまう　→　findで検索をかけて0であった場合はその旨を返せるようにした。
        $datas = $this->Tweet->isExist($userid,$delete_id);
        if($datas == 0){
            //件数が0である（通常ここには入らない）
            $result['zero'] = true;
        }
        if($this->Tweet->deleteTweet($userid,$delete_id)){
            //削除成功
            $result['delete'] = true;
        }
        echo json_encode($result);
    }
    
    //ツイートを保存する
    public function post(){
        if($this->request->is('post')){
        $this->autoRender = false;
        $result = array('error' => "true",'message' => "");
        $username = $_POST['username'];
        $message = $_POST['message'];
        if($message=='' || mb_strlen($message) > 140){
            $result['error'] = true;
        }else{
            $result['error'] = false;
            $data['Tweet']['username'] = $username;
            $data['Tweet']['message'] = $message;
        }
        if($result['error'] == false && $this->Tweet->save($data)){
            $result['message']='投稿に成功しました';
        }else{
            $result['message']='投稿に失敗しました。文字数を確認してください。';
        }
        echo json_encode($result);
        }else{
            $this->Session->setFlash('不正なアクセスです。');
        }
    }
    
    //新しいツイート
    public function newtweet(){
        if($this->request->is('post')){
            $this->layout = "";
            $username = $_POST['username'];
            if(!empty($_POST['id']) || $_POST['id'] == 0){
                $id = $_POST['id'];
                $datas = $this->Friend->searchFollows($username);
                foreach($datas as $key1 => $val1){
                    foreach($val1 as $key2 => $val2){
                        foreach($val2 as $key3 => $val3){
                            if($key3 == 'followID'){
                                $follow_username[] = $val3;
                            }
                        }
                    }
                }
                $datas = $this->Tweet->getNewTweet($follow_username,$id);
                $this->set('userid',$username);
                $this->set('search_result',$datas);
                $this->set('Tweet',$this);
                $this->set('error',false);
            }else{
                $this->set('error',true);
            }
        }else{
            $this->Session->setFlash('不正なアクセスです。');
        }
    }
    
    //古いツイート
    public function pasttweet(){
        if($this->request->is('post')){
            $this->layout = "";
            $username = $_POST['username'];
            if(!empty($_POST['id']) || $_POST['id'] == 0){
                $id = $_POST['id'];
                $datas = $this->Friend->searchFollows($username);
                foreach($datas as $key1 => $val1){
                    foreach($val1 as $key2 => $val2){
                        foreach($val2 as $key3 => $val3){
                            if($key3 == 'followID'){
                                $follow_username[] = $val3;
                            }
                        }
                    }
                }
                $datas = $this->Tweet->getPastTweet($follow_username,$id);
                $this->set('userid',$username);
                $this->set('search_result',$datas);
                $this->set('Tweet',$this);
                $this->set('error',false);
            }else{
                $this->set('error',true);
            }
        }else{
            $this->Session->setFlash('不正なアクセスです。');
        }
    }
    
    //最新のツイート
    public function recenttweet(){
        if($this->request->is('post')){
            $this->layout = "";
            $username = $_POST['username'];
            //最新ツイートの取得
            $datas = $this->Tweet->getRecentTweet($username);
            if(!empty($datas)){
                //ツイートがある
                $recent['tweet'] = $datas['Tweet']['message'];
                $recent['tweet_time'] = $datas['Tweet']['created'];
            }else{
                //ツイートがない
                $recent['tweet'] = "まだツイートがありません";
                $recent['tweet_time'] = null;
            }
            $this->set('recent',$recent);
        }else{
            $this->Session->setFlash('不正なアクセスです。');
        }
    }

    //テストツイート作成用
    /*
    public function addtweet(){
        ini_set("max_execution_time",180);
        $this->autoRender = false;
        $count = 1;
        $data = array();
        for($i = 9000; $i < 10000; $i++){
            for($num = 1; $num <= 100; $num++){
                $username = "test".$num;
                $message = "ついーと".$i;
                $data += array($count => array('username' => $username, 'message' => $message));
                $count = $count + 1;
            }
        }
        if($this->Tweet->bulkInsert($data)){
            echo "成功<br>";
        }else{
            echo "失敗<br>";
        }    
    }
    */
}
