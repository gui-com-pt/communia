<?hh

namespace Pi;

use Pi\Interfaces\IContainer,
	Pi\Interfaces\ICacheProvider,
	Pi\StaticContainer;




/**
 * Container Factory
 * Create a new Container
 */
class ContainerFactory {

	public function __construct()
	{

	}

	
	public function createContainer() : IContainer
	{
    	return new StaticContainer();
  	}
}
