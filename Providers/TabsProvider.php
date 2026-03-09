<?php

namespace Flute\Modules\Tabs\Providers;

use Flute\Core\Support\ModuleServiceProvider;

class TabsProvider extends ModuleServiceProvider
{
    public array $extensions = [];

    public function boot(\DI\Container $container): void
    {
        $this->bootstrapModule();

        $this->loadViews('Resources/views', 'tabs');
        $this->loadTranslations();
        $this->loadScss('Resources/assets/scss/tabs.scss');

        $jsFile = template()->getTemplateAssets()->assetFunction(
            path('app/Modules/Tabs/Resources/assets/js/tabs.js')
        );
        template()->prependToSection('footer', $jsFile);
    }

    public function register(\DI\Container $container): void {}
} 