
angular.module('taskConfirmationApp',  ['ui.router', 'ngResource'])
.config(function($stateProvider, $urlRouterProvider, $locationProvider) {
    // Use hashtags in URL
    $locationProvider.html5Mode(false);

    $urlRouterProvider.when("", "/tasks");
    $urlRouterProvider.when("/", "/tasks");
    $urlRouterProvider.otherwise("/tasks");

    $stateProvider
        .state('tasks', {
          url: "/tasks",
          templateUrl: "/taskConfirmationApp/templates/index.html",
          controller: 'TaskCtrl'
        })
        .state('tasks.assignments', {
          url: '/:task_id',
          parent: 'tasks',
          templateUrl: "/taskConfirmationApp/templates/assignments.html",
          controller: 'AssignmentCtrl'
        });
})
.factory('Task', function($resource) {
    return $resource('/task/:id?format=json',
        {id:'@id'},
        {
            'get': {method:'GET'},
            'save': {method: 'PUT'},
            'create': {method: 'POST'},
            'query':  {method:'GET', isArray:true},
            'remove': {method:'DELETE'},
            'delete': {method:'DELETE'}
        }
    );
})
.factory('Assignment', function($resource) {
    return $resource('/assignment/:id?format=json',
        {id:'@id'},
        {
            'get': {method:'GET'},
            'save': {method: 'PUT'},
            'create': {method: 'POST'},
            'query':  {method:'GET', isArray:true},
            'remove': {method:'DELETE'},
            'delete': {method:'DELETE'}
        }
    );
})
.controller('TaskCtrl', function($scope, $state, $timeout, Task, Assignment) {
    $scope.refresh = function(){
        //assuming http updates will take less than 500 ms
        //better approach can be done here notifying UI
        $timeout(function(){
            $scope.tasks = Task.query();
        },500, true);
    };

    $scope.assignments = function(id){
        $state.go('tasks.assignments', {task_id: id}); 
    };

    $scope.send_sms = function(task){
        Assignment.create({'task_id': task.id});
        $scope.assignments(task.id);
        $scope.refresh();
    };

    $scope.complete = function(task){
        Task.save({'id': task.id, 'status':'complete'});
        $scope.refresh();
    };

    $scope.refresh();
})
.controller('AssignmentCtrl', function($scope, $stateParams, Assignment) {
    $scope.task_id = $stateParams.task_id;
    $scope.assignments = [];
    $scope.assignments = Assignment.query({'task_id': $stateParams.task_id});
});
