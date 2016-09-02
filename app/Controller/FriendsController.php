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
        $conditions = array('username'=>$userid,'followID'=>$followid);
        $check = $this->Friend->find('count',array(
            'conditions' => $conditions,
        ));
        //存在チェック
        $conditions = array('username'=>$followid);
        $exist = $this->User->find('count',array(
            'conditions' => $conditions,
        ));    
        $data = array('Friend' => array('username' => $userid, 'followID' => $followid));
        if($userid == $this->Session->read('userid') && $check == 0 && $exist == 1 && $this->Friend->save($data) ){
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
            if(empty($_POST['remove_id'])){
                $removeid = null;
            }else{
                $removeid = $_POST['remove_id'];
            }
            $conditions = array('username'=>$userid,'followID'=>$removeid);
            if($userid == $this->Session->read('userid') && $this->Friend->deleteAll($conditions,false)){
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
        //何も入力されていない場合の処理
        if($search_text == "全てのユーザー"){
            $search_text = "";
        }
        //（条件設定）ユーザーIDと名前に対してlike検索、自分は含めない
        $opt = array(
            "OR" => array(
                "username like" => '%'.$search_text.'%',
                "name like" => '%'.$search_text.'%'
            ),
            "NOT" => array(
                "username" => $this->Session->read('userid') 
            )
        );
                
        //idや名前を検索
        $this->paginate = array(
                'conditions' => $opt,
                'limit' => 10,
                'order' => 'created DESC'
        );
        
        //値を代入
        $search_result = $this->paginate();
        //各々の最新ツイートを取得
        foreach($search_result as &$val){
            //フォローしているか検索
            $opt = array('AND'=>array('username'=>$this->Session->read('userid'),'followID' => $val['User']['username']));
            $datas = $this->Friend->find('first',array(
                'conditions' => $opt,
            ));
            if(!empty($datas)){
                //フォローしている
                $val['User']['follow'] = 1;
            }else{
                //フォローしていない
                $val['User']['follow'] = 0;
            }
            
            if($val['User']['private'] == 1 && $val['User']['follow'] == 0){
                //非公開かつフォローしていなければ
                $val['User']['tweet'] = "非公開ユーザーです";
                $val['User']['tweet_time'] = null;
            }else{
                $opt = array('username' => $val['User']['username']);
                $datas = $this->Tweet->find('first',array(
                    'conditions' => $opt,
                    'order' => 'created DESC'
                ));
                //非公開でなければツイートを取得
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
        if(empty($target_id)){
            $this->redirect(array('controller' => 'tweets', 'action' => 'home',$this->Session->read('userid')));
        }
        if(empty($menu)){
            $this->redirect(array('controller' => 'tweets', 'action' => 'home',$this->Session->read('userid')));
        }
        //ビューに引数を渡す
        $this->set('target_id',$target_id);
        $this->set('menu',$menu);
        //menuによって処理を分ける
        if($menu == 'following'){
            //フォロー者一覧の場合
            $opt = array(
                    "username" => $target_id,
                    "NOT" => array(
                        "followID" => $target_id 
                    )
            );
        }else if($menu == 'followers'){
            //被フォロー者（フォロワー）一覧の場合
            $opt = array(
                    "followID" => $target_id,
                    "NOT" => array(
                        "username" => $target_id 
                    )
            );
        }else{
            //それ以外はホームにリダイレクトさせる
            $this->redirect(array('controller' => 'tweets', 'action' => 'home',$this->Session->read('userid')));
        }
          
        //フォロー、フォロワーの検索 コントローラのはじめで、public $uses = array('User','Friend','Tweet');
        //のような宣言をした場合、一番左以外のモデルを利用する場合はモデル名を引数に指定する
        $this->paginate = array(
            'Friend' => array(
                'limit' => 10,
                'order' => 'created DESC',
                'conditions' => $opt
            )
        );
        //値を代入しておく
        $search_result = $this->paginate('Friend');
        
        foreach($search_result as &$val){

            //自分がフォローしているか検索
            if($menu == 'following'){
                $opt = array('AND'=>array('username'=>$this->Session->read('userid'),'followID' => $val['Friend']['followID']));
            }else{
                $opt = array('AND'=>array('username'=>$this->Session->read('userid'),'followID' => $val['Friend']['username']));
            }
            $datas = $this->Friend->find('first',array(
                'conditions' => $opt,
            ));
            if(!empty($datas)){
                //フォローしている
                $val['Friend']['follow'] = 1;
            }else{
                //フォローしていない
                $val['Friend']['follow'] = 0;
            }
            
            //名前の取得（非公開かどうかの判定も）
            if($menu == 'following'){
                $opt = array('username'=>$val['Friend']['followID']);
            }else{
                $opt = array('username'=>$val['Friend']['username']);
            }
            $datas = $this->User->find('first',array(
                'conditions' => $opt
            ));
            $val['Friend']['name'] = $datas['User']['name'];
            //最新ツイート、ツイート時間の取得
            if($datas['User']['private'] == 1 && $val['Friend']['follow'] == 0){
                //非公開　かつ　ログインユーザーがその人をフォローしていない　ならその旨を記述
                $val['Friend']['tweet'] = "非公開ユーザーです";
                $val['Friend']['tweet_time'] = null;
            }else{
                //非公開でないか、フォローしてればツイートを取得 条件は同じ
                $datas = $this->Tweet->find('first',array(
                    'conditions' => $opt,
                    'order' => 'created DESC'
                ));
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
        $opt = array('username'=>$target_id,'NOT'=>array('followID'=>$target_id));
        $datas = $this->Friend->find('count',array(
            'conditions' => $opt,
        ));
        $this->set('user_info_following',$datas);
        //フォローされてる数
        $opt = array('followID'=>$target_id,'NOT'=>array('username'=>$target_id));
        $datas = $this->Friend->find('count',array(
            'conditions' => $opt,
        ));
        $this->set('user_info_followers',$datas);
        //ツイート数
        $opt = array('username'=>$target_id);
        $datas = $this->Tweet->find('count',array(
            'conditions' => $opt,
        ));
        $this->set('user_info_tweet',$datas);
        
    }
}
