<?hh

namespace Pi\Interfaces;




class ServiceGatewayFactoryInterface {
	
	public function getServiceGateway(IRequest $req) : ServiceGatewayInterface;
}