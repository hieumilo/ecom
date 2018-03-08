App
.controller('OrderListCtrl', 
function($scope, DTOptionsBuilder, DTColumnBuilder, $compile, API, $state, cfpLoadingBar) {

    cfpLoadingBar.start();

    var fnRowCallback = (nRow, aData, iDisplayIndex, iDisplayIndexFull) => {
        $compile(nRow)($scope);
    }

    var actionsHtml = (data) => {
        return `
        <div class="btn-group">
            <a class="btn btn-info" ui-sref="admin.order-edit({id: ` + data.id + `})">
                <i class="fa fa-lg fa-edit"></i>
            </a>
            <button class="btn btn-warning" ng-click="delete(${data.id})">
                <i class="fa fa-lg fa-trash"></i>
            </button>
        </div>`
    }

    var statusHtml = (data) => {
        if (data.status) {
            return `<span class="label label-info">accepted</span>`;
        } else {
            return `<span class="label label-warning">Not accepted yet</span>`;
        }
    }

    var order = API.serviceAdmin('order');

    $scope.dtOptions = DTOptionsBuilder
    .fromFnPromise(function() {
        return order.getList()
        .then(function(response){
            return response.orders;
        })
        .finally(function() {
            cfpLoadingBar.complete();
        });
    })
    .withDataProp('data')
    .withOption('fnRowCallback', fnRowCallback)
    .withBootstrap();

    $scope.dtColumns = [
        DTColumnBuilder.newColumn('user.email').withTitle('Email'),
        DTColumnBuilder.newColumn('user.name').withTitle('Name'),
        DTColumnBuilder.newColumn('address').withTitle('Address'),
        DTColumnBuilder.newColumn('phone').withTitle('Phone'),
        DTColumnBuilder.newColumn('created_at').withTitle('Created at'),
        DTColumnBuilder.newColumn(null).withTitle('Status').renderWith(statusHtml),
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
            var order = API.serviceAdmin('order',id);
            order.remove()
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
