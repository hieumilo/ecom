App
.controller('SuggestCtrl', function ($scope, API, $stateParams, FileUploader, toastr) {
    var uploader = $scope.uploader = new FileUploader();
    var categories = API.service('category');
    categories.getListCategory()
    .then(function(response) {
        $scope.categories = API.formatCategory(response.categories);
    })

    $scope.send = function($stateParams) {
        var formData = new FormData();
        console.log($stateParams);
        angular.forEach($stateParams.info, function(value, key){
            formData.append(key, value);
        });

        angular.forEach($scope.uploader.queue, function(value, key) {
            formData.append('file[]', value._file)
        });

        var suggest = API.service('suggest', null, formData);
        suggest.postWithFile()
        .then(function(response) {
            toastr.success(response.message, {timeOut: 3000});
        })
        .catch(function(response) {
            if ($scope.hasErrorEmail = !!response.error.email) {
                $scope.errorEmail = response.error.email[0];
            } else {
                $scope.errorEmail = null;
            }
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
            if ($scope.hasErrorCategory = !!response.error.category_id) {
                $scope.errorCategory = response.error.category_id[0];
            } else {
                $scope.errorCategory = null;
            }
        })
    }
})
