<?hh

namespace Test\Cache;

use Pi\Cache\ApcCacheProvider,
	Pi\Common\RandomString;


/**
 * DTO class to save in APC
 */
class ApcCacheProviderTestDto {

	public string $name = 'asd';
}
class ApcCacheProviderTest extends \PHPUnit_Framework_TestCase {
	
	protected ApcCacheProvider $cache;

	public function setUp()
	{
		$this->cache = new ApcCacheProvider();
		if(!$this->cache->enabled) {
			throw new \Exception('To execute Apc tests you must enable it first');
		}
	}

	/**
	 * Get As test
	 * APC will handle objects serialization by default
	 */
	public function testCanGetAs()
	{
		$key = RandomString::generate();
		$randName = RandomString::generate();
		$obj = new ApcCacheProviderTestDto();
		$obj->name = $randName;

		$this->cache->set($key, $obj);
		$cached = $this->cache->getAs($key, ApcCacheProviderTestDto::class);
		$this->assertTrue($cached instanceof ApcCacheProviderTestDto);
		$this->assertEquals($cached->name, $randName);
	}

	public function testCanSetAndGetAString()
	{
		$key = RandomString::generate();
		$val = RandomString::generate();
		$this->cache->set($key, $val);
		$cached = $this->cache->get($key);
		$this->assertEquals($val, $cached);
	}

	public function testCanSetAndGetAArray()
	{
		$val = array('first', 'second');
		$key = RandomString::generate();
		$this->cache->set($key, $val);

		$arr = $this->cache->get($key);
		$this->assertArray($arr, 2);
		$arr = $this->cache->getArray($key);
		$this->assertArray($arr, 2);
	}

	public function testCanSetAndPushAArrayStringAndObject()
	{
		$val = array('first', 'second');
		$key = RandomString::generate();
		$this->cache->set($key, $val);
		$newVal = RandomString::generate();
		$this->cache->push($key, $newVal);

		$arr = $this->cache->get($key);
		$this->assertArray($arr, 3);
		$arr = $this->cache->getArray($key);
		$this->assertArray($arr, 3);

		$this->cache->pushObject($key, new ApcCacheProviderTestDto());
		$arr = $this->cache->get($key);
		$this->assertArray($arr, 4);
		$this->assertEquals($arr[3]->name, 'asd');
	}

	public function testCanRemoveAndCheckIfCacheContains()
	{
		$key = RandomString::generate();
		$val = RandomString::generate();
		$this->cache->set($key, $val);
		$this->assertTrue($this->cache->contains($key));

		$this->cache->delete($key);
		$this->assertFalse($this->cache->contains($key));
	}

	public function testCanSetGetAndPushToMap()
	{
		$key = RandomString::generate();
		$map = new Map<string,string>();
		$map->add(Pair{'k', 'v'});
		$this->cache->set($key, $map);

		$map = $this->cache->getMap($key);
		//$this->assertTrue($map instanceof \Map);
		$this->assertEquals($map->get('k'), 'v');

		$this->cache->pushMap($key, 'l', 'm');
		$map = $this->cache->getMap($key);
		$this->assertEquals($map->get('l'), 'm');
	}

	protected function assertArray($arr, $count = 2) 
	{
		$this->assertTrue(is_array($arr) && count($arr) === $count);	
	}
}