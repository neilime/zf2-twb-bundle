<?php
namespace TwbBundleTest;

use TwbBundle\ConfigProvider;

/**
 * Config Provider Test
 *
 * @package TwbBundleTest
 */
class ConfigProviderTest extends \PHPUnit_Framework_TestCase
{
	/**
     * The zend-component config provider
     *
	 * @var \TwbBundle\ConfigProvider
	 */
	protected $configProvider;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$this->configProvider = new ConfigProvider();
	}

    /**
     * Tests valid return values
     */
	public function testInvokeReturnValues()
    {
		$config = $this->configProvider->__invoke();

		$this->assertArrayHasKey('twbbundle', $config);
        $this->assertArrayHasKey('dependencies', $config);
        $this->assertArrayHasKey('view_helpers', $config);

        $this->assertNotEmpty($config['twbbundle']);
        $this->assertNotEmpty($config['dependencies']);
        $this->assertNotEmpty($config['view_helpers']);
	}
}
