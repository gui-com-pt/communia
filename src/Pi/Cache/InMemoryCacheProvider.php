<?hh

namespace Pi\Cache;

use Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\IContainer;




/**
 * In Memory Cache Provider
 * This implementation dont persist the cache data
 * It's used only for tests
 */
class InMemoryCacheProvider implements ICacheProvider {

  protected $config;

  public function __construct()
  {
    $this->config = new \StdClass;
  }

  public function init()
  {
   
  }

  public function get($key = null)
  {
    if(is_null($key))
      return $this->config;

    return array_key_exists($key, $this->config) ? $this->config->$key : null;
  }

  public function getAs(string $key, string $className) : ?mixed
  {
    return $this->get($key);
  }

  public function getArray(string $key) : ?array
  {
    return $this->get($key);
  }

  public function getMap(string $key) : ?Map<string,string>
  {
    return $this->get($key);
  }

  public function getObject(string $key) : ?mixed
  {
    return $this->get($key);
  }

  public function set($key, $value, $persist = true)
  {
    try {
      $this->config->$key = $value;
    }
    catch(\Exception $ex) {
      throw new \Exception(
        sprintf('Error while writting a new value in local cache provider: %s', $ex->getMessage())
      );
    }

  }

  public function push(string $key, string $value) : void
  {
    if(!array_key_exists($key, $this->config)) {
      $this->config->$key = Set{};
    }
    $this->config->$key->add($value);
  }

  public function pushObject(string $key, mixed $obj) : void
  {
    if(!property_exists($this->config, $key)) {
      $this->config->$key->add($obj);
    }
    throw new \Exception('NotImplemented');      
  }

  public function pushMap(string $key, string $mapKey, string $mapValue) : void
  {
    throw new \Exception('NotImplemented');      
  }

  public function contains($key) : bool
  {
    return $this->get($key) !== null;
  }
}
