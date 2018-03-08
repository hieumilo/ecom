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

        me: function() {
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

        profile: function() {
            var deferred = $q.defer();
            $http.get(API_URL + 'auth/profile')
            .success(function (data) {
                deferred.resolve(data);
            })
            .error(function (error) {
                deferred.reject(error);
            });
            
            return deferred.promise;
        },

        updateProfile: function(data) {
            var deferred = $q.defer();
            $http({
                method: 'POST',
                url: API_URL + 'auth/me',
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
        },

        getCurrentUser: function() {
            return angular.fromJson(localStorage.getItem("User"));
        },
    };
};
