App
.controller('ProductEditCtrl', function($scope, $stateParams, API, cfpLoadingBar, FileUploader) {

    cfpLoadingBar.start();

    var uploader = $scope.uploader = new FileUploader();
    var id = $stateParams.id;
    var product = API.serviceAdmin('product', id);
    var category = API.serviceAdmin('category');
    category.getList()
    .then(function(response) {
        $scope.categories = API.formatCategory(response.categories);
        return product.getOne();
    })
    .then(function(response) {
        $scope.product = response.product;
    })
    .finally(function() {
        cfpLoadingBar.complete();
    });

    $scope.removeImage = function(index) {
        console.log(index);
        console.log($scope.product);
        $scope.product.images.splice(index, 1);
        console.log($scope.product);
    }

    $scope.save = function($stateParams) {
        cfpLoadingBar.start();
        var formData = new FormData();

        angular.forEach($stateParams.product, function(value, key){
            formData.append(key, value);
        });

        angular.forEach($scope.product.images, function(value, key){
            console.log(value);
            formData.append('images[]', value.id);
        });

        angular.forEach($scope.uploader.queue, function(value, key) {
            formData.append('file[]', value._file)
        });

        var product = API.serviceAdmin('product', id, formData);
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
});
