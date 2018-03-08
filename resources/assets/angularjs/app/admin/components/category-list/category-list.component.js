App
.controller('CategoryListCtrl', function($scope, API, $stateParams, $state, cfpLoadingBar) {

    cfpLoadingBar.start();

    var categories = API.serviceAdmin('category');
    categories.getList()
    .then(function(response) {
        $scope.categories = response.categories;
    })
    .finally(function() {
        cfpLoadingBar.complete();
    });

    $scope.remove = function($stateParams) {
        swal({
            title: 'Are you sure?',
            text: 'You will not be able to recover this data!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            html: false
        }, function() {
            var category = API.serviceAdmin('category', $stateParams.id);
            category.remove()
            .then(function(response) {
                swal({
                    title: 'Deleted!',
                    text: response.message,
                    type: 'success',
                    confirmButtonText: 'OK',
                    closeOnConfirm: true
                }, function() {
                    $state.reload()
                })
            })
            .catch(function(response) {
                swal({
                    title: 'Deleted!',
                    text: response.message,
                    type: 'error',
                    confirmButtonText: 'OK',
                    closeOnConfirm: true
                })
            })
        })
    }
});
