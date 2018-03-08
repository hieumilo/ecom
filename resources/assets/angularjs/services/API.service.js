function API($http, API_URL, $q, $cookies) {

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

    function post(url, data) {
        var deferred = $q.defer();
        $http.post(url,data)
        .success(function (data) {
            deferred.resolve(data);
        }).error(function (data, status) {
            deferred.reject(data);
        });

        return deferred.promise;
    }

    function postWithFile(url, data) {
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

    function put(url, data) {
        var deferred = $q.defer();
        $http.put(url,data)
        .success(function (data) {
            deferred.resolve(data);
        }).error(function (data, status) {
            deferred.reject(data);
        });

        return deferred.promise;
    }

    function remove(url) {
        var deferred = $q.defer();
        $http.delete(url)
        .success(function (data) {
            deferred.resolve(data);
        }).error(function (data, status) {
            deferred.reject(data);
        });

        return deferred.promise;
    }

    var array = [];
    function formatCategory(categories) {
        angular.forEach(categories, function(value) {
            array.push(value)
            if (value.categories && value.categories.length > 0) {
                formatCategory(value.categories);
            }
        })

        return array;
    }

    return {
        formatCategory: function(categories) {
            array = [];

            return formatCategory(categories);
        },
        serviceAdmin: function(name,id = null, data = null) {
            return {
                getList: function() {
                    return get(API_URL + 'admin/' + name);
                },
                getOne: function() {
                    return get(API_URL + 'admin/' + name + '/' + id);
                },
                post: function() {
                    return post(API_URL + 'admin/' + name, data);
                },
                postWithFile: function() {
                    if (id) {
                        return postWithFile(API_URL + 'admin/' + name + '/' + id, data);
                    }
                    return postWithFile(API_URL + 'admin/' + name, data);
                },
                put: function() {
                    return put(API_URL + 'admin/' + name + '/' + id, data);
                },
                remove: function() {
                    return remove(API_URL + 'admin/' + name + '/' + id);
                },
                export: function() {
                    var token = window.localStorage.getItem('satellizer_token')
                    return window.location.href = API_URL + 'admin/export?token=' + token;
                }
            }
        }, service: function(name = null, option = null, data = null) {
            return {
                getNewProducts: function() {
                    return get(API_URL + 'products-new');
                },
                getListCategory: function() {
                    return get(API_URL + 'category');
                },
                getListProduct: function() {
                    var url = API_URL + name;
                    if(option != null) {
                        url += "?";
                        angular.forEach(option, function(value,key) {
                            if (value != '') {
                                url += key + "=" + value + "&";
                            }
                        })
                    }
                    return get(url);
                },
                getProductRecently: function() {
                    return get(API_URL + 'products-recently?id=' + JSON.parse($cookies.get('rid')));
                },
                getOne: function() {
                    return get(API_URL + 'product/' + name);
                },
                post: function() {
                    return post(API_URL + name, data);
                },
                postWithFile: function() {
                    return postWithFile(API_URL + name, data);
                },
                getOrderDetail: function() {
                    return get(API_URL + 'order/' + name);
                },
                getComment: function() {
                    return get(API_URL + 'comment/' + name);
                },
                postComment: function() {
                    return post(API_URL + 'comment/' + name, data);
                },
                putComment: function() {
                    return put(API_URL + 'comment/' + name, data);
                },
                deleteComment: function() {
                    return remove(API_URL + 'comment/' + name);
                },
            }
        }
    };
};
