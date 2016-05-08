<?hh

namespace Pi\Interfaces;




interface ServiceGatewayAsyncInterface {
	
	public function sendAsync(mixed $requestDto) : Awaitable<mixed>;

	public function sendAllAsync(array<mixed> $requestDtos) : Awaitable<Vector<mixed>>;

	public function publishAsync(mixed $requestDto) : Awaitable<mixed>;

	public function publishAllAsync(array<mixed> $requestDtos) : Awaitable<Vector<mixed>>;
	
}