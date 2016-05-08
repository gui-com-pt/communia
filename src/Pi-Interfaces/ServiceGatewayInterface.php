<?hh

namespace Pi\Interfaces;




interface ServiceGatewayInterface {
	
	public function send(mixed $requestDto) : mixed;

	public function sendAll(array<mixed> $requestDtos) : Vector<mixed>;

	public function publish(mixed $requestDto) : mixed;

	public function publishAll(array<mixed> $requestDtos) : Vector<mixed>;
	
}