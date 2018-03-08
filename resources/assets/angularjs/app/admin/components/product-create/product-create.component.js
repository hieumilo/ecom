App
.controller('ProductCreateCtrl', 
function($scope, $stateParams, API, cfpLoadingBar, FileUploader, $state) {

    cfpLoadingBar.start();

    var uploader = $scope.uploader = new FileUploader();
    var uploader1 = $scope.uploader1 = new FileUploader();
    var category = API.serviceAdmin('category');
    category.getList()
    .then(function(response) {
        $scope.categories = API.formatCategory(response.categories);
    })
    .finally(function() {
        cfpLoadingBar.complete();
    });

    $scope.save = function($stateParams) {
        cfpLoadingBar.start();
        var formData = new FormData();

        angular.forEach($stateParams.product, function(value, key){
            formData.append(key, value);
        });

        angular.forEach($scope.uploader.queue, function(value, key) {
            formData.append('file[]', value._file)
        });

        var product = API.serviceAdmin('product', null, formData);
        product.postWithFile()
        .then(function(response){
            swal({
                title: 'Good job!',
                text: response.message,
                type: 'success',
                confirmButtonText: 'OK',
                closeOnConfirm: true
            })
            $state.go('admin.product-list', {}, {reload: true});
        })
        .catch(function(response) {
            if ($scope.hasErrorName = !!response.error.name) {
                $scope.errorName = response.error.name[0];
            } else {
                $scope.errorName = null;
            }
            if ($scope.hasErrorPrice = !!response.error.price) {
                $scope.errorPrice = response.error.price[0];
            } else {
                $scope.errorPrice = null;
            }
            if ($scope.hasErrorStock = !!response.error.stock) {
                $scope.errorStock = response.error.stock[0];
            } else {
                $scope.errorStock = null;
            }
            if ($scope.hasErrorCategory = !!response.error.category_id) {
                $scope.errorCategory = response.error.category_id[0];
            } else {
                $scope.errorCategory = null;
            }
        })
        .finally(function() {
            cfpLoadingBar.complete();
        });
    }

    $scope.import = function() {
        cfpLoadingBar.start();
        var formData = new FormData();
        formData.append('file', $scope.uploader1.queue[0]._file)

        var product = API.serviceAdmin('import-products', null, formData);
        product.postWithFile()
        .then(function(response){
            swal({
                title: 'Good job!',
                text: response.message,
                type: 'success',
                confirmButtonText: 'OK',
                closeOnConfirm: true
            })
        })
        .catch(function(response) {
            swal({
                title: 'Good job!',
                text: response.message,
                type: 'error',
                confirmButtonText: 'OK',
                closeOnConfirm: true
            })
        })
        .finally(function() {
            cfpLoadingBar.complete();
        });
    }
});
