<?hh

use Mocks\BibleHost,
    Mocks\MockContainer,
    Pi\Cache\MemcachedProvider,
    Pi\Interfaces\ICacheProvider,
    Pi\Common\RandomString;




class MemcachedProviderTestDto {

  public function __construct(
    protected string $name
  )
  {

  }

  public function name():  string
  {
    return $this->name;
  }
}
class MemcachedProviderTest extends \PHPUnit_Framework_TestCase {
  

  protected ICacheProvider $provider;

  public function setUp()
  {
    MockContainer::init();
    
    $this->provider = new MemcachedProvider(MockContainer::$hydratorFactory, new Map<string,int>(Pair{'localhost', 11211}));
    $this->provider->init();
  }

  public function testCanSetAndGetAnStringValue()
  {
    $key = RandomString::generate();
    $this->provider->set($key, 'a');
    $this->assertEquals($this->provider->get($key), 'a');

    $this->provider->set($key, 'b');
    $this->assertEquals($this->provider->get($key), 'b');
  }

  public function testCanSetAndGetAnObjectValue()
  {
    $key = RandomString::generate();
    $obj = new MemcachedProviderTestDto(RandomString::generate());
    $this->provider->set($key, $obj);
    $instance = $this->provider->getObject($key);
    $this->assertTrue($instance != null);
    $this->assertTrue($instance instanceof MemcachedProviderTestDto);
    $this->assertEquals($instance->name(), $obj->name());
  }

  public function testCanPushObjectAndGetArray()
  {
    $key = RandomString::generate();
    $value = new MemcachedProviderTestDto($key);
    $this->provider->pushObject($key, $value);
    $list = $this->provider->getArray($key);
    $this->assertTrue(get_class($list[0]) == get_class($value));
  }

}
