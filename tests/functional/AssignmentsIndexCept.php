<?php
$I = new TestGuy($scenario);
$I->wantTo('List all the assignments');

$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET('/assignment?format=json');
$I->seeResponseCodeIs(200);