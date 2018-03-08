App
.controller('HomePageCtrl', function ($scope, API, Cart, recently, toastr) {
    $scope.cart = Cart.cart;
    $scope.recently = recently;
    var product = API.service();
    product.getNewProducts()
    .then(function(response) {
        $scope.products = response.products;
    });

    $scope.addItem = function(id, name, image, price) {
        $scope.cart.addItem(id, name, image, price, 1);
        toastr.success(name + ' has been added to cart', {timeOut: 3000});
    }
});
