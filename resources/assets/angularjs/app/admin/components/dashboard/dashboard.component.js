App
.controller('DashboardCtrl', function($scope, API, $stateParams, $state, cfpLoadingBar, $http) {
    var dashboard = API.serviceAdmin('dashboard');
    dashboard.getList()
    .then(function(response) {
        $scope.new_suggest = response.new_suggest;
        $scope.user = response.user;
        $scope.product = response.product;
        $scope.new_order = response.new_order;

        $scope.options_bar = { legend: { display: true } };
        $scope.labels_bar = [];
        $scope.series_bar = ['Top 5 product'];
        $scope.data_bar = [];
        $scope.data_bar[0] = [];
        angular.forEach(response.sellingProducts, function(value, key) {
            $scope.labels_bar.push(value.name);
            $scope.data_bar[0].push(value.count);
        })
    })

    $scope.export = function() {
        API.serviceAdmin().export();
    }
});
