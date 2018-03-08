App
.controller('AdminHeaderCtrl', function($scope, $state, $auth, auth) {
    auth.isAuthenticated()
    .then(function() {
        $scope.isAuthenticated = true;
        $scope.user = auth.getCurrentUser();
    });
})
