<?hh

namespace Pi;

use Pi\Interfaces\ISerializerService,
    Pi\Interfaces\IContainable,
    Pi\Interfaces\IContainer;




class PhpSerializerService implements ISerializerService, IContainable {
  
  public function ioc(IContainer $ioc)
  {

  }

  public function serialize($request)
  {
    $result = serialize($request);
    return $result;
  }

  public function unserialize($request)
  {
    $result = unserialize($request);
    return $result;
  }
}
