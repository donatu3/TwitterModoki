<?php
App::uses('Tweet', 'Model');

class TweetTest extends CakeTestCase {
        public function setUp() {
        parent::setUp();
        $this->Tweet = ClassRegistry::init('Tweet');
    }

    public function testGetRecentTweet() {
        $result = $this->Tweet->getRecentTweet("test1");
        debug($result);
    }
    
    public function testEnableUrl(){
        $result = $this->Tweet->enableUrl("testhttps://www.google.co.jp/");
        debug($result);
    }
    
    public function testNumberOfTweet(){
        $result = $this->Tweet->numberOfTweet("test1");
        debug($result);
    }

    public function testSearchTweetOption(){
        $result = $this->Tweet->searchTweetOption("test1");
        debug($result);
    }
    
    //新しいツイートを取得
    public function testGetNewTweet(){
        $result = $this->Tweet->getNewTweet("test1",3);
        debug($result);
    }

    //過去のツイートを取得
    public function testGetPastTweet(){
        $result = $this->Tweet->getPastTweet("test1",3);
        debug($result);
    }    
    
    //あるツイートが存在するか
    public function testIsExist(){
        $result = $this->Tweet->isExist("test1",3);
        debug($result);
    }

}