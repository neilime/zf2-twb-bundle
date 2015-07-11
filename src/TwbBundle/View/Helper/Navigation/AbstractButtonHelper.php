<?php
namespace TwbBundle\View\Helper\Navigation;

/**
 * Class AbstractButtonHelper
 * @package TwbBundle\View\Helper\Navigation
 */
abstract class AbstractButtonHelper extends AbstractHelper
{

    const TYPE_SINGLE_HORIZONTAL = 'singleHorizontal';
    const TYPE_SINGLE_VERTICAL = 'singleVertical';
    const TYPE_GROUPS_HORIZONTAL = 'groupsHorizontal';
    const TYPE_GROUPS_VERTICAL = 'groupsVertical';
    const TYPE_ONE_GROUP = 'oneGroup';

    protected $buttonGroupOpen = false;


    protected function decorateContainer($content,
                                         \Zend\Navigation\Navigation $container,
                                         $renderIcons = true,
                                         $activeIconInverse = true,
                                         array $options = [])
    {
        if (isset($options['type']) && (
                $options['type'] == self::TYPE_GROUPS_HORIZONTAL
                || $options['type'] == self::TYPE_GROUPS_VERTICAL
                || $options['type'] == self::TYPE_ONE_GROUP)
        ) {
            $content .= $this->closeButtonGroup();
        }
        $html = '<div class="btn-toolbar">';
        $html .= "\n" . $content;
        $html .= "\n</div>";

        return $html;
    }

    protected function decorateNavHeader($content,
                                         \Zend\Navigation\Page\AbstractPage $item,
                                         $renderIcons = true,
                                         $activeIconInverse = true,
                                         array $options = [])
    {
        return '';
    }

    protected function decorateNavHeaderInDropdown($content,
                                                   \Zend\Navigation\Page\AbstractPage $item,
                                                   $renderIcons = true,
                                                   $activeIconInverse = true,
                                                   array $options = [])
    {
        return '';
    }

    protected function decorateDivider($content,
                                       \Zend\Navigation\Page\AbstractPage $item,
                                       array $options = [])
    {
        if (isset($options['type']) && $options['type'] == self::TYPE_GROUPS_HORIZONTAL) {
            //Groups horizontal
            $html = $this->closeButtonGroup() . $content;
        } elseif (isset($options['type']) && $options['type'] == self::TYPE_GROUPS_VERTICAL) {
            //Groups vertical
            $html = $this->closeButtonGroup() . $content;
            $html .= "\n<br>";
        } else {
            //Non grouped - do not render divider
            $html = '';
        }

        return $html;
    }

    protected function decorateLink($content,
                                    \Zend\Navigation\Page\AbstractPage $page,
                                    $renderIcons = true,
                                    $activeIconInverse = true,
                                    array $options = [])
    {
        if (isset($options['type']) && $options['type'] == self::TYPE_SINGLE_HORIZONTAL) {
            $html = '<div class="btn-group">'
                . "\n" . $content
                . '</div>';
        } elseif (isset($options['type']) && $options['type'] == self::TYPE_SINGLE_VERTICAL) {
            $html = '<div class="btn-group">'
                . "\n" . $content
                . '</div><br>';
        } else {
            //One of the grouped types
            $html = $this->openButtonGroup() . $content;
        }

        return $html;
    }

    protected function decorateDropdown($content,
                                        \Zend\Navigation\Page\AbstractPage $page,
                                        $renderIcons = true,
                                        $activeIconInverse = true,
                                        array $options = [])
    {
        $html = $this->closeButtonGroup();
        $html .= '<div class="btn-group">'
            . "\n" . $content
            . "\n</div>";
        if (isset($options['type']) && (
                $options['type'] == self::TYPE_SINGLE_VERTICAL
                || $options['type'] == self::TYPE_GROUPS_VERTICAL
            )
        ) {
            $html .= "\n<br>";
        }

        return $html;
    }

    protected function renderLink(\Zend\Navigation\Page\AbstractPage $page,
                                  $renderIcons = true,
                                  $activeIconInverse = true,
                                  array $options = [])
    {
        $class = $page->getClass();
        $this->addWord('btn', $class);
        if ($page->isActive(true)) {
            $this->addWord('active', $class);
        }
        $page->setClass($class);
        $html = parent::renderLink($page, $renderIcons, $activeIconInverse, $options);

        return $html;
    }

    protected function renderLinkInDropdown(\Zend\Navigation\Page\AbstractPage $page,
                                            $renderIcons = true,
                                            $activeIconInverse = true,
                                            array $options = [])
    {
        $html = parent::renderLink($page, $renderIcons, $activeIconInverse, $options);

        return $html;
    }

    protected function renderDropdown(\Zend\Navigation\Page\AbstractPage $page,
                                      $renderIcons = true,
                                      $activeIconInverse = true,
                                      array $options = [])
    {
        $class = $page->getClass();
        $this->addWord('btn', $class);
        if ($page->isActive(true)) {
            $this->addWord('active', $class);
        }
        $page->setClass($class);
        $html = parent::renderDropdown($page, $renderIcons, $activeIconInverse, $options);

        return $html;
    }

    protected function openButtonGroup()
    {
        if (!$this->buttonGroupOpen) {
            $this->buttonGroupOpen = true;
            $html = "\n" . '<div class="btn-group">';
        } else {
            $html = '';
        }

        return $html;
    }

    protected function closeButtonGroup()
    {
        if ($this->buttonGroupOpen) {
            $this->buttonGroupOpen = false;
            $html = "\n</div>";
        } else {
            $html = '';
        }

        return $html;
    }
}