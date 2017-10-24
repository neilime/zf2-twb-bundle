<?php
namespace TwbBundle;

/**
 * The configuration provider for the TwbBundle module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Path to the module config
     *
     * @const string
     */
    const MODULE_CONFIG_PATH = __DIR__ . '/../../config/module.config.php';

    /**
     * The module config ZF2/3
     *
     * @var array
     */
    protected $moduleConfig;

    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function __invoke()
    {
        if (!file_exists(self::MODULE_CONFIG_PATH)) {
            throw new \InvalidArgumentException('Wrong path to module config file. File or directory does not exist: ' . self::MODULE_CONFIG_PATH);
        }

        $this->moduleConfig = require self::MODULE_CONFIG_PATH;

        return [
            'twbbundle' => $this->getTwbBundleOptions(),
            'dependencies' => $this->getDependencies(),
            'view_helpers' => $this->getViewHelpers()
        ];
    }

    /**
     * Returns twb bundle options
     *
     * @return array
     */
    protected function getTwbBundleOptions()
    {
        return array_key_exists('twbbundle', $this->moduleConfig) ? $this->moduleConfig['twbbundle'] : [];
    }

    /**
     * Returns dependencies (former server_manager)
     *
     * @return array
     */
    protected function getDependencies()
    {
        return array_key_exists('service_manager', $this->moduleConfig) ? $this->moduleConfig['service_manager'] : [];
    }

    /**
     * Returns view helpers
     *
     * @return array
     */
    protected function getViewHelpers()
    {
        return array_key_exists('view_helpers', $this->moduleConfig) ? $this->moduleConfig['view_helpers'] : [];
    }
}
