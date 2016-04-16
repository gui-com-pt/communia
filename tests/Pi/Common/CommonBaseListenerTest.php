<?hh

namespace Test\Common;




/**
 * CommonBaseListener Tests
 */
class CommonBaseListenerTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		CommonBaseListener::$workingDir = '/tmp';
	}
	public function testCanChangeDirectoryWithListener()
	{
		$this->assertEquals(getcwd(), '/tmp');
		$this->assertTrue(CommonBaseListener::$workingDir == null);
	}
}