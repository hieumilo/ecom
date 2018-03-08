App
.controller('SearchCtrl', function ($scope, $location, API, Cart, toastr) {
    $scope.cart = Cart.cart;
    $scope.search = $location.search().search;
    $scope.showLoadMore = false;
    var filter = {};
    filter.search = $location.search().search;
    var product = API.service('search', filter);
    product.getListProduct()
    .then(function(response) {
        $scope.products = response.products.data;
        $scope.count = response.count;
        if (response.products.data.length > 0) {
            $scope.showLoadMore = true;
        }
    })

    var lastPage = 1;
    $scope.showLoading = true;
    $scope.loadMore = function() {
        $scope.showLoading = false;
        lastPage += 1;
        filter.page = lastPage;
        var product = API.service('search', filter);
        product.getListProduct()
        .then(function(response) {
            $scope.products = $scope.products.concat(response.products.data);
            if (response.products.data.length == 0) {
                $scope.showLoadMore = false;
            }
        })
        .finally(function() {
            $scope.showLoading = true;
        })
    }

    $scope.addItem = function(id, name, image, price) {
        $scope.cart.addItem(id, name, image, price, 1);
        toastr.success(name + ' has been added to cart', {timeOut: 3000});
    }
});
