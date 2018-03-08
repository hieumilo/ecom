App
.controller('RecentlyCtrl', function ($scope, API, Cart, toastr) {
    $scope.cart = Cart.cart;
    var product = API.service();
    product.getProductRecently()
    .then(function(response) {
        $scope.products = response.products;
    })

    $scope.addItem = function(id, name, image, price) {
        $scope.cart.addItem(id, name, image, price, 1);
        toastr.success(name + ' has been added to cart', {timeOut: 3000});
    }
})
