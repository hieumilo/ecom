App
.controller('CategoryEditCtrl', function($scope, $stateParams, API, cfpLoadingBar) {

    cfpLoadingBar.start();

    var name = $stateParams.name;
    var categories = API.serviceAdmin('category');
    var category = API.serviceAdmin('category', name);

    categories.getList()
    .then(function(response) {
        $scope.categories = API.formatCategory(response.categories);
        return category.getOne();
    })
    .then(function(response) {
        $scope.category = response.category;
    })
    .finally(function() {
        cfpLoadingBar.complete();
    });

    $scope.save = function($stateParams) {
        cfpLoadingBar.start();
        var category = API.serviceAdmin('category',name,$stateParams.data);
        category.put()
        .then(function(response) {
            swal({
                title: 'Good job!',
                text: response.message,
                type: 'success',
                confirmButtonText: 'OK',
                closeOnConfirm: true
            })
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
