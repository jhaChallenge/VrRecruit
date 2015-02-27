<?php

use Vreasy\Models\Task;
use Vreasy\Models\Assignment;
use Vreasy\Utils\StatusMapper;
use Vreasy\Utils\Status;

class Vreasy_AssignmentController extends Vreasy_Rest_Controller
{
	protected $assignment, $assignments;

	public function preDispatch()
    {
    	parent::preDispatch();
        $req         = $this->getRequest();
        $action      = $req->getActionName();
    	$contentType = $req->getHeader('Content-Type');
        $rawBody     = $req->getRawBody();
        if ($rawBody) {
            if (stristr($contentType, 'application/json')) {
                $req->setParams(['assignment' => Zend_Json::decode($rawBody)]);
            }
        }
        if($req->getParam('format') == 'json') {
            switch ($action) {
                case 'index':
                    //ideally /tasks/{id}/assignments
                    //Not quite familiar how to do it in php
                    if ($req->getParam('task_id')){
                        $this->assignments = 
                            Assignment::where(
                                ['task_id' => $req->getParam('task_id')]);
                    }
                    else{
                        $this->assignments = Assignment::where([]);
                    }
                    
                    break;
                case 'new':
                    $this->assignment = new Assignment();
                    break;
                case 'create':
                    $this->assignment = Assignment::instanceWith(
                        $req->getParam('assignment'));
                    break;
                case 'show':
                case 'update':
                case 'destroy':
                    if ($req->getParam('id')){
                        $this->assignment = 
                            Assignment::findOrInit($req->getParam('id'));
                    }
                    break;
            }
        }
    }

    public function indexAction()
    {
        $this->view->assignments = $this->assignments;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->assignments]);
    }

    public function showAction()
    {
        $this->view->assignment = $this->assignment;
        $this->_helper->conditionalGet()->sendFreshWhen(
            ['etag' => [$this->assignment]]
        );
    }

    public function createAction()
    {
        $task = Task::findOrInit($this->assignment->task_id);
        $number = $task->assigned_phone;

        //Needs to be done outside HTTP call. Maybe using queues
        /*$client = new Services_Twilio(
            $_ENV['TWILIO_ACCOUNT_SID'], 
            $_ENV['TWILIO_AUTH_TOKEN']
        );

        $message = $client->account->messages->sendMessage(
            $_ENV['TWILIO_NUMBER'], // A Twilio number in your account
            $number,
            "Put some generic text for assigning a task"
        );

        $message_id = $message->sid;
        */

        $message_id = uniqid();
        $this->assignment->message_id = $message_id;

        //Needs transaction here since we will update Task and Assignment table
        //Probably a better approach would be decouple status from Task
        $task->status = Status::PENDING;
        if (!$task->save()){
            $this->view->errors = $task->errors();
            $this->getResponse()->setHttpResponseCode(422);
            return;
        }
        
        if ($this->assignment->isValid() && $this->assignment->save()) {
            $this->view->assignment = $this->assignment;
        } else {
            $this->view->errors = $this->assignment->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

	public function updateAction()
    {
        Assignment::hydrate($this->assignment, $this->_getParam('assignment'));

        if ($this->assignment->isValid() && $this->assignment->save()) {
            $this->view->assignment = $this->assignment;
        } else {
            $this->view->errors = $this->assignment->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    

    public function destroyAction()
    {}
}


