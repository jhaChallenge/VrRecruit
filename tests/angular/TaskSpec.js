describe('Task', function() {
  var _$controller,_$rootScope,_$httpBackend, _$state, _$timeout;
  var _scope, _taskFactory;

  beforeEach(module('taskConfirmationApp'));

  beforeEach(
      inject(function($injector) {
        _$httpBackend	= $injector.get('$httpBackend');
        _$controller 	= $injector.get('$controller');
        _$rootScope 	= $injector.get('$rootScope');
        _$state			= $injector.get('$state');
        _$timeout		= $injector.get('$timeout');
        _taskFactory	= $injector.get('Task');
        _assigFactory	= $injector.get('Assignment');
        _scope 			= _$rootScope.$new();
      }));

  function createController() {
	    return _$controller('TaskCtrl', {
	      $scope 	: _scope,
	      $state	: _$state, 
	      $timeout 	: _$timeout, 
	      Task 		: _taskFactory,
	      Assignment: _assigFactory
	    });
	};

  afterEach(function() {
     _$httpBackend.verifyNoOutstandingExpectation();
     _$httpBackend.verifyNoOutstandingRequest();
  });

  it('should load the tasks', function() {
  	_$httpBackend.whenGET('/taskConfirmationApp/templates/index.html').respond(200);
  	var taskController = createController();

  	_$httpBackend
         .expectGET('/task/?format=json')
         .respond(200, angular.toJson([{"id":1,"assigned_name":"John Doe","status":"declined"}]));

    _$timeout.flush();
 	_$httpBackend.flush();

    expect(_scope.tasks.length).toBe(1);
  });

  describe('when sending sms', function(){
	it('should create a new assignment', function() {
	  	_$httpBackend.whenGET('/taskConfirmationApp/templates/index.html').respond(200);
	  	_$httpBackend.whenGET('/taskConfirmationApp/templates/assignments.html').respond(200);
	  	_$httpBackend.whenGET('/task/?format=json').respond(200);
	  	var taskController = createController();

	  	_scope.send_sms(angular.toJson({"id":1,"assigned_name":"John Doe","status":"declined"}));

	  	_$httpBackend
        	.expectPOST('/assignment/?format=json')
         	.respond(200);

        _$httpBackend.flush();
  	});
  });

  describe('when completing a task', function(){
	it('should update the status of the task', function() {
	  	_$httpBackend.whenGET('/taskConfirmationApp/templates/index.html').respond(200);
	  	_$httpBackend.whenGET('/taskConfirmationApp/templates/assignments.html').respond(200);
	  	_$httpBackend.whenGET('/task/?format=json').respond(200);
	  	var taskController = createController();

	  	_scope.complete(angular.toJson({"id":1,"assigned_name":"John Doe","status":"pending"}));

	  	_$httpBackend
        	.expectPUT('/task/?format=json',{"status":"complete"})
         	.respond(200);

        _$httpBackend.flush();
  	});
  });
});
