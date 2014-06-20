<?php 

namespace Elvendor\Imgjss;

use Illuminate\Support\Facades\Facade;

class ImgjssFacade extends Facade
{

	protected static function getFacadeAccessor() { return 'imgjss'; }

}
