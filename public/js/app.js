function RoutesConfig($stateProvider, $urlRouterProvider) {

    var skipIfLoggedIn = ['$q', '$auth', function($q, $auth) {
        var deferred = $q.defer();

        if ($auth.isAuthenticated()) {
            deferred.reject();
        } else {
            deferred.resolve();
        }

        return deferred.promise;
    }];

    var loginRequired = ['$q', '$location', '$auth', function($q, $location, $auth) {
        var deferred = $q.defer();

        if ($auth.isAuthenticated()) {
            deferred.resolve();
        } else {
            $location.path('/login');
        }

        return deferred.promise;
    }];

    function getLayout(folder, layout) {
        return `resources/assets/angularjs/app/${folder}/components/${layout}/${layout}.component.html`;
    }

    /**
    * App routes
    */
    $urlRouterProvider.otherwise('/');

    $stateProvider
    .state('home', {
        abstract : true,
        views : {
            'layout': {
                templateUrl : 'resources/assets/angularjs/app/home/index.html',
            },
            'header@home' : {
                templateUrl : 'resources/assets/angularjs/app/home/pages/header/header.html',
                controller : 'HeaderCtrl'
            },
            'footer@home' : {
                templateUrl : 'resources/assets/angularjs/app/home/pages/footer/footer.html',
            },
            main: {}
        },
    })
    .state('home.login', {
        url : '/login',
        views : {
            'main@home' : {
                templateUrl : 'resources/assets/angularjs/app/auth/login/login.component.html',
                controller : 'LoginCtrl',
            }
        },
        css : [
            'resources/assets/angularjs/app/home/css/style.css',
            'resources/assets/angularjs/app/home/css/style1.css',
        ],
        data : {
            pageTitle : 'Project 1 - Login',
        },
        resolve : {
            skipIfLoggedIn : skipIfLoggedIn,
        }
    })
    .state('logout', {
        url : '/logout',
        views : {
            'layout': {
                templateUrl: null,
                controller: 'LogoutCtrl',
            }
        }
    })
    .state('home.signup', {
        url : '/signup',
        views : {
            'main@home': {
                templateUrl : 'resources/assets/angularjs/app/auth/signup/signup.component.html',
                controller : 'SignupCtrl',
            }
        },
        css : [
            'resources/assets/angularjs/app/home/css/style.css',
            'resources/assets/angularjs/app/home/css/style1.css',
        ],
        data : {
            pageTitle : 'Project 1 - Signup',
        },
        resolve : {
            skipIfLoggedIn : skipIfLoggedIn,
        }
    })
    .state('home.page', {
        url : '/',
        views : {
            'main@home' : {
                templateUrl : getLayout('home', 'homepage'),
            }
        },
        css : [
            'resources/assets/angularjs/app/home/css/style.css',
            'resources/assets/angularjs/app/home/css/style1.css',
        ],
        data : {
            pageTitle : 'Project 1 - Home Page',
        }
    })
    .state('admin', {
        abstract : true,
        views : {
            'layout' : {
                templateUrl : 'resources/assets/angularjs/app/admin/index.html',
            },
            'header@admin' : {
                templateUrl : 'resources/assets/angularjs/app/admin/pages/header/header.html',
            },
            'footer@admin' : {
                templateUrl : 'resources/assets/angularjs/app/admin/pages/footer/footer.html',
            },
            main : {}
        },
        data : {
            bodyClass : 'hold-transition skin-blue sidebar-mini',
        },
    })
    .state('admin.dashboard', {
        url : '/admin/dashboard',
        views : {
            'main@admin' : {
                templateUrl : getLayout('admin', 'dashboard'),
            }
        },
        css : [
            'resources/assets/angularjs/app/admin/css/AdminLTE.min.css',
            'resources/assets/angularjs/app/admin/css/skins/_all-skins.min.css',
        ],
        data : {
            pageTitle : 'Project 1 - Admin Dashboard',
        }
    })
    .state('admin.category-list', {
        url : '/admin/category-list',
        views : {
            'main@admin' : {
                templateUrl : getLayout('admin', 'category-list'),
                controller : 'CategoryListCtrl',
            }
        },
        css : [
            'resources/assets/angularjs/app/admin/css/AdminLTE.min.css',
            'resources/assets/angularjs/app/admin/css/skins/_all-skins.min.css',
        ],
        data : {
            pageTitle : 'Project 1 - Admin category list',
        }
    })
    .state('admin.category-create', {
        url : '/admin/category-create',
        views : {
            'main@admin' : {
                templateUrl : getLayout('admin', 'category-create'),
                controller : 'CategoryCreateCtrl',
            }
        },
        css : [
            'resources/assets/angularjs/app/admin/css/AdminLTE.min.css',
            'resources/assets/angularjs/app/admin/css/skins/_all-skins.min.css',
        ],
        data : {
            pageTitle : 'Project 1 - Admin category create',
        }
    })
    .state('admin.category-edit', {
        url : '/admin/category-edit/:name',
        views : {
            'main@admin' : {
                templateUrl : getLayout('admin', 'category-edit'),
                controller : 'CategoryEditCtrl'
            }
        },
        css : [
            'resources/assets/angularjs/app/admin/css/AdminLTE.min.css',
            'resources/assets/angularjs/app/admin/css/skins/_all-skins.min.css',
        ],
        data : {
            pageTitle : 'Project 1 - Admin category edit',
        }
    })
    .state('admin.user-list', {
        url : '/admin/user-list',
        views : {
            'main@admin' : {
                templateUrl : getLayout('admin', 'user-list'),
                controller : 'UserListCtrl'
            }
        },
        css : [
            'resources/assets/angularjs/app/admin/css/AdminLTE.min.css',
            'resources/assets/angularjs/app/admin/css/skins/_all-skins.min.css',
            'node_modules/angular-datatables/dist/css/angular-datatables.min.css',
        ],
        data : {
            pageTitle : 'Project 1 - Admin user list',
        }
    });
}

