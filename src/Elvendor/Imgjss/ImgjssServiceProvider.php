<?php namespace Elvendor\Imgjss;

use Illuminate\Support\ServiceProvider;
use	Illuminate\Foundation\AliasLoader;

class ImgjssServiceProvider extends ServiceProvider {

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
		$this->package('elvendor/imgjss');

		// Make an alias for the Facade class, so we don't need to add it in main config file
		$this->app->booting(function()
		{
			AliasLoader::getInstance()->alias('Imgjss', 'Elvendor\Imgjss\ImgjssFacade');
		});

		// Get the blade compiler instance
		$blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

		/**
		 * Here comes Blade syntax extending
		 * We simply call for the existing Imgjss methods and echo out the results
		 */
		$blade->extend(function($value)
		{
			return preg_replace("/@css(.*)/", "<?php echo Imgjss::css$1;?>", $value);
		});

		$blade->extend(function($value)
		{
			return preg_replace("/@js(.*)/", "<?php echo Imgjss::js$1;?>", $value);
		});

		$blade->extend(function($value)
		{
			return preg_replace("/@img(.*)/", "<?php echo Imgjss::img$1;?>", $value);
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['imgjss'] = $this->app->share(function($app)
		{
			return new Imgjss;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('imgjss');
	}

}
