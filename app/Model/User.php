<?php

App::uses('AppModel','Model');

class User extends AppModel{
    
    public $validate = array(
        //ユーザの名前
        'name' => array(
            array('rule' => 'notEmpty',
                  'message' => '名前は必須項目です。',
                  'last' => false
            ),
            array('rule' => array('between',4,20),
                  'message' => '名前は、4文字以上20文字以内で入力してください。',
                  'last' => false
            ),
            //全角英数字、ひらがな、カタカナ、漢字、記号、半角英数字、-、_のみ許可（全角全部と半角英数字と-と_のみのつもり）
            array('rule' => '/^[Ａ-Ｚ０-９ぁ-んァ-ン一-龥、-◯a-zA-Z0-9_-]+$/',
                  'message' => '名前は、全角文字と半角英数字、ハイフン(-)、アンダーバー(_)で入力してください。'
            )
        ),
        //ユーザのID
        'username' => array(
            array('rule' => 'notEmpty',
                  'message' => 'ユーザIDは必須項目です。',
                  'last' => false
            ),
            array('rule' => 'isUnique',
                  'message' => '入力したユーザIDは既に存在しています。',
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
        ),
        //ユーザのパスワード
        'password' => array(
            array('rule' => 'notEmpty',
                  'message' => 'パスワードは必須項目です。',
                  'last' => false
            ),
            array('rule' => array('between',4,8),
                  'message' => 'パスワードは、4文字以上8文字以内で入力してください。',
                  'last' => false
            ),
            array('rule' => '/^[a-zA-Z0-9]+$/',
                  'message' => 'パスワードは、半角英数字とハイフン(-)、アンダーバー(_)で入力してください。',
                  'last' => false
            )
        ),
        //確認用のパスワード　データベースに登録はしない　＊同じかどうかの判断を追加する＊
        'password_confirmation' => array(
            array('rule' => 'notEmpty',
                  'message' => 'パスワード（確認）は必須項目です。',
                  'last' => false
            ),
            array('rule' => array('between',4,8),
                  'message' => 'パスワード（確認）は、4文字以上8文字以内で入力してください。',
                  'last' => false
            ),
            array('rule' => '/^[a-zA-Z0-9]+$/',
                  'message' => 'パスワード（確認）は、半角英数字とハイフン(-)、アンダーバー(_)で入力してください。',
                  'last' => false
            ),
            array('rule' => 'checkPassword',
				'message' => 'パスワードとパスワード（確認）が一致しません。',
                  'last' => false
            )  
        ),
        //ユーザのメールアドレス
        'mail' => array(
            array('rule' => array('maxLength',100),
                  'message' => 'メールアドレスは、100文字以内で入力してください。',
                  'last' => false
            ),
            array('rule' => 'email',
                  'message' => 'RFCに準拠したメールアドレスを入力してください。',
                  'last' => false
            )
        ),
        'private'
    );
    
    //パスワードをハッシュ化する関数
    public function beforeSave($options = array()) {
        $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        return true;
    }
    
    //パスワードを確認するための関数
    public function checkPassword(){
		if($this->data[$this->name]['password'] == $this->data[$this->name]['password_confirmation']){
			return true;
		}
		return false;
	}
    //存在チェック
    public function checkExist($followid = null) {
        $conditions = array('username'=>$followid);
        $exist = $this->find('count',array(
            'conditions' => $conditions,
        ));
        return $exist;
    }
    //ユーザ情報取得
    public function getUserInfo($username = null){
        $opt = array('username'=>$username);
        return $datas = $this->find('first',array(
            'conditions' => $opt
        ));
    }

}


