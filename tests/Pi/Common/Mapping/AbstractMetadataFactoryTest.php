<?hh

use Mocks\MongoOdmConfiguration,
    Mocks\MockOdmConfiguration,
    Mocks\MockContainer,
    Mocks\MockEntity,
    Mocks\MockHydratorFactory,
    Mocks\MockMetadataFactory,
    Mocks\MockMappingDriver,
    Pi\PhpUnitUtils,
    Pi\Cache\InMemoryCacheProvider,
    Pi\Redis\RedisHydratorFactory,
    Pi\Odm\UnitWork,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\IEntityMetaDataFactory,
    Pi\Common\ClassUtils,
    Pi\EventManager,
    Pi\MongoConnection,
    Pi\Odm\Mapping\Driver\AttributeDriver,
    Pi\Odm\EntityMetaDataFactory;


class AbstractMetadataFactoryTestDto {

}
class AbstractMetadataFactoryTest extends \PHPUnit_Framework_TestCase {
  
  protected EventManager $em;

  protected InMemoryCacheProvider $cache;

  protected MockMetadataFactory $metadataFactory;

  public function setUp()
  {
    MockContainer::init();
    $this->em = MockContainer::$eventManager;
    $this->cache = MockContainer::$cache;
    $this->metadataFactory = MockContainer::$metadataFactory;
  }

  public function testCanLoadMetadataAndCache()
  {
    $this->assertFalse($this->metadataFactory->isCached(AbstractMetadataFactoryTestDto::class));
    $this->metadataFactory->getMetadataFor(AbstractMetadataFactoryTestDto::class);
    $this->assertTrue($this->metadataFactory->isCached(AbstractMetadataFactoryTestDto::class));
  }

}