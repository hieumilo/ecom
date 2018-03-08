App
.controller('ProductsCtrl', function ($scope, $stateParams, API, Cart, toastr) {

    $scope.cart = Cart.cart;

    var category = $stateParams.name;
    var categories = API.service();
    var product = API.service(category);

    $scope.showLoadMore = false;
    $scope.showList = true;

    categories.getListCategory()
    .then(function(response) {
        $scope.categories = response.categories;
        return product.getListProduct();
    })
    .then(function(response) {
        $scope.products = response.products.data;
        if (response.products.data.length > 0) {
            $scope.showLoadMore = true;
        }
    });

    var lastPage = 1;
    $scope.showLoading = true;

    var filter = {};
    $scope.softByName = '';
    $scope.softByPrice = '';
    $scope.softByRate = '';
    $scope.filter = function($stateParams) {
        if ($stateParams.name == 'name') {
            filter.name = $stateParams.value;
        }
        if ($stateParams.name == 'price') {
            filter.price = $stateParams.value;
        }
        if ($stateParams.name == 'rate') {
            filter.rate = $stateParams.value;
        }
        lastPage = 1;
        filter.page = lastPage;
        var product = API.service(category, filter);
        product.getListProduct()
        .then(function(response) {
            $scope.products = response.products.data;
        });
    };

    $scope.loadMore = function() {
        $scope.showLoading = false;
        lastPage += 1;
        filter.page = lastPage;
        var product = API.service(category, filter);
        product.getListProduct()
        .then(function(response) {
            $scope.products = $scope.products.concat(response.products.data);
            if (response.products.data.length == 0) {
                $scope.showLoadMore = false;
            }
        })
        .finally(function() {
            $scope.showLoading = true;
        });
    };

    $scope.addItem = function(id, name, image, price) {
        $scope.cart.addItem(id, name, image, price, 1);
        toastr.success(name + ' has been added to cart', {timeOut: 3000});
    };
});
