<?php

App::uses('AppModel','Model');

class Tweet extends AppModel{
    
    public $validate = array(
        //本文
        'username',
        'tweet_id',
        'message' => array('rule' => array('maxLength',140),
                'message' => '140文字で入力してください。'
        )
    );
    
    //最新ツイートを取得
    public function getRecentTweet($username = null){
        $opt = array('username' => $username);
        return $datas = $this->find('first',array(
            'conditions' => $opt,
            'order' => 'tweet_id DESC'
        ));
    }

    //つぶやきのURLを有効にする
    public function enableUrl($value){
        /*リンク*/
        $pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u';
        // 置換後の文字列
        $replacement = '<a href="\1">\1</a>';
        // 置換
        return preg_replace($pattern,$replacement,$value);
    }
    
    //ツイート数
    public function numberOfTweet($target_id = null){
        $opt = array('username'=>$target_id);
        return $datas = $this->find('count',array(
            'conditions' => $opt,
        ));
    }
    
    //ツイート検索オプション
    public function searchTweetOption($username = null){
            $opt = array("username" => $username);
            return array(
                'conditions' => $opt,
                'limit' => 10,
                'order' => 'tweet_id DESC'
            );
    }
    
    //新しいツイートを取得
    public function getNewTweet($username = null,$id = null){
        $opt = array('username' => $username,"tweet_id > $id");
        return $datas = $this->find('all',array(
            'conditions' => $opt,
            'order' => 'tweet_id DESC'
        ));
    }

    //過去のツイートを取得
    public function getPastTweet($username = null,$id = null){
        $opt = array('username' => $username,"tweet_id < $id");
        return $datas = $this->find('all',array(
            'conditions' => $opt,
            'limit' => 10,
            'order' => 'tweet_id DESC'
        ));
    }    
    
    //あるツイートが存在するか
    public function isExist($username = null,$id = null){
        $opt = array('username'=>$username,'tweet_id'=>$id);
        return $this->find('count',array(
            'conditions' => $opt,
        ));
    }
    
    //ツイート削除
    public function deleteTweet($username = null,$delete_id = null){
        $opt = array('username'=>$username,'tweet_id'=>$delete_id);
        return $this->deleteAll($opt,false);
    }

    //テストデータ作成用のinsert
    public function bulkInsert($data) {
        if(count($data) > 0) { 
            App::uses('Sanitize', 'Utility');
            $data = Sanitize::clean($data);
            $value_array = array();
            $tmp = $data;
            $first_data = array_shift($tmp);
            if(isset($first_data[$this->name])){
                $fields = array_keys($first_data[$this->name]);
                foreach ($data as $key => $v) {
                    $value_array[] = "('" . implode('\',\'', $v[$this->name]) . "')";
                }
            } else {
                $fields = array_keys($first_data);
                foreach ($data as $key => $v) {
                    $value_array[] = "('" . implode('\',\'', $v) . "')";
                }
            }
            $table = $this->tablePrefix.$this->useTable;
            $dup_update = [];
            foreach ($fields as $v) {
                $dup_update[] = $v." = VALUES(".$v.")";
            }
            $dup_update = implode(",",$dup_update);
            $sql = "INSERT INTO " . $table . " (" . implode(', ', $fields) . ") VALUES " . implode(',', $value_array)." ON DUPLICATE KEY UPDATE ".$dup_update;
            $this->query($sql);
            return true;
        }

        return false;
    }
}


