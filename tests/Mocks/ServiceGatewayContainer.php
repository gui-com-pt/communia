<?hh

namespace Mocks;

use Pi\InProcessServiceGateway,
	Pi\Interfaces\ServiceGatewayFactoryInterface,
	Pi\Interfaces\IContainer,
	Pi\Interfaces\IRequest;




class ServiceGatewayContainer {
	
	public static function getServiceGateway(IRequest $req, ?IContainer $container = null)
	{
		if(is_null($container)) {
			return new InProcessServiceGateway($req);
		}

		if($factory = $container->tryResolve(ServiceGatewayFactoryInterface::class)) {
			return $factory->getServiceGateway($req);
		}
		return new InProcessServiceGateway($req);
	}
}