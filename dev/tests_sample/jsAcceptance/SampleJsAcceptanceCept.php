<?php 
$I = new JsAcceptanceTester($scenario);
$I->wantTo('see a non existing JavaScript alert');
$I->amOnPage('/');
$I->see('Text in an alert');
