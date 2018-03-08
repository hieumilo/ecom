App
.controller('UserEditCtrl', function($scope, $stateParams, API, cfpLoadingBar) {

    cfpLoadingBar.start();

    var id = $stateParams.id;
    var user = API.serviceAdmin('user', id);
    user.getOne()
    .then(function(response) {
        $scope.user = response.user;
    })
    .finally(function() {
        cfpLoadingBar.complete();
    });
});
