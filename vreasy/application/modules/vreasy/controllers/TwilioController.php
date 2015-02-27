<?php

use Vreasy\Models\Task;
use Vreasy\Models\Assignment;
use Vreasy\Utils\StatusMapper;

class Vreasy_TwilioController extends Vreasy_Rest_Controller
{
    protected $message_id;

	public function preDispatch()
    {
        parent::preDispatch();

        $req = $this->getRequest();
        $action = $req->getActionName();
        $contentType = $req->getHeader('Content-Type');
        $rawBody     = $req->getRawBody();
        if ($rawBody) {
            if (stristr($contentType, 'application/json')) {
                $req->setParams(['response' => Zend_Json::decode($rawBody)]);
            }
        }

        $this->message_id = trim($req->getParam('MessageSid'));

        if( !in_array($action, ['create',]) && !$this->message_id) {
            throw new Zend_Controller_Action_Exception('Resource not found', 404);
        }
     }

	//Twilio's callback configured for the account when the sms is replied via POST
    public function createAction()
    {
    	$sms_body 	= trim($this->_getParam('Body'));

    	if (empty($this->message_id)) {
            $this->view->errors = ['create' => 'Unable to process MessageSid'];
            $this->getResponse()->setHttpResponseCode(422);
            return;
    	}

    	$assignment = Assignment::findByMessageId($this->message_id);
    	$assignment->response = $sms_body;

        //Needs transaction here since we will update Task and Assignment table
        //Probably the better approach would be decouple status from Task
        $task = Task::findOrInit($assignment->task_id);
        $task->status = StatusMapper::Map($assignment->response);

        if (!$task->save()){
            $this->view->errors = ['create' => 'Unable to process task'];
            $this->getResponse()->setHttpResponseCode(422);
            return;
        }

        if (!$assignment->save()) {
            $this->view->errors = ['create' => 'Unable to process assignment'];
            $this->getResponse()->setHttpResponseCode(422);
            return;
        }

        $this->view->result = "Ok";
    }
}