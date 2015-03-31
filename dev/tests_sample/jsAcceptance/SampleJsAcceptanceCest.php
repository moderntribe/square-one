<?php
use \JsAcceptanceTester;

class SampleJsAcceptanceCest
{
    public function _before(JsAcceptanceTester $I)
    {
    }

    public function _after(JsAcceptanceTester $I)
    {
    }

    /**
     * @test
     * it should fail
     */
    public function it_should_fail(\JSAcceptanceTester $I) {
        $I->wantTo('see a JavaScript alert');
        $I->amOnPage('/');
        $I->see('Alert text');
    }
}