<?php
App::uses('AppController', 'Controller');

class FriendsController extends AppController {
    public $components = array('Session');
    public $uses = array('User','Friend','Tweet');
    
    //フォロー
    public function follow($userid = null,$followid = null){
        $message = "";
        $result = array('error' => false);
        if(empty($userid) && empty($followid)){
            //他のユーザーをフォローする場合は引数が違う
            $userid = $this->Session->read('userid');
            if(empty($_POST['follow_id'])){
                $followid = null;
            }else{
                $followid = $_POST['follow_id'];
            }
            //ビューを使用しない
            $this->autoRender = false;
        }
        //重複チェック
        $check = $this->Friend->checkDouble($userid,$followid);
        //存在チェック
        $exist = $this->User->checkExist($followid);
        if($userid == $this->Session->read('userid') && $check == 0 && $exist == 1 && $this->Friend->saveFollow($userid,$followid) ){
            if($userid == $followid){
                //登録時の処理なので、登録完了画面にリダイレクトさせる。
                $this->redirect(array('controller' => 'users', 'action' => 'completion_of_registration'));
            }
        }
        else{
            $message = "フォローに失敗しました。";
            if($check > 0){
                $result['error'] = true;
                $message = $message . "<br>・（重複エラー）";
            }
            if($exist == 0){
                $result['error'] = true;
                $message = $message . "<br>・（相手ユーザが存在しません）";
            }
        }
        if($this->autoRender == true){
            $this->Session->setFlash($message);
        }else{
            echo json_encode($result);
        }
    }
    
    //リムーブ
    public function remove(){
        //ビューを使用しない
        $result = array('error' => false);
        if($this->request->is('post')){
            $this->autoRender = false;
            $userid = $this->Session->read('userid');
            if($userid == $this->Session->read('userid') && $this->Friend->deleteFollow($userid,$_POST['remove_id'])){
                $result['error'] = false;
            }else{
                $result['error'] = true;
            }
            echo json_encode($result);
        }else{
            $this->Session->setFlash('不正なアクセスです。');
        }
        
    }
    
    //友達検索
    public function search(){
        if($this->request->is('post')){
            if($this->request->data['Friend']['search_id'] != null){
                //入力がされている場合
                $search_text = $this->request->data['Friend']['search_id'];
                $this->redirect(array('action' => 'search_result',$search_text));
            }else{
                //何も入力されてない場合、すべてのユーザーを検索する
                $search_text = "全てのユーザー";
                $this->redirect(array('action' => 'search_result',$search_text));
            }
        }
    }
    
    //友達検索結果
    public function search_result($search_text){
        if($this->request->is('post')){
            if($this->request->data['Friend']['search_id'] != null){
                //入力がされている場合
                $search_text = $this->request->data['Friend']['search_id'];
                $this->redirect(array('action' => 'search_result',$search_text));
            }else{
                //何も入力されてない場合、すべてのユーザーを検索する
                $search_text = "全てのユーザー";
                $this->redirect(array('action' => 'search_result',$search_text));
            }
        }
        
        $this->set('search_text',$search_text);
        $this->paginate = $this->Friend->searchFriendsOptions($this->Session->read('userid'),$search_text);
        $search_result = $this->paginate();
        
        //各々の最新ツイートを取得
        foreach($search_result as &$val){
            //フォローしているか検索
            $val['User']['follow'] = $this->Friend->isFollow($this->Session->read('userid'),$val['User']['username']);
            if($val['User']['private'] == 1 && $val['User']['follow'] == 0){
                //非公開かつフォローしていなければ
                $val['User']['tweet'] = "非公開ユーザーです";
                $val['User']['tweet_time'] = null;
            }else{
                //非公開でなければツイートを取得
                $datas = $this->Tweet->getRecentTweet($val['User']['username']);
                if(!empty($datas)){
                    //ツイートがある
                    $val['User']['tweet'] = $datas['Tweet']['message'];
                    $val['User']['tweet_time'] = $datas['Tweet']['created'];
                }else{
                    //ツイートがない
                    $val['User']['tweet'] = "まだツイートがありません";
                    $val['User']['tweet_time'] = null;
                }
            }
        }
        //値をセット
        $this->set('search_result',$search_result);        
    }
    
