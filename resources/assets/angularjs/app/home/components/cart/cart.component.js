App
.controller('CartCtrl', function ($scope, $stateParams, API, $location, Cart, auth, toastr) {
    $scope.cart = Cart.cart;
    $scope.isAuthenticated = false;
    $scope.show = true;

    // toastr.success('response.message', {timeOut: 3000});

    auth.isAuthenticated()
    .then(function() {
        $scope.isAuthenticated = true;
        $scope.user = auth.getCurrentUser();
    });

    $scope.order = {
        items: $scope.cart.items,
        totalCount : $scope.cart.getTotalCount(),
        totalPrice : $scope.cart.getTotalPrice()
    }

    $scope.sendOrder = function() {
        if (!$scope.isAuthenticated) {
            return;
        }
        $scope.show = false;
        var data = {
            address: $scope.user.address,
            phone: $scope.user.phone,
            user_id: $scope.user.id,
            price: $scope.cart.getTotalPrice(),
            order_items: $scope.cart.items
        };

        var order = API.service('order', null, data);
        order.post()
        .then(function(response) {
            $scope.cart.clearItems();
            $scope.products = null;
            toastr.success(response.message, {timeOut: 5000});
        })
        .catch(function(response) {
            $scope.products = response.data;
            toastr.error(response.error, {timeOut: 5000});
        })
        .finally(function() {
            $scope.show = true;
        })
    }
});
