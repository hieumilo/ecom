App
.controller('UserListCtrl', function($scope, DTOptionsBuilder, DTColumnBuilder, 
        $compile, API, $state, cfpLoadingBar) {

    cfpLoadingBar.start();

    var fnRowCallback = (nRow, aData, iDisplayIndex, iDisplayIndexFull) => {
        $compile(nRow)($scope);
    }

    var actionsHtml = (data) => {
        return `
        <div class="btn-group">
            <a class="btn btn-info" ui-sref="admin.user-edit({id: ` + data.id + `})">
                <i class="fa fa-lg fa-edit"></i>
            </a>
            <button class="btn btn-warning" ng-click="delete(${data.id})">
                <i class="fa fa-lg fa-trash"></i>
            </button>
        </div>`
    }

    var user = API.serviceAdmin('user');

    $scope.dtOptions = DTOptionsBuilder
    .fromFnPromise(function() {
        return user.getList()
        .then(function(response){
            return response.users;
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
        DTColumnBuilder.newColumn('email').withTitle('Email'),
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
            var user = apiService.serviceAdmin('user',id);
            user.remove()
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
                    type: 'warning',
                    confirmButtonText: 'OK',
                    closeOnConfirm: true
                })
            })
        })
    }
});