    //フォロー、フォロワー　一覧
    public function table($target_id=null,$menu=null){
        //引数がセットされていない場合はホームにリダイレクトさせる
        if(empty($target_id) || empty($menu)){
            $this->redirect(array('controller' => 'tweets', 'action' => 'home',$this->Session->read('userid')));
        }
        //ビューに引数を渡す
        $this->set('target_id',$target_id);
        $this->set('menu',$menu);
        //フォローorフォロワー検索　menuによって処理を分ける
        $this->paginate = $this->Friend->searchFollowOptions($menu,$target_id);
        $search_result = $this->paginate('Friend');
        
        foreach($search_result as &$val){    
            //自分が相手をフォローしているか
            if($menu == 'following'){
                $opt = $val['Friend']['followID'];
                $val['Friend']['follow'] = $this->Friend->isFollow($this->Session->read('userid'),$val['Friend']['followID']);
            }else{
                $opt = $val['Friend']['username'];
                $val['Friend']['follow'] = $this->Friend->isFollow($this->Session->read('userid'),$val['Friend']['username']);
            }
            //名前は何か
            $userdatas = $this->User->getUserInfo($opt);
            $val['Friend']['name'] =$userdatas['User']['name'];
            //最新ツイート、ツイート時間の取得
            if($userdatas['User']['private'] == 1 && $val['Friend']['follow'] == 0){
                //非公開　かつ　ログインユーザーがその人をフォローしていない　ならその旨を記述
                $val['Friend']['tweet'] = "非公開ユーザーです";
                $val['Friend']['tweet_time'] = null;
            }else{
                //非公開でないか、フォローしてればツイートを取得 $optはそのままで良い
                $datas = $this->Tweet->getRecentTweet($opt);
                if(!empty($datas)){
                    //ツイートがある
                    $val['Friend']['tweet'] = $datas['Tweet']['message'];
                    $val['Friend']['tweet_time'] = $datas['Tweet']['created'];
                }else{
                    //ツイートがない
                    $val['Friend']['tweet'] = "まだツイートがありません";
                    $val['Friend']['tweet_time'] = null;
                }
            }
        }
        //ビューに値を渡す
        $this->set('search_result',$search_result);
        //ユーザ情報（右側）のための情報取得
        //フォローしている数
        $this->set('user_info_following',$this->Friend->numberOfFollow($target_id));
        //フォローされてる数
        $this->set('user_info_followers',$this->Friend->numberOfFollower($target_id));
        //ツイート数
        $this->set('user_info_tweet',$this->Tweet->numberOfTweet($target_id));
    }
    
    //テストフォロー作成用
    /*
    public function addfriend(){
        $this->autoRender = false;
        for($num = 1; $num <= 100; $num++){
            $username = "test".$num;
            //1～100までの配列
            $ar_num = range(1,100);
            //自分を取り除く
            $split = array_splice($ar_num, $num-1, 1);
            shuffle($ar_num);
            for($fol = 0; $fol < 20; $fol++){
                if($fol == 0){
                    $followid = "test".$num; 
                }else{
                    $followid = "test".$ar_num[$fol]; 
                }
                $delay = $num - 1000;
                $created = date('Y-m-d H:i:s', strtotime("- 4 days $delay seconds"));
                $data = array('Friend' => array('username' => $username, 'followID' => $followid, 'created' => $created));
                $this->Friend->create(false);  
                if($this->Friend->save($data,$validate=false)){
                    echo $num.":成功<br>";
                }else{
                    echo $num.":<h1>失敗</h1><br>";
                }
            }    
        }
    }
    */
}
