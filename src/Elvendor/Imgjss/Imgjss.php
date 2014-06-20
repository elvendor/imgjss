<?php 

namespace Elvendor\Imgjss;

use Illuminate\Support\Facades\HTML,
	Illuminate\Support\Facades\Config;

class Imgjss
{

	/**
	 * Process given CSS path.
	 *
	 * @return string
	 */
	public function css($path, $attrs = [], $timestamp = null, $secure = null)
	{
		return HTML::style($this->process($path, '.css', $timestamp), $attrs, $secure);
	}

	/**
	 * Process given JS path.
	 *
	 * @return string
	 */
	public function js($path, $attrs = [], $timestamp = null, $secure = null)
	{
		return HTML::script($this->process($path, '.js', $timestamp), $attrs, $secure);
	}

	/**
	 * Process given image path.
	 *
	 * @return string
	 */
	public function img($path, $attrs = [], $timestamp = null, $secure = null)
	{
		// Here is a little trick: 
		// by default in Laravel's HTML::image we pass 'alt' attribute as second param 
		// and other attributes as third. A little weird, right? Let's fix that!
		return HTML::image($this->process($path, null, $timestamp), @$attrs['alt'] ? $attrs['alt'] : null, $attrs, $secure);
	}

	/**
	 * Main method, that returns only file path 
	 * & optionally query string 
	 * with last modified timestamp of the given file.
	 * External paths are returned without timestamp append 
	 * but with css & js extension autocomplete.
	 *
	 * @return string
	 */
	public function process($path, $ext = null, $timestamp = null)
	{
	    
	    // Get all package configs
		$config = Config::get('imgjss::config');

		// We don't need appending the extension if we already have one
		if($ext !== null && $ext !== strrchr($path, "."))
		{
			$path .= $ext;
		}

		// Make sure file is not external
		if(stristr($path, 'http') === false)
		{

			// Get the full path to the asset.
			$absolutePath = public_path($path);

			// We return the result even if the file wasn't found but without timestamp
			if (file_exists($absolutePath))
			{
				
				// Make sure that we need to generate timestamp
				// First we check if true passed, if not we get the default behavior from the package config
				$timestamp = $timestamp !== null ? $timestamp : $config['timestamp'];

				// Append the last modified timestamp of the file or just return the path
				$path = $timestamp ? $path . $config['qstring'] . filemtime($absolutePath) : $path;
			}
		}

		// Returns external paths or the local image path in case
		// if there is need to generate the last modified timestamp
		return $path;

	}

}
