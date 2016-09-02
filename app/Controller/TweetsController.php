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
        $this->request->data['Tweet']['username'] = $this->Session->read('userid');
        //ツイートid（削除などに使用）はデータベースが自動でインクリメントする
        //入力がされていて、文字数などが正しければツイートを保存する
        if($this->request->is('post') && $this->Tweet->save($this->request->data)){
            $this->redirect(array('controller' => 'tweets', 'action' => 'home',$this->Session->read('userid')));
        }
        $this->set('valerror', $this->Tweet->validationErrors);
        
        if($home_id == $this->Session->read('userid') && $only == null){
            //フォローしている人の検索（普通のホーム　フォローしている人のつぶやきも表示）
            //$this->Session->write('aaa','aaaa');
            $opt = array('username'=>$this->Session->read('userid'));
            $datas = $this->Friend->find('all',array(
                'conditions' => $opt,
            ));
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
            $opt = array("username" => $follow_username);
            $this->paginate = array(
                    'conditions' => $opt,
                    'limit' => 10,
                    'order' => 'created DESC'
            );
            //最新ツイートの取得
            $opt = array('username' => $this->Session->read('userid'));
            $datas = $this->Tweet->find('first',array(
                'conditions' => $opt,
                'order' => 'created DESC'
            ));
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
            $opt = array("username" => $home_id);
            $this->paginate = array(
                    'conditions' => $opt,
                    'limit' => 10,
                    'order' => 'created DESC'
            );
        }

        //値をセット
        $this->set('search_result',$this->paginate()); 
        
        //ユーザ情報（右側）のための情報取得
        //フォローしている数
        $opt = array('username'=>$home_id,'NOT'=>array('followID'=>$home_id));
        $datas = $this->Friend->find('count',array(
            'conditions' => $opt,
        ));
        $this->set('user_info_following',$datas);
        //フォローされてる数
        $opt = array('followID'=>$home_id,'NOT'=>array('username'=>$home_id));
        $datas = $this->Friend->find('count',array(
            'conditions' => $opt,
        ));
        $this->set('user_info_followers',$datas);
        //ツイート数
        $opt = array('username'=>$home_id);
        $datas = $this->Tweet->find('count',array(
            'conditions' => $opt,
        ));
        $this->set('user_info_tweet',$datas);
    }
    
    //ツイートの削除用
    public function delete(){
        //ビューを使用しない
        $this->autoRender = false;
        $tweetid = $_POST['tweet_id'];
        $userid = $this->Session->read('userid') ;
        $delete_id = $tweetid;
        $result = array('zero' => false,'delete' => false);
        $conditions = array('username'=>$userid,'tweet_id'=>$delete_id);
        //deleteAllは件数が0であってもtrueを返してしまう　→　findで検索をかけて0であった場合はその旨を返せるようにした。
        $datas = $this->Tweet->find('count',array(
            'conditions' => $conditions,
        ));
        if($datas == 0){
            //件数が0である（通常ここには入らない）
            $result['zero'] = true;
        }
        if($this->Tweet->deleteAll($conditions,false)){
            //削除成功
            $result['delete'] = true;
        }
        echo json_encode($result);
    }
}
