<?php namespace Andrew13\Laravel4FileUpload;

use Illuminate\Support\ServiceProvider;

class Laravel4FileUploadServiceProvider extends ServiceProvider {

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
        $this->package('andrew13\laravel-4-file-upload');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('l4fu', function($app)
        {
            return new Laravel4FileUpload($app);
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('l4fu');
	}

}