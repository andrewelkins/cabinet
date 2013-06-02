<?php namespace Andrew13\Cabinet;

use Illuminate\Support\ServiceProvider;

class CabinetServiceProvider extends ServiceProvider {

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
        $this->package('andrew13/cabinet');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('cabinet', function($app)
        {
            return new Cabinet($app);
        });

        $this->registerCommands();
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('cabinet');
	}

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app['command.cabinet.controller'] = $this->app->share(function($app)
        {
            return new ControllerCommand($app);
        });

        $this->app['command.cabinet.routes'] = $this->app->share(function($app)
        {
            return new RoutesCommand($app);
        });

        $this->app['command.cabinet.migration'] = $this->app->share(function($app)
        {
            return new MigrationCommand($app);
        });

        $this->commands(
            'command.cabinet.controller',
            'command.cabinet.routes',
            'command.cabinet.migration'
        );
    }

}