function SatellizerConfig ($authProvider, API_URL) {
    'ngInject'

    $authProvider.httpInterceptor = function () {
        return true;
    }

    $authProvider.loginUrl = API_URL + 'auth/login';
    $authProvider.signupUrl = API_URL + 'auth/signup';

    $authProvider.facebook({
        clientId: '491224784419802'
    });

    $authProvider.google({
        clientId: '511036233663-j09c0lelfbtstlr9qtfl31290dnrvqb7.apps.googleusercontent.com'
    });

    // Facebook
    $authProvider.facebook({
        name: 'facebook',
        url: API_URL + 'auth/facebook',
        authorizationEndpoint: 'https://www.facebook.com/v2.5/dialog/oauth',
        redirectUri: window.location.origin + '/',
        requiredUrlParams: ['display', 'scope'],
        scope: ['email'],
        scopeDelimiter: ',',
        display: 'popup',
        oauthType: '2.0',
        popupOptions: { width: 580, height: 400 }
    });

    // Google
    $authProvider.google({
        url: API_URL + 'auth/google',
        authorizationEndpoint: 'https://accounts.google.com/o/oauth2/auth',
        redirectUri: window.location.origin,
        requiredUrlParams: ['scope'],
        optionalUrlParams: ['display'],
        scope: ['profile', 'email'],
        scopePrefix: 'openid',
        scopeDelimiter: ' ',
        display: 'popup',
        oauthType: '2.0',
        popupOptions: { width: 452, height: 633 }
    });

    // Twitter
    $authProvider.twitter({
        url: API_URL + 'auth/twitter',
        authorizationEndpoint: 'https://api.twitter.com/oauth/authenticate',
        redirectUri: window.location.origin,
        oauthType: '1.0',
        popupOptions: { width: 495, height: 645 }
    });
}

var translationsEN = {
    AUTH: {
        // login
        BTN_LOGIN: 'Login',
        LOGIN_TITLE: 'Sign in with your account',
        BTN_LOGIN_FB: 'Sign in with Facebook',
        BTN_LOGIN_GG: 'Sign in with Google',
        BTN_LOGIN_TT: 'Sign in with Twitter',
        FORGOT_PASSWORD: 'Forgot password?',
        REMEMBER_ME: 'Remember me.',
        // logout
        BTN_LOGOUT: 'Logout',
        // signup
        SIGNUP_TITLE: 'Sign up new account',
        BTN_SIGNUP: 'Sign up',
        NAME: 'Name',
        EMAIL: 'Email',
        PASSWORD: 'Password',
        RE_PASSWORD: 'Re-Password',
        PHONE: 'Phone',
        ADDRESS: 'Address',
        GENDER: 'Gender',
        MALE: 'Male',
        FEMALE: 'Female',
        // profile
        PROFILE: 'Profile',
    },

    LB_SEARCH_TEXT: 'Search',

    ADMIN: {
        BUTTON: {
            CREATE: 'Create',
            UPDATE: 'Update',
            BACK: 'Back',
        },
        FORM: {
            CATEGORY_CREATE: 'Category Create',
            CATEGORY_EDIT: 'Category Edit',
        },
        LABEL: {
            CATEGORY_LIST: 'Category List',
            CATEGORY: 'Category',
            NAME: 'Name',
            PARENT: 'Parent',
        },
    },
};

function TranslateConfig($translateProvider) {
    $translateProvider.translations('en', translationsEN);
    $translateProvider.preferredLanguage('en');
    $translateProvider.fallbackLanguage('en');
}

