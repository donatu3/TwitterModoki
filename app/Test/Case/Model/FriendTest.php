<?php
App::uses('Friend', 'Model');

class FriendTest extends CakeTestCase {
        public function setUp() {
        parent::setUp();
        $this->Friend = ClassRegistry::init('Friend');
    }

    /*
    public function testCheckDouble() {
        $result = $this->Friend->checkDouble("12","13");
        $expected = 0;
        debug($result);
        $this->assertEquals($expected, $result);
    }
    public function testSaveFollow() {
        $result = $this->Friend->saveFollow("test134","test145");
        $expected = true;
        debug($result);
    }
    
    public function testDeleteFollow(){
        $result = $this->Friend->deleteFollow("test134","test145");
        debug($result);
        $expected = true;
        $this->assertEquals($expected, $result);
    }
    */
    public function testSearchFriendsOptions(){
        $result = $this->Friend->searchFriendsOptions("test1","test");
        debug($result);
    }
    public function testIsFollow(){
        $result = $this->Friend->isFollow("test1","test2");
        debug($result);
        $expected = 1;
        $this->assertEquals($expected, $result);
    }
    public function testSearchFollowOptions(){
        $result = $this->Friend->searchFollowOptions("following","test2");
        debug($result);
    }
    public function testNumberOfFollow(){
        $result = $this->Friend->numberOfFollow("test1");
        debug($result);
    }
    public function testNumberOfFollower(){
        $result = $this->Friend->numberOfFollower("test2");
        debug($result);
    }
    public function testSearchFollows(){
        $result = $this->Friend->searchFollows("test1");
        debug($result);
    }

}