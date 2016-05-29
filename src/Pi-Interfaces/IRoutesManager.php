<?hh

namespace Pi\Interfaces;

interface IRoutesManager {

  public function add($restPath, $serviceType, $requestType, $action = null, array $verbs = array('GET'), $summary = null, $notes = null);

  public function get($restPath);

  public function routes();
}
