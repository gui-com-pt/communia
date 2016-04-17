<?hh
use Pi\Common\RandomString;

class RandomStringTest extends \PHPUnit_Framework_TestCase {

  public function testCanGenerateRandomStringInLoop()
  {
    $last = RandomString::generate(20);

    for($i = 1; $i <= 2; $i++) {
        $new = RandomString::generate(20);
        $this->assertNotEquals($last, $new);
        $last = $new;
    }
  }

  public function testCanGenerateRandomStringSlowly()
  {
  	$start = microtime(true);
  	$key = RandomString::generate();
  	$diff = microtime(true) - $start;
  	if($diff > 10 ) {
  		throw new \Exception('Slow test');
  	}
  }
}
