function fbLike($window, $rootScope) {
    return {
        restrict: 'A',
        scope: {
            fbLike: '=?'
        },
        link: function (scope, element, attrs) {
            if (!$window.FB) {
                $.getScript('//connect.facebook.net/en_US/sdk.js', function () {
                    $window.FB.init({
                        appId: $rootScope.facebookAppId,
                        status: true, 
                        cookie: true, 
                        xfbml: true,
                        version: 'v2.4'
                    });
                    renderLikeButton();
                });
            } else {
                renderLikeButton();
            }

            var watchAdded = false;
            function renderLikeButton() {
                if (!!attrs.fbLike && !scope.fbLike && !watchAdded) {
                    watchAdded = true;
                    var unbindWatch = scope.$watch('fbLike', function (newValue, oldValue) {
                        if (newValue) {
                            renderLikeButton();
                            unbindWatch();
                        }
                    });

                    return;
                } else {
                    element.html(
                        '<div class="fb-like"' + 
                        (!!scope.fbLike ? ' data-href="' + scope.fbLike + '"' : '') + 
                        ' data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>'
                    );
                    $window.FB.XFBML.parse(element.parent()[0]);
                }
            }
        }
    };
}
