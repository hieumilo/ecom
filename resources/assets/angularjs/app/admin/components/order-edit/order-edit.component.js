App
.controller('OrderEditCtrl', 
function($scope, DTOptionsBuilder, DTColumnBuilder, $compile, API, $state, cfpLoadingBar, $stateParams) {

    cfpLoadingBar.start();
    var id = $stateParams.id;

    var fnRowCallback = (nRow, aData, iDisplayIndex, iDisplayIndexFull) => {
        $compile(nRow)($scope);
    }
    var valueHtml = (data) => {
        return data.price * data.quantity;
    }

    var order = API.serviceAdmin('order', id);

    $scope.dtOptions = DTOptionsBuilder
    .fromFnPromise(function() {
        return order.getOne()
        .then(function(response){
            $scope.order = response.order;
            return response.order.items;
        })
        .finally(function() {
            cfpLoadingBar.complete();
        });
    })
    .withDataProp('data')
    .withOption('fnRowCallback', fnRowCallback)
    .withBootstrap();

    $scope.dtColumns = [
        DTColumnBuilder.newColumn('name').withTitle('Name'),
        DTColumnBuilder.newColumn('price').withTitle('Price'),
        DTColumnBuilder.newColumn('quantity').withTitle('Quantity'),
        DTColumnBuilder.newColumn(null).withTitle('Value').renderWith(valueHtml),
    ];

    $scope.acceptOrder = function(id) {
        var order = API.serviceAdmin('order', id);
        order.put()
        .then(function(response) {
            swal({
                title: 'Good job!',
                text: response.message,
                type: 'success',
                confirmButtonText: 'OK',
                closeOnConfirm: true
            })
        })
        .catch(function(response) {
            swal({
                title: 'Something went wrong!',
                text: response.message,
                type: 'warning',
                confirmButtonText: 'OK',
                closeOnConfirm: true
            })
        })
    }
});
