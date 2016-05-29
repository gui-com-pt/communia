<?hh

namespace Pi\Common\Mapping;

use Pi\Interfaces\DtoMetadataInterface,
	Pi\Interfaces\DtoMappingMetadataInterface;




abstract class AbstractMetadata implements DtoMetadataInterface{
	
	public \ReflectionClass $reflClass;

	public $reflFields;

	public string $id;

	/**
	 * The name of the document class
	 */
	public string $name;

	/**
	 * The class namespace
	 */
	public string $namespace;

	public string $identifier;

	public array $fieldMappings;

	protected bool $isFile = false;

	protected bool $multiTenantEnabled = false;

	protected string $multiTenantField;

	protected string $defaultDiscriminatorValue;

	protected string $discriminatorField;

  	protected string $inheritanceType;

	protected bool $isSuperclass = false;

	/**
	 * Whether this class describes the mapping of a embedded document.
	 */
	public bool $isEmbeddedDocument = false;

	public bool $embeddedDocument;

	public bool $reference = false;

	public Map<string,mixed> $opts;

	public function __construct(string $documentName)
	{
		$this->reflClass = new \ReflectionClass($documentName);
		$this->namespace = $this->reflClass->getNamespaceName();
		$this->name = $documentName;
		$this->fieldMappings = array();
		$this->opts = Map{};
	}
	
	/**
	 * Magic function for serialization
	 * Ignore \ReflectionClass as it can't be serialized
	 */
	public function __sleep()
	{
		return array('namespace', 'name', 'fieldMappings');
	}

	/**
	 * Initializes \ReflectionClass (name is already unserialized)
	 */
	public function __wakeup()
	{
		if($this->reflClass == null) {
			$this->reflClass = new \ReflectionClass($this->name);
		}
	}

	/**
	 * Indicates if this Metadata has a field mapped
	 * @param string $fieldName the field name
	 */
	public function hasField(string $fieldName) : bool
	{
		return $this->fieldsMapping->contains($fieldName);
	}

	/**
	 * Set the identifier for the Metadata
	 * @param string $name The identifier
	 */
	public function setId(string $name)
	{
		$this->id = $name;
		$this->identifier = $name;
	}

	public function getId()
	{
		return $this->identifier;
	}

	public function getIdentifierObject($document)
	{
		return $this->getDatabaseIdentifierValue($this->getIdentifierValue($document));
	}


	public function getPHPIdentifierValue($id = null)
	{
		return $this->getDatabaseIdentifierValue($id);
	}
	
	public function setMappedSuperclass(bool $value = true) : void
	{
		$this->isSuperclass = $value;
	}

	public function isMappedSuperclass() : bool
	{
		return $this->isSuperclass;
	}

	public function getDiscriminatorField() : ?string
	{
		return $this->discriminatorField;
	}

	public function getDefaultDiscriminatorValue() : ?string
	{
		return $this->discriminatorField;
	}

	public function setDiscriminator(string $field, ?string $inheritanceType = 'Single', ?string $defaultValue = null)
	{
		$this->discriminatorField = $field;
		$this->inheritanceType = $inheritanceType;
		if(!is_null($defaultValue)) {
			$this->defaultDiscriminatorValue = $defaultValue;
		}
	}

	public function getInheritanceType() : ?string
	{
		return $this->inheritanceType;
	}

	public function setInheritanceType(string $value) : void
	{
		$this->inheritanceType = $value;
	}

	public function setMultiTenant(bool $enabled)
	{
		$this->multiTenantEnabled = $enabled;
	}

	public function setMultiTenantField(string $fieldName) : void
	{
		$this->multiTenantField = $fieldName;
	}

	public function getMultiTenantField()
	{
		return $this->multiTenantField;
	}

	public function getMultiTenantMode()
	{
		return $this->multiTenantEnabled;
	}

	public function isFile()
	{
		return $this->isFile;
	}

	public function isReference()
	{
		return isset($this->reference);
	}

	public function setEmbeddedDocument()
	{
		$this->isEmbeddedDocument = true;
	}

	public function isEmbeddedDocument()
	{
		return $this->isEmbeddedDocument;
	}

	public function getFieldValue(string $document, $field) : mixed
	{
		return $this->reflFields[$field]->getValue($document);
	}
	
	public function getReflectionClass() : \ReflectionClass
	{
		if(!is_null($this->reflClass)){
			$this->reflClass = new \ReflectionClass($this->name);
		}

		return $this->reflClass;
	}

	public function getReflectionProperties() : array
	{
		return $this->fields;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function newInstance()
	{
		return new $this->name();
	}

	public function hasIdentifier() : bool
	{
		return !empty($this->identifier);
	}

	public function getIdentifierValue($document)
	{
		return isset($this->identifier) && isset($this->reflFields[$this->identifier])
						? $this->reflFields[$this->identifier]->getValue($document)
						: null;
	}
	
	public function setIdentifierValue(&$id, $document) : void
	{
		if(!isset($this->identifier)){
			throw new \Exception('The mapping of ' . get_class($document) . ' hasn\'t any Id mapped. Each document should have one Id');
		}
		$this->reflFields[$this->identifier]->setValue($document, $id);
	}

	public function getIdentifierValues($object)
	{
			return array($this->identifier => $this->getIdentifierValue($object));
	}

	public function setFieldValue($document, $field, $value)
	{
		$this->reflFields[$field]->setValue($document, $value);
	}

	public function mappings() : array
	{
		return $this->fieldMappings;
	}

	public function opts() : Map<string,mixed>
	{
		return $this->opts;
	}

	public function mapField(DtoMappingMetadataInterface $mapping) : void
	{
		// Most cases user will set only name of mapping, which is equal to fieldName
		if($mapping->getFieldName() === null && $mapping->getName() !== null){
			$mapping->setFieldName((string)$mapping->getName());
		} else if(is_null($mapping->getName()) && !is_null($mapping->getFieldName())){
			$mapping->setName((string)$mapping->getFieldName());
		}

		if($this->reflClass->hasProperty($mapping->getFieldName())) {
			$reflProp = $this->reflClass->getProperty($mapping->getFieldName());
			$reflProp->setAccessible(true);
			$this->reflFields[$mapping->getName()] = $reflProp;
		}

		$this->fieldMappings[$mapping->getName()] = $mapping;
	}
}