function Auth($http, API_URL, $q, $auth) {

    return {
        isAuthenticated: function() {
            var deferred = $q.defer();
            if ($auth.isAuthenticated()) {
                deferred.resolve();
            } else {
                deferred.reject();
            }

            return deferred.promise;
        },

        skipIfLoggedIn: function() {
            var deferred = $q.defer();
            if ($auth.isAuthenticated()) {
                deferred.reject();
            } else {
                deferred.resolve();
            }

            return deferred.promise;
        },

        isAdmin: function(){
            var deferred = $q.defer();
            var user = angular.fromJson(localStorage.getItem("User"));
            if (user.is_admin) {
                deferred.resolve();
            } else {
                deferred.reject();
            }

            return deferred.promise;
        },

        profile: function() {
            var deferred = $q.defer();
            $http.get(API_URL + 'auth/me')
            .success(function (data) {
                deferred.resolve(data);
            })
            .error(function (error) {
                deferred.reject(error);
            });
            
            return deferred.promise;
        },

        getCurrentUser: function() {
            return angular.fromJson(localStorage.getItem("User"));
        },
    };
};

function API($http, API_URL, $q) {

    function get(url) {
        var deferred = $q.defer();
        $http.get(url)
            .success(function (data) {
                deferred.resolve(data);
            })
            .error(function (error) {
                deferred.reject(error);
            });
        return deferred.promise;
    }

    function post(url, data){
        var deferred = $q.defer();
        $http.post(url,data)
            .success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data);
            });
        return deferred.promise;
    }

    function postWithFile(url, data){
        var deferred = $q.defer();
        $http({
            method: 'POST',
            url: url,
            headers: {
                'Content-Type': undefined
            },
            data: data,
        }).success(function (data) {
            deferred.resolve(data);
        }).error(function (data, status) {
            deferred.reject(data);
        });
        return deferred.promise;
    }

    function put(url, data){
        var deferred = $q.defer();
        $http.put(url,data)
            .success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data);
            });
        return deferred.promise;
    }

    function remove(url){
        var deferred = $q.defer();
        $http.delete(url)
            .success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data);
            });
        return deferred.promise;
    }

    return {
        serviceAdmin: function(name,slug = null, data = null){
            return {
                getList: function(){
                    return get(API_URL + 'admin/' + name);
                },
                getOne: function(){
                    return get(API_URL + 'admin/' + name + '/' + slug);
                },
                post: function(){
                    return post(API_URL + 'admin/' + name, data);
                },
                put: function(){
                    return put(API_URL + 'admin/' + name + '/' + slug, data);
                },
                remove: function(){
                    return remove(API_URL + 'admin/' + name + '/' + slug);
                }
            }
        }
    };
};

var App = angular.module('App', [
    'ui.router',
    'satellizer',
    'angularCSS',
    'angularFileUpload',
    'pascalprecht.translate',
]);

App
.constant('API_URL', 'http://api.ecommerce.com/');

App
.run([ '$rootScope', '$state', '$stateParams', function ($rootScope, $state, $stateParams) {
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;   
}]);

App
.config(RoutesConfig)
.config(SatellizerConfig)
.config(TranslateConfig);

App
.factory('auth', Auth)
.factory('API', API);

App
.controller('HeaderCtrl', function($auth, auth, $scope) {
    auth.isAuthenticated()
    .then(function() {
        $scope.isAuthenticated = true;
        var user = auth.getCurrentUser();
        $scope.username = user.name;
    });
});

App
.controller('LoginCtrl', function($scope, $state, $auth, auth) {
    $scope.login = function() {
        var credentials = {
            email: $scope.email,
            password: $scope.password
        }
        $auth.login(credentials)
        .then(function() {
            return auth.profile();
        })
        .then(function(response) {
            localStorage.setItem("User", JSON.stringify(response.user));
            $state.go('home.page', {}, {reload: true});
        })
        .catch(function(response) {
            alert(response.data.error);
        })
    };

    $scope.authenticate = function(provider) {
        $auth.authenticate(provider)
        .then(function() {
            return auth.profile();
        })
        .then(function(response) {
            localStorage.setItem("User", JSON.stringify(response.user));
            $state.go('home.page', {}, {reload: true});
        })
    };
});

App
.controller('LogoutCtrl', function($auth, $state) {

    if (!$auth.isAuthenticated()) {
        return;
    }
    
    $auth.logout()
    .then(function() {
        localStorage.removeItem("User");
        $state.go('home.page', {}, {reload: true});
    });
});

