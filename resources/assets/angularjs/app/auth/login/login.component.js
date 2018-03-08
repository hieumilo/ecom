App
.controller('LoginCtrl', function($scope, $state, $auth, auth) {
    $scope.email = 'admin@gmail.com';
    $scope.password = '123456';
    $scope.login = function() {
        var credentials = {
            email: $scope.email,
            password: $scope.password
        }
        $auth.login(credentials)
        .then(function() {
            return auth.me();
        })
        .then(function(response) {
            localStorage.setItem("User", JSON.stringify(response.user));
            $state.go('home.page', {}, {reload: true});
        })
        .catch(function(response) {
            alert(response.data.error);
        })
    };

    $scope.authenticate = function(provider) {
        $auth.authenticate(provider)
        .then(function() {
            return auth.me();
        })
        .then(function(response) {
            localStorage.setItem("User", JSON.stringify(response.user));
            $state.go('home.page', {}, {reload: true});
        })
    };
});
