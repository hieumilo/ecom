App
.factory('auth', Auth)
.factory('API', API)
.factory('recently', recently)
.factory('Cart', function() {
    return {
        cart: new shoppingCart(),
    }
});
