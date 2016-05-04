<?hh

namespace Pi\Interfaces;




class ServiceGatewayInterface {
	
	public function send(mixed $requestDto) : mixed;

	public function sendAll(Vector<mixed> $requestDtos);

	public function publish(mixed $requestDto);

	public function publishAll(Set<mixed> $requestDtos);
	
}