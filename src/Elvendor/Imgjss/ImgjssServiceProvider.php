<?php namespace Elvendor\Imgjss;

use Illuminate\Support\ServiceProvider,
	Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImgjssServiceProvider extends ServiceProvider
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
		$this->package('elvendor/imgjss');

		$app 	= $this->app;
		$blade 	= $app['blade.compiler'];
		$html  	= $app['html'];

		$html->macro('css', function($path, $attrs = [], $timestamp = null, $secure = null) use($app)
		{    	
			return $app['html']->style($this->process($path, '.css', $timestamp), $attrs, $secure);
		});

		$html->macro('js', function($path, $attrs = [], $timestamp = null, $secure = null) use($app)
		{    	
			return $app['html']->script($this->process($path, '.js', $timestamp), $attrs, $secure);
		});

		$html->macro('img', function($path, $attrs = [], $timestamp = null, $secure = null) use($app)
		{   
			return $app['html']->image($this->process($path, null, $timestamp), @$attrs['alt'] ? $attrs['alt'] : null, $attrs, $secure);
		});

		$blade->extend(function($view) use($html)
		{
			return preg_replace("/@css(.*)/", "<?php echo HTML::css$1;?>", $view);
		});

		$blade->extend(function($view) use($html)
		{
			return preg_replace("/@js(.*)/", "<?php echo HTML::js$1;?>", $view);
		});

		$blade->extend(function($view) use($html)
		{
			return preg_replace("/@img(.*)/", "<?php echo HTML::img$1 . '\r\n';?>", $view);
		});

	}

	public function process($path, $ext = null, $timestamp = null)
	{
	    
		$app = $this->app;
		$config = $app['config']->get('imgjss::config');

		if($ext !== null && $ext !== strrchr($path, "."))
		{
			$path .= $ext;
		}
			
		// Get the full path to the asset.
		$absolutePath = public_path($path);

		if (!file_exists($absolutePath))
		{
			throw new NotFoundHttpException('Asset not found: ' . $path);
		}

		$timestamp = !is_null($timestamp) ? $timestamp : $config['timestamp'];

		return $timestamp ? $path . $config['qstring'] . filemtime($absolutePath) : $path;

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
