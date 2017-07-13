<?php

namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Framework;
require_once('vendor/autoload.php');

class myTest extends PHPUnit2_Framework_TestCase{
	
	protected $url = 'http://github.com';
    /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;

	public function setUp()
    {
		$host = 'http://localhost:4444/wd/hub'; // this is the default
		$capabilities = DesiredCapabilities::firefox();
		$this->webDriver = RemoteWebDriver::create($host, $capabilities, 5000);       
    }

    public function tearDown()
    {
        $this->webDriver->close();
    }

    public function testGitHubHome()
    {
        $this->webDriver->get($this->url);
        // checking that page title contains word 'GitHub'
        $this->assertContains('GitHub', $this->webDriver->getTitle());
    }

    public function testSearch()
    {
        $this->webDriver->get($this->url);

        // find search field by its id
        $search = $this->webDriver->findElement(WebDriverBy::id('js-command-bar-field'));
        $search->click();

        // typing into field
        $this->webDriver->getKeyboard()->sendKeys('php-webdriver');

        // pressing "Enter"
        $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);

        $firstResult = $this->webDriver->findElement(
            // some CSS selectors can be very long:
            WebDriverBy::cssSelector('li.public:nth-child(1) > h3:nth-child(3) > a:nth-child(1) > em:nth-child(2)')
        );

        $firstResult->click();

        // we expect that facebook/php-webdriver was the first result
        $this->assertContains("php-webdriver",$this->webDriver->getTitle());

        $this->assertEquals('https://github.com/facebook/php-webdriver', $this->webDriver->getCurrentURL());

        $this->assertElementNotFound(WebDriverBy::className('avatar'));

    }

    protected function waitForUserInput()
    {
        if(trim(fgets(fopen("php://stdin","r"))) != chr(13)) return;
    }

    protected function assertElementNotFound($by)
    {
        $els = $this->webDriver->findElements($by);
        if (count($els)) {
            $this->fail("Unexpectedly element was found");
        }
        // increment assertion counter
        $this->assertTrue(true);
        
    }
}
$test = new myTest();
$test->testSearch();
?>