App
.controller('SignupCtrl', function($scope, $location, $auth) {
    $scope.signup = function() {
        $auth.signup($scope.user)
        .then(function(response) {
            $location.path('/');
        })
        .catch(function(er) {
            if ($scope.hasErrorName = !!er.data.error.name) {
                $scope.errorName = er.data.error.name[0];
            } else {
                $scope.errorName = null;
            }
            if ($scope.hasErrorEmail = !!er.data.error.email) {
                $scope.errorEmail = er.data.error.email[0];
            } else {
                $scope.errorEmail = null;
            }
            if ($scope.hasErrorPassword = !!er.data.error.password) {
                $scope.errorPassword = er.data.error.password[0];
            } else {
                $scope.errorPassword = null;
            }
            if ($scope.hasErrorRepassword = !!er.data.error.repassword) {
                $scope.errorRepassword = er.data.error.repassword[0];
            } else {
                $scope.errorRepassword = null;
            }
            if ($scope.hasErrorPhone = !!er.data.error.phone) {
                $scope.errorPhone = er.data.error.phone[0];
            } else {
                $scope.errorPhone = null;
            }
            if ($scope.hasErrorAddress = !!er.data.error.address) {
                $scope.errorAddress = er.data.error.address[0];
            } else {
                $scope.errorAddress = null;
            }
            if ($scope.hasErrorGender = !!er.data.error.gender) {
                $scope.errorGender = er.data.error.gender[0];
            } else {
                $scope.errorGender = null;
            }
        });
    };
});
