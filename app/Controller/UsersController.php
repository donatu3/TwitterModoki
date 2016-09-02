<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

    //読み込むコンポーネントの指定
    public $components = array('Session', 'Auth');
    //public $helpers = array('Js' => array('Jquery'));
    //どのアクションが呼ばれてもはじめに実行される関数
    public function beforeFilter()
    {
        parent::beforeFilter();
        //未ログインでアクセスできるアクションを指定
        //これ以外のアクションへのアクセスはloginにリダイレクトされる規約になっている
        $this->Auth->allow('register', 'login','completion_of_registration');
    }

    public function index(){

    }
    
    //ユーザー登録
    public function register(){
        //$this->requestにPOSTされたデータが入っている
        //POSTメソッドかつユーザ追加が成功したら
        if($this->request->is('post') && $this->User->save($this->request->data)){
            $this->Auth->login();
            //自分のツイートは最初から表示されるように、自分を友達として登録する（フォロー数などには加えない）
            $id = $this->request->data['User']['username'];
            $this->Session->write('userid',$this->Auth->user('username'));
            $this->redirect(array('controller' => 'friends', 'action' => 'follow',$id,$id));
        }
        $this->set('valerror', $this->User->validationErrors);
    }

    //登録完了
    public function completion_of_registration(){
        //仕様書では登録後ログイン画面に遷移しているため、一旦ログアウトさせる
        $this->Session->destroy();
        $this->Auth->logout();
    }

    //ログイン
    public function login(){ 
        //バリデーションのルール変更（同一ユーザーかどうかの確認をしない）
        $this->User->validate['username'] = array(
            array('rule' => 'notEmpty',
                  'message' => 'ユーザIDは必須項目です。',
                  'last' => false
            ),
            array('rule' => array('between',4,20),
                  'message' => 'ユーザIDは、4文字以上20文字以内で入力してください。',
                  'last' => false
            ),
            array('rule' => '/^[a-zA-Z0-9_-]+$/',
                  'message' => 'ユーザIDは、半角英数字とハイフン(-)、アンダーバー(_)で入力してください。',
                  'last' => false
            )
        );
        $this->User->set( $this->request->data );
        if($this->request->is('post')) {
            if ($this->User->validates( array( 'fieldList' => array( 'username', 'password')))) {
                // バリデーションOKの場合の処理
                    if($this->Auth->login()){
                        //ログイン成功
                        $this->Session->write('userid',$this->Auth->user('username'));
                        return $this->redirect(array('controller' => 'tweets', 'action' => 'home',$this->Session->read('userid')));
                    }else{
                        //ログイン失敗
                        $loginerror = array('login' => array('ユーザ名、パスワードの組み合わせが違うようです。'));
                        $this->User->validationErrors = $this->User->validationErrors + $loginerror;
                    }
            }
        }
        //エラー内容をセット
        $this->set('valerror', $this->User->validationErrors);
    }

    //ログアウト
    public function logout(){
        $this->Session->destroy();
        $this->Auth->logout();
        $this->Session->setFlash('ログアウトしました');
        $this->redirect('login');
    }

}