App
.controller('SignupCtrl', function($scope, $location, $auth) {
    $scope.signup = function() {
        $auth.signup($scope.user)
        .then(function(response) {
            $location.path('/');
        })
        .catch(function(er) {
            if ($scope.hasErrorName = !!er.data.error.name) {
                $scope.errorName = er.data.error.name[0];
            } else {
                $scope.errorName = null;
            }
            if ($scope.hasErrorEmail = !!er.data.error.email) {
                $scope.errorEmail = er.data.error.email[0];
            } else {
                $scope.errorEmail = null;
            }
            if ($scope.hasErrorPassword = !!er.data.error.password) {
                $scope.errorPassword = er.data.error.password[0];
            } else {
                $scope.errorPassword = null;
            }
            if ($scope.hasErrorRepassword = !!er.data.error.repassword) {
                $scope.errorRepassword = er.data.error.repassword[0];
            } else {
                $scope.errorRepassword = null;
            }
            if ($scope.hasErrorPhone = !!er.data.error.phone) {
                $scope.errorPhone = er.data.error.phone[0];
            } else {
                $scope.errorPhone = null;
            }
            if ($scope.hasErrorAddress = !!er.data.error.address) {
                $scope.errorAddress = er.data.error.address[0];
            } else {
                $scope.errorAddress = null;
            }
            if ($scope.hasErrorGender = !!er.data.error.gender) {
                $scope.errorGender = er.data.error.gender[0];
            } else {
                $scope.errorGender = null;
            }
        });
    };
});

App
.controller('UserListCtrl', 
    function($scope, DTOptionsBuilder, DTColumnBuilder, $compile, API, $state) {

        var fnRowCallback = (nRow, aData, iDisplayIndex, iDisplayIndexFull) => {
            $compile(nRow)($scope);
        }

        var actionsHtml = (data) => {
            return `
            <div class="btn-group">
                <a class="btn btn-info" href="">
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
            .then(function(d){
                return d.data;
            })
            .finally(function() {
                cfpLoadingBar.complete();
            });
        })
        .withDataProp('data')
        .withOption('fnRowCallback', fnRowCallback)
        .withBootstrap();

        $scope.dtColumns = [
        DTColumnBuilder.newColumn('id').withTitle('ID').notSortable(),
        DTColumnBuilder.newColumn('name').withTitle('Name'),
        DTColumnBuilder.newColumn('email').withTitle('Email'),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable().renderWith(actionsHtml)
        ];

        $scope.delete = function(id){
            var user = apiService.serviceAdmin('user',id);
            user.remove()
            .then(function(response){
                console.log(response.message)
                $state.reload();
            })
        }

    }

});
App
.controller('CategoryListCtrl', function($scope, API, $stateParams, $state) {
    var categories = API.serviceAdmin('category');
    categories.getList()
    .then(function(response) {
        $scope.categories = response.categories;
    });

    $scope.remove = function($stateParams) {
        var category = API.serviceAdmin('category', $stateParams.id);
        category.remove()
        .then(function(response) {
            alert(response.message);
            $state.reload();
        })
    }
});

App
.controller('CategoryCreateCtrl', function($scope, $state, $stateParams, API) {
    var categories = API.serviceAdmin('category').getList();
    categories.then(function(response) {
        console.log(response.categories);
        $scope.categories = response.categories;
    });

    $scope.save = function($stateParams) {
        console.log($stateParams);
        var category = API.serviceAdmin('category', null, $stateParams.data);
        category.post()
        .then(function(response) {
            alert(response.message);
            $state.reload();
        })
        .catch(function(response) {
            if ($scope.hasErrorName = !!response.error.name) {
                $scope.errorName = response.error.name[0];
            } else {
                $scope.errorName = null;
            }
            if ($scope.hasErrorParent = !!response.error.parent) {
                $scope.errorParent = response.error.parent[0];
            } else {
                $scope.errorParent = null;
            }
        })
    }

    $scope.click = function($stateParams) {
        console.log($stateParams);
        $scope.category = $stateParams.cate.parent_id
        console.log($scope.category)
    }
});

App
.controller('CategoryEditCtrl', function($scope, $stateParams, API) {
    var name = $stateParams.name;
    var categories = API.serviceAdmin('category');
    categories.getList()
    .then(function(response) {
        $scope.categories = response.categories;
    });

    var category = API.serviceAdmin('category', name);
    category.getOne()
    .then(function(response) {
        $scope.category = response.category;
    });

    $scope.save = function($stateParams) {
        var category = API.serviceAdmin('category',name,$stateParams.data);
        category.put()
        .then(function(response) {
            alert(response.message);
        })
        .catch(function(response) {
            if ($scope.hasErrorName = !!response.error.name) {
                $scope.errorName = response.error.name[0];
            } else {
                $scope.errorName = null;
            }
            if ($scope.hasErrorParent = !!response.error.parent) {
                $scope.errorParent = response.error.parent[0];
            } else {
                $scope.errorParent = null;
            }
        })
    }
});
