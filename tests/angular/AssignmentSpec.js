describe('Assignment', function() {
  var _$controller,_$rootScope,_$httpBackend, _$state;
  var _scope, _taskFactory, _stateParams;

  beforeEach(module('taskConfirmationApp'));

  beforeEach(
      inject(function($injector) {
        _stateParams  = { task_id : 1 };
        _$httpBackend	= $injector.get('$httpBackend');
        _$controller 	= $injector.get('$controller');
        _$rootScope 	= $injector.get('$rootScope');
        _assigFactory	= $injector.get('Assignment');
        _scope 			= _$rootScope.$new();
      }));

  function createController() {
	    return _$controller('AssignmentCtrl', {
	      $scope 	: _scope,
        $stateParams : _stateParams,
	      Assignment: _assigFactory
	    });
	};

  afterEach(function() {
     _$httpBackend.verifyNoOutstandingExpectation();
     _$httpBackend.verifyNoOutstandingRequest();
  });

  it('should load the tasks', function() {
  	var taskController = createController();
    var assignments = [{"id":1},{"id":2}];

  	_$httpBackend
         .expectGET(['/assignment/?format=json&task_id=',_stateParams.task_id].join(''))
         .respond(200, [{"id":1},{"id":2}]);

 	  _$httpBackend.flush();

    expect(_scope.assignments.length).toBe(assignments.length);
  });
});
