<?hh

namespace Pi\Cache;

use Pi\Interfaces\ICacheProvider;




class ApcCacheProvider implements ICacheProvider {
	
	const CACHE_PREFIX = 'cache::';

	public bool $enabled = false;

	public function __construct()
	{
		$this->enabled = extension_loaded('apc');
	}

	public function delete(string $key)
	{
		apc_delete(self::CACHE_PREFIX.$key);
	}

	/**
	 * Get the value of the given key
	 * @param  string $key the key
 	 * @return ?string      the value or null if not exists
	 */
	public function get($key = null) : ?mixed
	{
		return apc_fetch(self::CACHE_PREFIX.$key);
	}

	/**
	 * Set the value of the given key
	 * @param string $key   the key
	 * @param scalar $value the value
	 * @return void
	 */
	public function set($key, $value) : void
	{
		if(is_array($value)) { // http://php.net/manual/en/function.apc-store.php#73560
			//$value = new \ArrayObject($value);
		}
		apc_store(self::CACHE_PREFIX.$key, $value);
	}

	/**
	 * Get the array of the given key
	 * @param string $key the array key
	 * @return ?array array or null if not exists
	 */
	public function getArray(string $key) : ?array
	{
		$val = apc_fetch(self::CACHE_PREFIX.$key);
		return $val;
	}

	/**
	 * Push the value to the given key
	 * If key not exists, create a new array
	 * @param string $key the array key
	 * @return void
	 */
	public function push(string $key, string $value) : void
	{
		$val = apc_fetch(self::CACHE_PREFIX.$key);
		$val[]= $value;
		apc_store(self::CACHE_PREFIX.$key, $val);

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
		$val = apc_fetch(self::CACHE_PREFIX.$key);
		$val[]= $obj;
		apc_store(self::CACHE_PREFIX.$key, $val);
	}

	/**
	 * Get the object for the given key
	 * @param string $key the array key
	 * @return ?mixed the object or null if not exists
	 */
	public function getAs(string $key, string $className) : ?mixed
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
		return $this->get($key);
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
		$map = apc_fetch(self::CACHE_PREFIX.$key);
		$map->add(Pair{$mapKey, $mapValue});
		apc_store(self::CACHE_PREFIX.$key, $map);
	}

	/**
	 * Indicates if the given key exists
	 * @param string $key the key name
	 * @return bool true if the key exits
	 */
	public function contains($key) : bool
	{
		return apc_exists(self::CACHE_PREFIX.$key);
	}
}