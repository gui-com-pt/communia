<?hh

namespace Pi;

use Pi\AbstractAppSettingsProvider,
	Pi\Interfaces\IContainable,
	Pi\Interfaces\AppSettingsProviderInterface;




class ApcAppSettingsProvider extends AbstractAppSettingsProvider implements AppSettingsProviderInterface {
	
	protected $settings;

	const string CACHE_PREFIX = 'settings::';

	public function __construct()
	{
		
	}
	public function getAll() : Map<string,string>
	{
		throw new \Exception('Not Implemented');
	}

	public function getAllKeys() : Set<string>
	{
		throw new \Exception('Not Implemented');
	}

	public function exists(string $key) : bool
	{
		return apc_exists(self::CACHE_PREFIX.$key);
	}

	public function getString(string $name) : string
	{
		return apc_fetch(self::CACHE_PREFIX.$name);
	}

	public function getList(string $key) : Set<string>
	{
		return apc_fetch(self::CACHE_PREFIX.$key) ?: new Set<string>();
	}

	public function getMap(string $key) : Map<string,string>
	{	
		return apc_fetch(self::CACHE_PREFIX.$key) ?: new Map<string,string>();
	}

	public function setString(string $name, string $value) : void	
	{
		apc_store(self::CACHE_PREFIX.$name, $value);
	}

	public function set(string $key, mixed $value) : void
	{
		apc_store(self::CACHE_PREFIX.$key, $value);
	}

	public function addToList(string $key, string $value)
	{
		$list = $this->getList($key);
		$list->add($value);
		$this->set($key, $list);
	}

	public function addToMap(string $key, string $mapKey, string $mapValue) : void
	{
		$map = $this->getMap($key);
		$map->add(Pair{$mapKey, $mapValue});
		$this->set($key, $map);
	}
}