<?hh

namespace Pi\Interfaces;




interface ServiceGatewayFactoryInterface {
	
	public function getServiceGateway(IRequest $req) : ServiceGatewayInterface;
}