<?hh

namespace Pi\Cache;

use Pi\Interfaces\ICacheProvider,
	Pi\Interfaces\IContainable,
	Pi\Interfaces\IContainer,
	Pi\Interfaces\InitializeInterface,
	Pi\Interfaces\HydratorFactoryInterface,
	Pi\Host\HostProvider,
	Pi\Common\RandomString;



/**
 * Cache implementation for Memcached
 */
class MemcachedProvider implements ICacheProvider, InitializeInterface {

	protected \Memcached $mem;

	public function __construct(
		protected HydratorFactoryInterface $hydratorFactory,
		protected Map<string,int> $instances
	)
	{
	
	}

	public function init() : void
	{
		$this->mem = new \Memcached();
		foreach ($this->instances as $port => $host) {
			$this->mem->addServer($host, $port);
		}
	}

	public function get($key = null) : ?mixed
	{
		return $this->mem->get($key);
	}

  public function getAs(string $key, string $className) : ?mixed
  {
    return $this->get($key);
  }
	
   /**
	* Set the value of the given key
	* @param string $key   the key
	* @param scalar $value the value
	* @return void
	*/
	public function set($key, $value) : void
	{
		$this->mem->set($key, $value);
	}

	/**
    * Get the array of the given key
    * @param string $key the array key
    * @return ?array array or null if not exists
    */
	public function getArray(string $key) : ?array
	{
		return (array)$this->get($key);
	}

 /**
  * Push the value to the given key
  * If key not exists, create a new array
  * @param string $key the array key
  * @return void
  */
  public function push(string $key, string $value) : void
  {
  	$list = $this->get($key);
		if($list == null) {
			$list = array();
		}
		array_push($list, $value);
		$this->set($key, $value);
  }

  /**
   * Push the object to the given key
   * If the key not exists, create a new array
   * @param string $key the array key
   * @param mixed $obj the object
   * @return void
   */
  public function pushObject(string $key, mixed $obj) : void
	{

	}

	/**
 * Get the object for the given key
   * @param string $key the array key
   * @return ?mixed the object or null if not exists
   */
  public function getObject(string $key) : ?mixed
  {
  	return $this->get($key);
  }

  /**
   * Get the map for the given key
   * @param string $key the array key
   * @return ?Map<string,string> the map, if not exists null
   */
  public function getMap(string $key) : ?Map<string,string>
  {
  	$map = $this->get($key);
  	return unserialize($map);
  }

  /**
   * Push the given key and value to array key
   * @param string $key the array key
   * @param string $mapKey the map key
   * @param string $mapValue the map value
   * @return void
   */
  public function pushMap(string $key, string $mapKey, string $mapValue) : void
  {
  	$map = $this->get($key);
  	if($map == $null) {
  		$map = Map{};
  	}
  	$map->add(Pair{$mapKey, $mapValue});
    $this->set($key, serialize($map));
  }

/**
   * Indicates if the given key exists
   * @param string $key the key name
   * @return bool true if the key exits
   */
	public function contains($key) : bool
	{
		return $this->mem->get($key) != null;
	}
}