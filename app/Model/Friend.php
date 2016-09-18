<?php

App::uses('AppModel','Model');

class Friend extends AppModel{

    
    //重複チェック
    public function checkDouble($userid = null,$followid = null) {
        $conditions = array('username'=>$userid,'followID'=>$followid);
        $check = $this->find('count',array(
            'conditions' => $conditions,
        ));
        return $check;
    }
    //フォローする
    public function saveFollow($userid = null,$followid = null){
        $data = array('Friend' => array('username' => $userid, 'followID' => $followid));
        return $this->save($data);
    }
    //フォローを解除する
    public function deleteFollow($userid = null,$removeid = null){
        if(empty($removeid)){
            return false;
        }
        $conditions = array('username'=>$userid,'followID'=>$removeid);
        return $this->deleteAll($conditions,false);
    }
    //友達検索オプション
    public function searchFriendsOptions($username = null,$search_text = null){
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
                "username" => $username 
            )
        );
                
        //検索条件を返す
        return array(
                'conditions' => $opt,
                'limit' => 10,
                'order' => 'user_id DESC'
        );
    }
    //フォローしてるかチェック
    public function isFollow($username = null,$followid = null){
        $opt = array('AND'=>array('username'=>$username,'followID' => $followid));
        $datas = $this->find('first',array(
                'conditions' => $opt,
            ));
        if(!empty($datas)){
            //フォローしている
            return 1;
        }else{
            //フォローしていない
            return 0;
        }
    }
    //フォロー、フォロワー検索オプション
    public function searchFollowOptions($menu = null,$target_id = null){
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
        return array(
            'Friend' => array(
                'limit' => 10,
                'order' => 'friend_id DESC',
                'conditions' => $opt
            )
        );
    }
    //フォローしている数
    public function numberOfFollow($target_id = null){
        $opt = array('username'=>$target_id,'NOT'=>array('followID'=>$target_id));
        return $datas = $this->find('count',array(
            'conditions' => $opt,
        ));
    }
    //フォローされてる数
    public function numberOfFollower($target_id = null){
        $opt = array('followID'=>$target_id,'NOT'=>array('username'=>$target_id));
        return $datas = $this->find('count',array(
            'conditions' => $opt,
        ));
    }
    //フォローしている人全てを検索する
    public function searchFollows($username = null){
        $opt = array('username'=>$username);
        return $datas = $this->find('all',array(
            'conditions' => $opt,
        ));
    }
}


