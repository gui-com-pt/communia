<?hh

namespace Pi\Common\Mapping;

use Pi\Interfaces\IContainer,
    Pi\Interfaces\IContainable,
    Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\DtoMetadataInterface,
    Pi\EventManager,
    Pi\Interfaces\IMappingDriver,
    Pi\Interfaces\IEntityMetaDataFactory,
    Pi\Events;

abstract class AbstractMetadataFactory implements IEntityMetaDataFactory, IContainable {

  private $documentManager;

  private $entityMeta;

  private $loadedMetadata;

  private $initialized = false;

  const CACHE_KEY = 'metadata::';

  public function __construct(
    protected ICacheProvider $cacheProvider,
    protected EventManager $eventManager,
      protected IMappingDriver $mappingDriver)
  {
    $this->loadedMetadata = Map{};
  }

  public abstract function newEntityMetadataInstance(string $documentName);
  
  public function ioc(IContainer $container)
  {

    //$this->eventManager = $container->get('EventManager');
    //$this->mappingDriver = $container->get('IMappingDriver');
  }


  /**
   * Gets the class metadata descriptor for a class.
   *
   * @param string $className The name of the class.
   *
   * @return EntityMetadata
   *
   * @throws ReflectionException
   * @throws MappingException
   */
  public function getMetadataFor(string $className)
  {
    if($this->loadedMetadata->contains($className)){
      return $this->loadedMetadata->get($className);
    }

    return $this->loadFromCached($className) ?: $this->loadMetadata($className);
  }


  public function isCached(string $name) : bool
  {
    return $this->cacheProvider->contains(self::CACHE_KEY . $name);
  }

  public function initialize()
  {

  }

  public function loadFromCached(string $name) : ?mixed
  {
    return $this->cacheProvider->getObject(self::CACHE_KEY . $name);
  }

  protected function cache(DtoMetadataInterface $class)
  {
    try {
      $this->cacheProvider->set(self::CACHE_KEY . $class->getName(), $class);  
    }
    catch(\Exception $ex) {
      die('error with '.get_class($class).$ex->getMessage());
    }
    
  }

  public function loadMetadata(string $name)
  {
    if ( ! $this->initialized) {
        $this->initialize();
    }

    $loaded = array();

    $visited = array();

    $className = $name;

    $class = $this->newEntityMetadataInstance($className);


    $this->doLoadMetadata($class);
    $this->setMetadataFor($className, $class);

    $loaded[] = $className;
    
    $this->cache($class);

    return $class;
  }

  protected function getFqcnFromAlias($namespaceAlias, $simpleClassName)
    {
        //return $this->config->getDocumentNamespace($namespaceAlias) . '\\' . $simpleClassName;
    }
  /**
   * Checks whether the factory has the metadata for a class loaded already.
   *
   * @param string $className
   *
   * @return boolean TRUE if the metadata of the class in question is already loaded, FALSE otherwise.
   */
  public function hasMetadataFor($className)
  {
      return isset($this->loadedMetadata[$className]);
  }

  /**
   * Sets the metadata descriptor for a specific class.
   *
   * NOTE: This is only useful in very special cases, like when generating proxy classes.
   *
   * @param string        $className
   * @param ClassMetadata $class
   *
   * @return void
   */
  public function setMetadataFor($className, $class)
  {
      $this->loadedMetadata[$className] = $class;
  }

  public function doLoadMetadata(DtoMetadataInterface $class)
  {

    try {
      $this->mappingDriver->loadMetadataForClass($class->getName(), $class);
    }
    catch(\Exception $ex){
      throw $ex;
    }
    if($this->eventManager->has(Events::LoadClassMetadata)){
      $args = new LoadClassMetadataEventArgs($class, $this->documentManager);
      $this->eventManager->dispatch(Events::LoadClassMetadata, $args);
    }
  }
}