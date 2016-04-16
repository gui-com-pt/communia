<?hh

namespace Pi\Interfaces;

interface IEntityMetaDataFactory {
	
	 public function getMetadataFor(string $className);

	 public function setMetadataFor($className, $class);
}