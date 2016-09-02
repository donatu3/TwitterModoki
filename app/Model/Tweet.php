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
    


    
}


