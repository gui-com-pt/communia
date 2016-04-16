<?hh

namespace Pi\Interfaces;





interface IMappingDriver {
  
    public function loadMetadataForClass(string $className, DtoMetadataInterface $entity);

  //public static function create($paths = array());
}
