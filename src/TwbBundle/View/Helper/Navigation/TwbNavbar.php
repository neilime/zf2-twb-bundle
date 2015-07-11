<?php
namespace TwbBundle\View\Helper\Navigation;

use TwbBundle\View\Helper\Navigation\Exception\UnsupportedElementTypeException;

/**
 * Class TwbNavbar
 * @package TwbBundle\View\Helper\Navigation
 */
class TwbNavbar extends AbstractNavHelper
{
    /**
     * @param string|\Zend\Navigation\AbstractContainer $container
     * @return string
     */
    public function render($container = null)
    {
        return $this->renderNavbar($container);
    }

    /**
     * @param \Zend\Navigation\Navigation $container
     * @param null $leftElements
     * @param null $rightElements
     * @param \Zend\Navigation\Page\AbstractPage $brandLink
     * @param null $brandName
     * @param string $fixed
     * @param bool $responsive
     * @param bool $renderIcons
     * @param bool $inverse
     * @return string
     * @throws UnsupportedElementTypeException
     */
    public function renderNavbar(
        \Zend\Navigation\Navigation $container = null,
        $leftElements = null,
        $rightElements = null,
        \Zend\Navigation\Page\AbstractPage $brandLink = null,
        $brandName = null,
        $fixed = 'default',
        $responsive = false,
        $renderIcons = true,
        $inverse = false
    ) {
        if (null === $container) {
            $container = $this->getContainer();
        }

        if ($leftElements && !is_array($leftElements)) {
            $leftElements = [$leftElements];
        }

        if ($rightElements && !is_array($rightElements)) {
            $rightElements = [$rightElements];
        }

        $html = '';

        //Navbar scaffolding
        $navbarClass = 'navbar navbar-default';

        switch ($fixed) {
            case 'top' :
                $navbarClass .= ' navbar-fixed-top';
                break;
            case 'bottom' :
                $navbarClass .= ' navbar-fixed-bottom';
                break;
            case 'top static':
                $navbarClass .= ' navbar-static-top';
                break;
            default:
                break;
        }

        if ($inverse) {
            $navbarClass .= ' navbar-inverse';
        }

        $html .= "<nav class='{$navbarClass}' role='navigation'>";
        $html .= "\n" . '<div class="container">';
        $html .= "\n" . '<div id="navbar" class="collapse navbar-collapse">';

        //Responsive (button)
        if ($responsive) {
            $html .= "\n" . '<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">';
            $html .= "\n" . '<span class="icon-bar"></span>';
            $html .= "\n" . '<span class="icon-bar"></span>';
            $html .= "\n" . '<span class="icon-bar"></span>';
            $html .= "\n" . '</a>';
        }

        //Brand
        if ($brandLink) {
            $view = $this->getView();
            if ($brandName) {
                $brandName = $view->escapeHtml($brandName);
            } else {
                $brandName = $view->escapeHtml($brandLink->getLabel());
            }
            $html .= "\n" . '<a class="brand" href="' . $brandLink->getHref() . '">' . $brandName . '</a>';
        }

        //Responsive (div)
        if ($responsive) {
            $html .= "\n" . '<div class="nav-collapse">';
        }

        //Primary container
        $options = [
            'align' => null,
            'ulClass' => 'nav navbar-nav',
        ];
        $html .= "\n" . $this->renderContainer($container, $renderIcons, true, $options);

        //Left elements
        if ($leftElements) {
            $html .= "\n" . $this->renderElements($leftElements, 'left', $renderIcons);
        }

        //Right elements
        if ($rightElements) {
            $html .= "\n" . $this->renderElements($rightElements, 'right', $renderIcons);
        }

        //Responsive (close div)
        if ($responsive) {
            $html .= "\n" . '</div>';
        }

        //Scaffolding (close divs)
        $html .= "\n</div>";
        $html .= "\n</div>";
        $html .= "\n</nav>";

        return $html;
    }

    /**
     * @param array $elements
     * @param null $align
     * @param bool $renderIcons
     * @return string
     * @throws UnsupportedElementTypeException
     */
    protected function renderElements(array $elements, $align = null, $renderIcons = true)
    {
        $html = '';
        $view = $this->getView();

        foreach ($elements as $element) {
            if ($element instanceof \Zend\Navigation\AbstractContainer) {
                $options = [
                    'align' => $align,
                    'ulClass' => 'nav navbar-nav',
                ];
                $html .= "\n" . $this->renderContainer($element, $renderIcons, true, $options);
            }

            elseif (is_string($element)) {
                $pClass = 'navbar-text';
                if ($align == self::ALIGN_LEFT) {
                    $pClass .= ' pull-left';
                } elseif ($align == self::ALIGN_RIGHT) {
                    $pClass .= ' pull-right';
                }
                $html .= "\n" . '<p class="' . $pClass . '">' . $view->escapeHtml($element) . '</p>';
            } else {
                throw new UnsupportedElementTypeException('Unsupported element type.');
            }
        }

        return $html;
    }
}