<?hh

namespace Test;

use Pi\ApcAppSettingsProvider,
	Pi\Common\RandomString;




class ApcAppSettingsProviderTest extends \PHPUnit_Framework_TestCase {
	
	protected $provider;

	public function setUp()
	{
		$this->provider = new ApcAppSettingsProvider();;
	}

	public function testCanGetAndSetString()
	{
		$key = RandomString::generate();
		$this->provider->setString($key, 'new-val');
		$val = $this->provider->getString($key);
		$this->assertEquals($val, 'new-val');
	}

	public function testCanAddAndGetLists()
	{
		$key = RandomString::generate();

		$provider = $this->provider;
		$add = function() use($key) {
			$k = RandomString::generate(3);
			$this->provider->addToList($key, $k);
			$map = $this->provider->getList($key);
			$this->assertTrue($map->contains($k));	
		};

		for ($i=0; $i < 2; $i++) { 
			$add();
		}
	}

	public function testCanAddAndGetMaps()
	{
		$key = RandomString::generate();

		$provider = $this->provider;
		$add = function() use($key) {
			$k = RandomString::generate(3);
			$v = RandomString::generate(3);
			$this->provider->addToMap($key, $k, $v);
			$map = $this->provider->getMap($key);
			$this->assertEquals($map->get($k), $v);	
		};

		for ($i=0; $i < 2; $i++) { 
			$add();
		}
	}
}