const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.scripts([
        // config
    'resources/assets/angularjs/config/routes.config.js',
    'resources/assets/angularjs/config/satellizer.config.js',
    'resources/assets/angularjs/lang/en.js',
    'resources/assets/angularjs/config/translate.config.js',
    // service
    'resources/assets/angularjs/services/auth.service.js',
    'resources/assets/angularjs/services/API.service.js',
    'resources/assets/angularjs/services/cart.service.js',
    'resources/assets/angularjs/services/recently.service.js',
    // directive
    'resources/assets/angularjs/directives/ng-thum.directive.js',
    'resources/assets/angularjs/directives/like-share.directive.js',
    'resources/assets/angularjs/directives/comment-fb.directive.js',
    'resources/assets/angularjs/directives/star-rating.directive.js',
    // module
    'resources/assets/angularjs/app.module.js',
    'resources/assets/angularjs/app.constant.js',
    'resources/assets/angularjs/app.run.js',
    'resources/assets/angularjs/app.config.js',
    'resources/assets/angularjs/app.service.js',
    'resources/assets/angularjs/app.directive.js',
    // home
    'resources/assets/angularjs/app/home/pages/header/header.js',
    'resources/assets/angularjs/app/home/components/homepage/homepage.component.js',
    'resources/assets/angularjs/app/home/components/search/search.component.js',
    // auth
    'resources/assets/angularjs/app/auth/login/login.component.js',
    'resources/assets/angularjs/app/auth/logout/logout.component.js',
    'resources/assets/angularjs/app/auth/signup/singup.component.js',
    'resources/assets/angularjs/app/auth/profile/profile.component.js',
    
    'resources/assets/angularjs/app/home/components/product-list/product-list.component.js',
    'resources/assets/angularjs/app/home/components/product-detail/product-detail.component.js',
    // recently
    'resources/assets/angularjs/app/home/components/recently/recently.component.js',
    // suggest
    'resources/assets/angularjs/app/home/components/suggest/suggest.component.js',
    // cart
    'resources/assets/angularjs/app/home/components/cart/cart.component.js',
    // admin
    'resources/assets/angularjs/app/admin/pages/header/header.js',
    // dashboard
    'resources/assets/angularjs/app/admin/components/dashboard/dashboard.component.js',
    // user
    'resources/assets/angularjs/app/admin/components/user-list/user-list.component.js',
    'resources/assets/angularjs/app/admin/components/user-edit/user-edit.component.js',
    // category
    'resources/assets/angularjs/app/admin/components/category-list/category-list.component.js',
    'resources/assets/angularjs/app/admin/components/category-create/category-create.component.js',
    'resources/assets/angularjs/app/admin/components/category-edit/category-edit.component.js',
    // product
    'resources/assets/angularjs/app/admin/components/product-list/product-list.component.js',
    'resources/assets/angularjs/app/admin/components/product-edit/product-edit.component.js',
    'resources/assets/angularjs/app/admin/components/product-create/product-create.component.js',
    // suggest
    'resources/assets/angularjs/app/admin/components/suggest-list/suggest-list.component.js',
    'resources/assets/angularjs/app/admin/components/suggest-edit/suggest-edit.component.js',
    // order
    'resources/assets/angularjs/app/admin/components/order-list/order-list.component.js',
    'resources/assets/angularjs/app/admin/components/order-edit/order-edit.component.js',
    ], 'public/js/app.js')
