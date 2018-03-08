function dynFbCommentBox($window, $rootScope) {
    function createHTML(href, numposts, colorscheme) {
        return '<div class="fb-comments" ' +
           'data-href="' + href + '" ' +
           'data-numposts="' + numposts + '" ' +
           'data-colorsheme="' + colorscheme + '">' +
       '</div>';
    }

    return {
        restrict: 'A',
        scope: {},
        link: function (scope, elem, attrs) {
            if (!$window.FB) {
                $.getScript('//connect.facebook.net/en_US/sdk.js', function () {
                    $window.FB.init({
                        appId: $rootScope.facebookAppId,
                        status: true, 
                        cookie: true, 
                        xfbml: true,
                        version: 'v2.4'
                    });
                    postLink(scope, elem, attrs);
                });
            } else {
                postLink(scope, elem, attrs);
            }

            function postLink(scope, elem, attrs) {
                attrs.$observe('pageHref', function (newValue) {
                    var href = newValue;
                    var numposts = attrs.numposts || 5;
                    var colorscheme = attrs.colorscheme || 'light';
                    elem.html(createHTML(href, numposts, colorscheme));
                    FB.XFBML.parse(elem[0]);
                });
            }
        }
    };
}
