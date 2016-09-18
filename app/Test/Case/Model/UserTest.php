<?php
App::uses('User', 'Model');

class FriendTest extends CakeTestCase {
        public function setUp() {
        parent::setUp();
        $this->User = ClassRegistry::init('User');
    }

    
    public function testCheckExist() {
        $result = $this->User->checkExist("test1");
        $expected = 1;
        $this->assertEquals($expected, $result);
        debug($result);
    }
    
    public function testGetUserInfo() {
        $result = $this->User->getUserInfo("test1");
        debug($result);
    }
}