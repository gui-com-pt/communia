<?hh

namespace Pi\Interfaces;

interface ISerializerService {
	
	public function serialize($request);

	public function unserialize($request);
}