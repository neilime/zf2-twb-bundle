<?php
namespace TwbBundle\View\Helper\Navigation;

/**
 * Class TwbButtons
 * @package TwbBundle\View\Helper\Navigation
 */
class TwbButtons extends AbstractButtonHelper
{

    /**
     * Renders helper
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
        return $this->renderButtons($container);
    }

    /**
     * Renders buttons
     * @param null|\Zend\Navigation\Navigation $container
     * @param null|string $type
     * @param bool $renderIcons
     * @return string
     */
    public function renderButtons(\Zend\Navigation\Navigation $container = null,
                                  $type = null,
                                  $renderIcons = true)
    {
        if (null === $container) {
            $container = $this->getContainer();
        }
        if (!$container->hasPages()) {
            return '';
        }
        if (is_null($type)) {
            $type = self::TYPE_GROUPS_HORIZONTAL;
        }
        $options = ['type' => $type];
        $html = $this->renderContainer($container, $renderIcons, false, $options);

        return $html;
    }
}