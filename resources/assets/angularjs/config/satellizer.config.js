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
