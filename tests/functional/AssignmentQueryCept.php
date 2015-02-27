<?php
$I = new TestGuy($scenario);
$I->wantTo('Show a task assigned to Jane');
$task = $I->haveTask(['assigned_name' => 'Jane Doe']);

$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET("/task/{$task->id}?format=json");
$I->seeResponseCodeIs(200);
$I->seeResponseContains('"assigned_name":"Jane Doe"');
$I->seeResponseContains('"status":"unassigned"');

$I->wantTo('Send message to Jane');
$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST('/assignment', array('task_id' => $task->id));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains("\"task_id\":{$task->id}");
$message_id = $I->grabDataFromJsonResponse('assignment.message_id');
$assignment_id = $I->grabDataFromJsonResponse('assignment.id');

$I->wantTo('show Jane\'s task assignments');
$I->sendGET("/assignment", array('task_id' => $task->id,'format' => 'json'));
$I->seeResponseCodeIs(200);
$I->seeResponseContains("\"task_id\":{$task->id}");
$I->seeResponseContains("\"message_id\":\"{$message_id}\"");
