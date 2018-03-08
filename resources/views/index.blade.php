<!DOCTYPE html>
<html lang="en" ng-app="App">
<head>
    <meta charset="UTF-8">
    <title ng-bind="$state.current.data.pageTitle"></title>
    <!-- Bootstrap v3.3.7 -->
    {{ Html::style('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css') }}
    <!-- Font Awesome -->
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css') }}
    <!-- Ionicons -->
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css') }}
    <!-- bootstrap-social -->
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css') }}
    <!-- sweetalert -->
    {{ Html::style('node_modules/sweetalert/dist/sweetalert.css') }}
</head>
<body ng-class="$state.current.data.bodyClass">

    <div ui-view="layout"></div>

    <!-- jquery v3.2.1 -->
    {{ Html::script('https://code.jquery.com/jquery-3.2.1.min.js') }}
    <!-- Bootstrap v3.3.7 -->
    {{ Html::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js') }}
    <!-- jquery-easing v1.4.1 -->
    {{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js') }}

    {{ Html::script('node_modules/datatables/media/js/jquery.dataTables.min.js') }}
    
    {{ Html::script('node_modules/angular/angular.js') }}
    <!-- angular ui-router -->
    {{ Html::script('node_modules/angular-ui-router/release/angular-ui-router.js') }}
    <!-- satellizer -->
    {{ Html::script('node_modules/satellizer/dist/satellizer.js') }}
    <!-- angular css -->
    {{ Html::script('node_modules/angular-css/angular-css.min.js') }}
    <!-- angular file upload -->
    {{ Html::script('node_modules/angular-file-upload/dist/angular-file-upload.min.js') }}
    <!-- angular translate -->
    {{ Html::script('node_modules/angular-translate/dist/angular-translate.min.js') }}
    <!-- dataTables.bootstrap -->
    {{ Html::script('node_modules/angular-datatables/dist/plugins/bootstrap/angular-datatables.bootstrap.min.js') }}
    <!-- angular-datatables -->
    {{ Html::script('node_modules/angular-datatables/dist/angular-datatables.min.js') }}
    <!-- angular-loading-bar -->
    {{ Html::script('node_modules/angular-loading-bar/build/loading-bar.min.js') }}
    <!-- chart -->
    {{ Html::script('node_modules/chart.js/dist/Chart.min.js') }}
    <!-- angular-chart -->
    {{ Html::script('node_modules/angular-chart.js/dist/angular-chart.min.js') }}
    <!-- angular-cookies -->
    {{ Html::script('node_modules/angular-cookies/angular-cookies.js') }}
    <!-- jquery flexslider -->
    {{ Html::script('node_modules/flexslider/jquery.flexslider.js') }}
    <!-- angular-flexslider -->
    {{ Html::script('node_modules/angular-flexslider/angular-flexslider.js') }}
    <!-- ng-timeago -->
    {{ Html::script('bower_components/ng-timeago/ngtimeago.js') }}
    <!-- sweetalert -->
    {{ Html::script('node_modules/sweetalert/dist/sweetalert.min.js') }}
    <!-- sweetalert -->
    {{ Html::script('node_modules/angular-toastr/dist/angular-toastr.tpls.js') }}
    <!-- admin script -->
    {{ Html::script('resources/assets/angularjs/app/admin/js/app.min.js') }}
    <!-- app script -->
    <!-- config -->
    {{ Html::script('resources/assets/angularjs/config/routes.config.js') }}
    {{ Html::script('resources/assets/angularjs/config/satellizer.config.js') }}
    {{ Html::script('resources/assets/angularjs/lang/en.js') }}
    {{ Html::script('resources/assets/angularjs/config/translate.config.js') }}
    <!-- service -->
    {{ Html::script('resources/assets/angularjs/services/auth.service.js') }}
    {{ Html::script('resources/assets/angularjs/services/API.service.js') }}
    {{ Html::script('resources/assets/angularjs/services/cart.service.js') }}
    {{ Html::script('resources/assets/angularjs/services/recently.service.js') }}
    <!-- directive -->
    {{ Html::script('resources/assets/angularjs/directives/ng-thum.directive.js') }}
    {{ Html::script('resources/assets/angularjs/directives/like-share.directive.js') }}
    {{ Html::script('resources/assets/angularjs/directives/comment-fb.directive.js') }}
    {{ Html::script('resources/assets/angularjs/directives/star-rating.directive.js') }}
    <!-- module -->
    {{ Html::script('resources/assets/angularjs/app.module.js') }}
    {{ Html::script('resources/assets/angularjs/app.constant.js') }}
    {{ Html::script('resources/assets/angularjs/app.run.js') }}
    {{ Html::script('resources/assets/angularjs/app.config.js') }}
    {{ Html::script('resources/assets/angularjs/app.service.js') }}
    {{ Html::script('resources/assets/angularjs/app.directive.js') }}
    <!-- home -->
    {{ Html::script('resources/assets/angularjs/app/home/pages/header/header.js') }}
    {{ Html::script('resources/assets/angularjs/app/home/components/homepage/homepage.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/home/components/search/search.component.js') }}
    <!-- auth -->
    {{ Html::script('resources/assets/angularjs/app/auth/login/login.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/auth/logout/logout.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/auth/signup/singup.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/auth/profile/profile.component.js') }}
    
    {{ Html::script('resources/assets/angularjs/app/home/components/product-list/product-list.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/home/components/product-detail/product-detail.component.js') }}
    <!-- recently -->
    {{ Html::script('resources/assets/angularjs/app/home/components/recently/recently.component.js') }}
    <!-- suggest -->
    {{ Html::script('resources/assets/angularjs/app/home/components/suggest/suggest.component.js') }}
    <!-- cart -->
    {{ Html::script('resources/assets/angularjs/app/home/components/cart/cart.component.js') }}
    <!-- admin -->
    {{ Html::script('resources/assets/angularjs/app/admin/pages/header/header.js') }}
    <!-- dashboard -->
    {{ Html::script('resources/assets/angularjs/app/admin/components/dashboard/dashboard.component.js') }}
    <!-- user -->
    {{ Html::script('resources/assets/angularjs/app/admin/components/user-list/user-list.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/admin/components/user-edit/user-edit.component.js') }}
    <!-- category -->
    {{ Html::script('resources/assets/angularjs/app/admin/components/category-list/category-list.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/admin/components/category-create/category-create.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/admin/components/category-edit/category-edit.component.js') }}
    <!-- product -->
    {{ Html::script('resources/assets/angularjs/app/admin/components/product-list/product-list.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/admin/components/product-edit/product-edit.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/admin/components/product-create/product-create.component.js') }}
    <!-- suggest -->
    {{ Html::script('resources/assets/angularjs/app/admin/components/suggest-list/suggest-list.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/admin/components/suggest-edit/suggest-edit.component.js') }}
    <!-- order -->
    {{ Html::script('resources/assets/angularjs/app/admin/components/order-list/order-list.component.js') }}
    {{ Html::script('resources/assets/angularjs/app/admin/components/order-edit/order-edit.component.js') }}
    
</body>
</html>
