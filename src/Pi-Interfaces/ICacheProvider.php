<?hh

namespace Pi\Interfaces;




/**
 * Cache Provider allows data to be saved and access in a fast way
 * Cache persistent is imediatlly, transactions will 
 * be implemented in the future
 */
interface ICacheProvider {

  /**
   * Get the value of the given key
   * @param  string $key the key
   * @return ?string      the value or null if not exists
   */
  public function get($key = null) : ?mixed; 

  /**
   * Set the value of the given key
   * @param string $key   the key
   * @param scalar $value the value
   * @return void
   */
  public function set($key, $value) : void;

  /**
   * Get the array of the given key
   * @param string $key the array key
   * @return ?array array or null if not exists
   */
  public function getArray(string $key) : ?array;

  /**
   * Push the value to the given key
   * If key not exists, create a new array
   * @param string $key the array key
   * @return void
   */
  public function push(string $key, string $value) : void;

  /**
   * Push the object to the given key
   * If the key not exists, create a new array
   * @param string $key the array key
   * @param mixed $obj the object
   * @return void
   */
  public function pushObject(string $key, mixed $obj) : void;

  /**
   * Get the object for the given key
   * @param string $key the array key
   * @return ?mixed the object or null if not exists
   */
  public function getAs(string $key, string $className) : ?mixed;

  /**
   * Get the map for the given key
   * @param string $key the array key
   * @return ?Map<string,string> the map, if not exists null
   */
  public function getMap(string $key) : ?Map<string,string>;

  /**
   * Push the given key and value to array key
   * @param string $key the array key
   * @param string $mapKey the map key
   * @param string $mapValue the map value
   * @return void
   */
  public function pushMap(string $key, string $mapKey, string $mapValue) : void;

  /**
   * Indicates if the given key exists
   * @param string $key the key name
   * @return bool true if the key exits
   */
  public function contains($key) : bool;
}
