<?hh

namespace Test\Common;




/**
 * PHPUnit Listener used not only by this library
 * but also extended by others libraries i wrote
 * 
 */
class CommonBaseListener extends \PHPUnit_Framework_BaseTestListener {

	/**
	 * Current Working Directory
	 * Change the $workingDir in the Test SetUp
	 * If $workingDir is not null at the begining for a test
	 * The listener will call chdir() and clear the value
	 */
	static $workingDir = null;

	public function __construct()
	{
		die('executed');
	}

	public function startTest(\PHPUnit_Framework_Test $test)
    {
    	die('executed');
        printf("Test '%s' started.\n", $test->getName());
    }

    protected function changeDirIfNeeded() : bool
    {
    	if(self::$workingDir === null || empty(self::$workingDir)) {
    		return false;
    	}

    	chdir(self::$workingDir);
    	self::$workingDir = null;
    	return true;
    }
}