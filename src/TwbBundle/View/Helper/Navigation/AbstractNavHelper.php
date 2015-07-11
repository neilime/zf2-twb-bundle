<?php
namespace TwbBundle\View\Helper\Navigation;

/**
 * Class AbstractNavHelper
 * @package TwbBundle\View\Helper\Navigation
 */
abstract class AbstractNavHelper extends AbstractHelper
{

    protected function decorateContainer(
        $content,
        \Zend\Navigation\Navigation $container,
        $renderIcons = true,
        $activeIconInverse = true,
        array $options = []
    ) {
        //Align option
        if (isset($options['align'])) {
            $align = $options['align'];
        } else {
            $align = null;
        }
        //ulClass option
        if (isset($options['ulClass'])) {
            $ulClass = $options['ulClass'];
        } else {
            $ulClass = '';
        }
        if ($align == self::ALIGN_LEFT) {
            $this->addWord('pull-left', $ulClass);
        } elseif ($align == self::ALIGN_RIGHT) {
            $this->addWord('pull-right', $ulClass);
        }
        $html = '<ul class="' . $ulClass . '">';
        $html .= "\n" . $content;
        $html .= "\n</ul>";

        return $html;
    }

    protected function decorateNavHeader($content,
                                         \Zend\Navigation\Page\AbstractPage $item,
                                         $renderIcons = true,
                                         $activeIconInverse = true,
                                         array $options = [])
    {
        return $this->decorateNavHeaderInDropdown($content, $item, $renderIcons, $activeIconInverse, $options);
    }

    protected function decorateDivider($content,
                                       \Zend\Navigation\Page\AbstractPage $item,
                                       array $options = [])
    {
        return $this->decorateDividerInDropdown($content, $item, $options);
    }

    protected function decorateLink($content,
                                    \Zend\Navigation\Page\AbstractPage $page,
                                    $renderIcons = true,
                                    $activeIconInverse = true,
                                    array $options = [])
    {
        return $this->decorateLinkInDropdown($content, $page, $renderIcons, $activeIconInverse, $options);
    }

    protected function decorateDropdown($content,
                                        \Zend\Navigation\Page\AbstractPage $page,
                                        $renderIcons = true,
                                        $activeIconInverse = true,
                                        array $options = [])
    {
        //Get attribs
        $liAttribs = [
            'id' => $page->getId(),
            'class' => 'dropdown' . ($page->isActive(true) ? ' active' : ''),
        ];
        $html = "\n" . '<li' . $this->htmlAttribs($liAttribs) . '>'
            . "\n" . $content
            . "\n</li>";

        return $html;
    }
}