# Changes #

### Task 1 ###

* Added status DB column to Task table
* Added status in the Task grid UI
* New assignments table to track messages sent to the provider and responses (task_id / MessageSid)
* New Assignments controller
* New action in the UI to send sms messages for a given task. 
	- This basically creates a new assignment doing a POST request to the Assignment Controller
	- The action puts the task into "pending" status
	- Twilio sms request is mocked for the challenge. The controller returns a dummy uuid.  
* Added TwillioController to recieve SMS responses
	- POST /twillio with the MessageSid and Body from twilio when a provider replies the SMS
	- The action puts the task into "accepted" or "declined" status
* Functional tests testing the flow from creating a task to accepting the assignment
* Removed "tasks" option in the menu and provide the functionality in the main page

### Task 2 ###

* Show button requests the assignments for the given task and populates a new grid
	- GET /assignment by task_id
* Complete action from UI to complete a given task
* AngularJS tests


#### Missing/To improve ####

* Grid pagination
* Twilio integration for requesting sms
* Some actions require transactions since Tasks and Assigments tables are updated. Lack of knowledge how to do that in php. Another option would be decouple the staus from task entity however not quite familiar either with the php ORM.
* Due to lack of php & zend framework understanding the assignments are requested via Assigments controller passing task_id as parameter. The ideal rest request would be using the proper route /tasks/{id}/assignments