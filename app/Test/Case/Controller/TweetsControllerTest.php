<?php
class TweetsControllerTest extends ControllerTestCase {

    public function testtest() {
        $result = $this->testAction('/tweets/test');
        $this->assertTextContains('okok', $this->vars);
        debug($this->vars);
    }

}