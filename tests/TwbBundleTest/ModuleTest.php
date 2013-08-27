<?php
namespace TwbBundleTest;
class ModuleTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @var \TwbBundle\Module
	 */
	protected $module;

	public function setUp(){
		$this->module = new \TwbBundle\Module();
	}

	public function testGetAutoloaderConfig(){
		$this->assertEquals(
			array('Zend\Loader\ClassMapAutoloader' => array(realpath(getcwd().'/../autoload_classmap.php'))),
			$this->module->getAutoloaderConfig()
		);
	}

	public function testGetConfig(){
		$this->assertTrue(is_array($this->module->getConfig()));
	}
}