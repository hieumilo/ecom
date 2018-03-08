function TranslateConfig($translateProvider) {
    $translateProvider.translations('en', translationsEN);
    $translateProvider.preferredLanguage('en');
    $translateProvider.fallbackLanguage('en');
}
