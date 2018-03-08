App
.controller('CategoryCreateCtrl', function($scope, $state, $stateParams, API, cfpLoadingBar) {

    cfpLoadingBar.start();

    var categories = API.serviceAdmin('category').getList();
    categories.then(function(response) {
        $scope.categories = API.formatCategory(response.categories);
    })
    .finally(function() {
        cfpLoadingBar.complete();
    });

    $scope.save = function($stateParams) {
        cfpLoadingBar.start();
        var category = API.serviceAdmin('category', null, $stateParams.data);
        category.post()
        .then(function(response) {
            swal({
                title: 'Good job!',
                text: response.message,
                type: 'success',
                confirmButtonText: 'OK',
                closeOnConfirm: true
            })
            $state.go('admin.category-list', {}, {reload: true});
        })
        .catch(function(response) {
            if ($scope.hasErrorName = !!response.error.name) {
                $scope.errorName = response.error.name[0];
            } else {
                $scope.errorName = null;
            }
            if ($scope.hasErrorParent = !!response.error.parent) {
                $scope.errorParent = response.error.parent[0];
            } else {
                $scope.errorParent = null;
            }
        })
        .finally(function() {
            cfpLoadingBar.complete();
        });
    }
});
