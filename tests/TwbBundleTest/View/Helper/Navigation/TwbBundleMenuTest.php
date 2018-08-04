<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   TwbBundleTest\Form\View\Helper\Navigation
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2018 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace TwbBundleTest\View\Helper\Navigation;

use TwbBundle\View\Helper\Navigation\TwbBundleMenu;

class TwbBundleMenuTest extends AbstractTest
{
    // @codingStandardsIgnoreStart
    /**
     * Class name for view helper to test.
     *
     * @var string
     */
    protected $_helperName = TwbBundleMenu::class;

    /**
     * View helper.
     *
     * @var Menu
     */
    protected $_helper;
    // @codingStandardsIgnoreEnd

    public function testCanRenderNormalMenu()
    {
        $this->_helper->setServiceLocator($this->serviceManager);
        $returned = $this->_helper->renderMenu('Navigation');
        $this->assertEquals($returned, $this->_getExpected('menu/default.html'));
    }
}
