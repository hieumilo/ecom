App
.controller('ProductListCtrl', 
function($scope, DTOptionsBuilder, DTColumnBuilder, $compile, API, $state, cfpLoadingBar) {

    cfpLoadingBar.start();

    var fnRowCallback = (nRow, aData, iDisplayIndex, iDisplayIndexFull) => {
        $compile(nRow)($scope);
    }

    var actionsHtml = (data) => {
        return `
        <div class="btn-group">
            <a class="btn btn-info" ui-sref="admin.product-edit({id: ` + data.id + `})">
                <i class="fa fa-lg fa-edit"></i>
            </a>
            <button class="btn btn-warning" ng-click="delete(${data.id})">
                <i class="fa fa-lg fa-trash"></i>
            </button>
        </div>`
    }

    var product = API.serviceAdmin('product');

    $scope.dtOptions = DTOptionsBuilder
    .fromFnPromise(function() {
        return product.getList()
        .then(function(response){
            return response.products;
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
        DTColumnBuilder.newColumn('rate').withTitle('Rate'),
        DTColumnBuilder.newColumn('stock').withTitle('Stock'),
        DTColumnBuilder.newColumn('category.name').withTitle('Category'),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable().renderWith(actionsHtml)
    ];

    $scope.delete = function(id){
        swal({
            title: 'Are you sure?',
            text: 'You will not be able to recover this data!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            html: false
        }, function() {
            var product = API.serviceAdmin('product',id);
            product.remove()
            .then(function(response) {
                swal({
                    title: 'Deleted!',
                    text: response.message,
                    type: 'success',
                    confirmButtonText: 'OK',
                    closeOnConfirm: true
                }, function() {
                    $state.reload()
                })
            })
            .catch(function(response) {
                swal({
                    title: 'Deleted!',
                    text: response.message,
                    type: 'error',
                    confirmButtonText: 'OK',
                    closeOnConfirm: true
                })
            })
        })
    }

});
