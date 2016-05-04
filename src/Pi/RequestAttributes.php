<?hh

namespace Pi;

enum RequestAttributes : int {
	None = 0;


	OneWay = 1;
	Reply = 2;
	Json = 3;

	// Endpoints
	Http = 30;
	MessageQueue = 31;

	/**
	 * Service was executed within code (e.g. resolveService($svcType))
	 */
	InProcess = 40;
}