<?hh

namespace Pi;

use Pi\Interfaces\ISerializerService;




class PhpSerializer implements ISerializerService{
	
	public function serialize($request)
	{
		if(is_string($request)) {
			return $request;
		}
		return serialize($request);
	}

	public function unserialize($request)
	{
		return unserialize($request);
	}
}