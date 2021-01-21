
Testing Basics
==============

Laravel uses PHPUnit for tests.

The phpunit.xml file controls testing and is included automatically in the root directory.

The tests folder contains a Feature and Unit folder. 

- Feature tests test a larger portion of the code as a whole. Most tests should generally be feature tests as they provide the most confidence the application is working as it should.

- Unit tests focus on a very small, isolated portion of the code.

Each folder (Feature & Unit) contain an ExampleTest.php file.

The php artisan test (or vendor/bin/phpunit) command will run the tests. This will automatically set the configuration environment to testing thanks to the vars in the phpunit.xml file.

Run php artisan config:clear before running your tests if you've changed testing env vars.

Creating an optional .env.testing file in the root will override the basic .env file.


Creating Tests
==============

php artisan make:test UserTest

Tests by default will be placed in the tests/Feature folder. For Unit tests, add --unit to the above command.

A basic example test is below:

<?
class ExampleTest extends TestCase{

	public function testBasicTest(){
	    $this->assertTrue(true);
	}
}
?>


