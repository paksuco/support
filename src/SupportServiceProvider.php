<?php

namespace Paksuco\Support;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->handleConfigs();
        $this->handleMigrations();
        $this->handleViews();
        $this->handleTranslations();
        $this->handleRoutes();
        $this->handleResources();

        Event::listen("paksuco.menu.beforeRender", function ($key, $container) {
            if ($key == "admin") {
                $container->addItem("Support", "#", "fas fa-life-ring", function ($menu) {
                    $menu->addItem("Support Tickets", "#", "fas fa-envelope-open-text")
                        ->addItem("Frequently Asked Questions", route("paksuco.faq.index"), "fas fa-question")
                        ->addItem("FAQ Categories", route("paksuco.faqcategory.index"), "fas fa-question-circle");
                }, config("permission-ui.menu_priority", 30));
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind any implementations.
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function handleConfigs()
    {
        $configPath = __DIR__ . '/../config/support-ui.php';

        $this->publishes([
            $configPath =>
            base_path('config/support-ui.php'),
        ], "config");

        $this->mergeConfigFrom($configPath, 'support-ui');
    }

    private function handleTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'support-ui');
    }

    private function handleViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'support-ui');

        $this->publishes([
            __DIR__ . '/../views' =>
            base_path('resources/views/vendor/support-ui'),
        ], "views");
    }

    private function handleResources()
    {
        $this->publishes([
            __DIR__ . '/../resources/js/tinymce' =>
            base_path('public/assets/vendor/tinymce'),
        ], "pages-tinymce");
    }

    private function handleMigrations()
    {
        $this->publishes([
            __DIR__ . '/../migrations' =>
            base_path('database/migrations'),
        ], "migrations");
    }

    private function handleRoutes()
    {
        include __DIR__ . '/../routes/routes.php';
    }
}
