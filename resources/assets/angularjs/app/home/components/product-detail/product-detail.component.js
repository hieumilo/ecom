App
.controller('ProductDetailCtrl', function ($scope, $stateParams, API, $location, Cart, recently, auth, toastr) {
    $scope.cart = Cart.cart;
    $scope.recently = recently;

    auth.isAuthenticated()
    .then(function() {
        $scope.isAuthenticated = true;
        return auth.me();
    })
    .then(function(response) {
        $scope.user = response.user;
    })
    .catch(function() {
        $scope.isAuthenticated = false;
    })

    $scope.rating = 0;
    $scope.rating1 = 0;
    $scope.isReadonly = true;
    var name = $stateParams.name;
    var product = API.service(name);
    product.getOne()
    .then(function(response) {
        $scope.product = response.product;
        recently.add(response.product.id);
        $scope.rating1 = response.product.rate;
        $scope.like = {
            Url: $location.$$absUrl
        };

        $scope.images = [];
        angular.forEach($scope.product.images, function(value, key) {
            $scope.images.push(value.image);
        })

        $scope.tab = 1;
        $scope.setTab = function(newTab){
            $scope.tab = newTab;
        };
        $scope.isSet = function(tabNum){
            return $scope.tab === tabNum;
        };
    })
    
    $scope.rate = function(rate) {
        var data = {
            rate: rate,
            name: $stateParams.name
        };
        var rate = API.service('rate', null, data)
        rate.post()
        .then(function(response) {
            $scope.rating1 = response.rate;
        })
    };

    $scope.addItem = function(id, name, image, price) {
        $scope.cart.addItem(id, name, image, price, 1);
        toastr.success(name + ' has been added to cart', {timeOut: 3000});
    }

    var comments = API.service(name);
    comments.getComment()
    .then(function(response) {
        $scope.comments = response.comments;
    })

    $scope.cmt = 0;
    $scope.setComment = function(newTab){
        $scope.cmt = newTab;
    };
    $scope.isSetComment = function(tabNum){
        return $scope.cmt === tabNum;
    };

    $scope.edit = 0;
    $scope.editComment = function(newTab){
        $scope.edit = newTab;
    };
    $scope.isEditCmt = function(tabNum){
        return $scope.edit === tabNum;
    };

    $scope.send = function($stateParams) {

        var data = {
            content: $stateParams.content,
            parent_id: $stateParams.parent_id
        }

        var comment = API.service(name, null, data);
        comment.postComment()
        .then(function(response) {
            $scope.content = "";
            $scope.comments = response.comments;
            $scope.cmt = 0;
        })
    }

    $scope.update = function($stateParams) {
        var comment = API.service($stateParams.comment.id, null, $stateParams.comment);
        comment.putComment()
        .then(function(response) {
            $scope.comments = response.comments;
            $scope.edit = 0;
        })
    }

    $scope.delete = function(id) {
        if (confirm('delete ??')) {
            var comment = API.service(id);
            comment.deleteComment()
            .then(function(response) {
                $scope.comments = response.comments;
                $scope.edit = 0;
            })
        }
    }
});
