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
