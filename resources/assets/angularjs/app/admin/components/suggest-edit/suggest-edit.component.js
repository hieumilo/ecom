App
.controller('SuggestEditCtrl', function ($scope, $stateParams, API) {
    var id = $stateParams.id;
    var suggest = API.serviceAdmin('suggest', id);
    suggest.getOne()
    .then(function(response) {
        $scope.suggest = response.suggest;
    })

    $scope.accept = function() {
        var data = {
            message: $scope.message,
            subject: $scope.subject
        }
        var suggest = API.serviceAdmin('suggest', id, data);
        suggest.put()
        .then(function(response) {
            swal({
                title: 'Good job!',
                text: response.message,
                type: 'success',
                confirmButtonText: 'OK',
                closeOnConfirm: true
            })
            $scope.suggest = response.suggest;
        })
        .catch(function(response) {
            swal({
                title: 'Something went wrong!',
                text: response.message,
                type: 'error',
                confirmButtonText: 'OK',
                closeOnConfirm: true
            })
        })
    }
});
