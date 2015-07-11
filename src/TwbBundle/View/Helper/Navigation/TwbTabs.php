<?php
namespace TwbBundle\View\Helper\Navigation;

/**
 * Class TwbTabs
 * @package TwbBundle\View\Helper\Navigation
 */
class TwbTabs extends AbstractNavHelper
{

    /**
     * Renders helper
     *
     * @param  string|\Zend\Navigation\AbstractContainer $container [optional] container to render.
     *                                         Default is null, which indicates
     *                                         that the helper should render
     *                                         the container returned by {@link
     *                                         getContainer()}.
     * @return string helper output
     * @throws \Zend\View\Exception\ExceptionInterface if unable to render
     */
    public function render($container = null)
    {
        return $this->renderTabs($container);
    }

    /**
     * Renders Tabs
     * @param null|\Zend\Navigation\Navigation $container
     * @param bool $pills
     * @param bool $stacked
     * @param bool $renderIcons
     * @return string
     */
    public function renderTabs(\Zend\Navigation\Navigation $container = null,
                               $pills = false,
                               $stacked = false,
                               $renderIcons = true)
    {
        if (null === $container) {
            $container = $this->getContainer();
        }
        $ulClass = 'nav';
        //Tabs or Pills
        if ($pills) {
            $ulClass .= ' nav-pills';
            $activeIconInverse = true;
        } else {
            $ulClass .= ' nav-tabs';
            $activeIconInverse = false;
        }
        //Stacked
        if ($stacked) {
            $ulClass .= ' nav-stacked';
        }
        //Container
        $options = [
            'ulClass' => $ulClass,
        ];
        $html = "\n" . $this->renderContainer($container, $renderIcons, $activeIconInverse, $options);

        return $html;
    }

}