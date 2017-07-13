<?php
 /*Run this on CMD before run this script
 java -Dwebdriver.gecko.driver="C:\\SeleniumGecko\geckodriver-v0.17.0-win64\\geckodriver.exe" -jar D:\rc\selenium-server-standalone-3.4.0.jar
 */
 
//$driver = new FirefoxDriver();
// An example of using php-webdriver.
namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
require_once('vendor/autoload.php');
// start Firefox with 5 second timeout
$host = 'http://localhost:4444/wd/hub'; // this is the default
//System.setProperty("webdriver.gecko.driver","C:\\SeleniumGecko\geckodriver-v0.17.0-win64\\geckodriver.exe");
$capabilities = DesiredCapabilities::firefox();
$driver = RemoteWebDriver::create($host, $capabilities, 5000);
// navigate to 'http://www.seleniumhq.org/'
//$driver->get('http://www.seleniumhq.org/');
$driver->get('http://localhost/trends/');
// adding cookie
$driver->manage()->deleteAllCookies();
$cookie = new Cookie('cookie_name', 'cookie_value');
$driver->manage()->addCookie($cookie);
$cookies = $driver->manage()->getCookies();
print_r($cookies);
//$driver->manage()->timeouts()->implicitlyWait=10;
// click the link 'About'
$link = $driver->findElement(
    //WebDriverBy::id('primary')
	WebDriverBy::className('tags-links')
	// WebDriverBy::linkText("Hello world!")
	// WebDriverBy::xpath('//a[@href="http://localhost/trends/2017/06/21/hello-world/"]')
)->getAttribute("innerHTML");
echo "test by goutam:";
var_dump($link);
//echo "<pre>"; 
//echo "test:\\n\\r".$link;die;
//$link->click();
// wait until the page is loaded
$driver->wait()->until(
    WebDriverExpectedCondition::titleContains('site-title')
);
// print the title of the current page
echo "The title is '" . $driver->getTitle() . "'\n";
// print the URI of the current page
echo "The current URI is '" . $driver->getCurrentURL() . "'\n";
// write 'php' in the search box
$driver->findElement(WebDriverBy::id('q'))
    ->sendKeys('php');
// submit the form
$driver->findElement(WebDriverBy::id('submit'))
    ->click(); // submit() does not work in Selenium 3 because of bug https://github.com/SeleniumHQ/selenium/issues/3398
// wait at most 10 seconds until at least one result is shown
$driver->wait(10)->until(
    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
        WebDriverBy::className('gsc-result')
    )
);
// close the Firefox
$driver->quit();