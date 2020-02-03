<?php

namespace TwbBundle\View\Helper\Navigation;

use RecursiveIteratorIterator;
use Laminas\View\Helper\Navigation\Menu as ZendMenuHelper;
use Laminas\Navigation\AbstractContainer;
use Laminas\Navigation\Page\AbstractPage;

class TwbBundleMenu extends ZendMenuHelper
{
    /**
     * CSS class to use for the ul element
     *
     * @var string
     */
    protected $ulClass = 'nav';

    /**
     * CSS class to use for the li element
     *
     * @var string
     */
    protected $liClass = '';

    /**
     * @var string
     */
    protected $subUlClass = 'dropdown-menu';

    /**
     * @var string
     */
    protected $ulId;

    /**
     * @var bool
     */
    protected $useCaret = true;

    /**
     * @return string
     */
    public function getUlId()
    {
        return $this->ulId;
    }

    /**
     * @param $ulId
     * @return $this
     */
    public function setUlId($ulId)
    {
        $this->ulId = $ulId;
        return $this;
    }

    /**
     * @return string
     */
    public function getLiClass()
    {
        return $this->liClass;
    }

    /**
     * @param string $liClass
     */
    public function setLiClass($liClass)
    {
        $this->liClass = $liClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubUlClass()
    {
        return $this->subUlClass;
    }

    /**
     * @param $subUlClass
     * @return $this
     */
    public function setSubUlClass($subUlClass)
    {
        $this->subUlClass = $subUlClass;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getUseCaret()
    {
        return $this->useCaret;
    }

    /**
     * @param $useCaret
     * @return $this
     */
    public function setUseCaret($useCaret)
    {
        $this->useCaret = $useCaret;
        return $this;
    }

    /**
     * Renders the deepest active menu within [$minDepth, $maxDepth], (called
     * from {@link renderMenu()})
     *
     * @param  AbstractContainer $container          container to render
     * @param  string            $ulClass            CSS class for first UL
     * @param  string            $indent             initial indentation
     * @param  int|null          $minDepth           minimum depth
     * @param  int|null          $maxDepth           maximum depth
     * @param  bool              $escapeLabels       Whether or not to escape the labels
     * @param  bool              $addClassToListItem Whether or not page class applied to <li> element
     * @return string
     */
    protected function renderDeepestMenu(
        AbstractContainer $container,
        $ulClass,
        $indent,
        $minDepth,
        $maxDepth,
        $escapeLabels,
        $addClassToListItem,
        $liActiveClass
    ) {
        if (!$active = $this->findActive($container, $minDepth - 1, $maxDepth)) {
            return '';
        }

        // special case if active page is one below minDepth
        if ($active['depth'] < $minDepth) {
            if (!$active['page']->hasPages()) {
                return '';
            }
        } elseif (!$active['page']->hasPages()) {
            // found pages has no children; render siblings
            $active['page'] = $active['page']->getParent();
        } elseif (is_int($maxDepth) && $active['depth'] +1 > $maxDepth) {
            // children are below max depth; render siblings
            $active['page'] = $active['page']->getParent();
        }

        $ulClass = $ulClass ? ' class="' . $ulClass . '"' : '';
        $ulId = ($this->getUlId()) ? ' id="' . $this->getUlId() . '"' : '';
        $html = $indent . '<ul' . $ulId . $ulClass . '>' . PHP_EOL;

        foreach ($active['page'] as $subPage) {
            if (!$this->accept($subPage)) {
                continue;
            }

            // render li tag and page
            $liClasses = array();
            // Is page active?
            if ($subPage->isActive(true)) {
                $liClasses[] = 'active';
            }

            // Add CSS class from page to <li>
            if ($addClassToListItem && $subPage->getClass()) {
                $liClasses[] = $subPage->getClass();
            }
            $liClass = empty($liClasses) ? '' : ' class="' . implode(' ', $liClasses) . '"';

            $html .= $indent . '    <li' . $liClass . '>' . PHP_EOL;
            $html .= $indent . '        ' . $this->htmlify($subPage, $escapeLabels, $addClassToListItem) . PHP_EOL;
            $html .= $indent . '    </li>' . PHP_EOL;
        }

        $html .= $indent . '</ul>';

        return $html;
    }

    /**
     * Renders a normal menu (called from {@link renderMenu()})
     *
     * @param  AbstractContainer $container          container to render
     * @param  string            $ulClass            CSS class for first UL
     * @param  string            $indent             initial indentation
     * @param  int|null          $minDepth           minimum depth
     * @param  int|null          $maxDepth           maximum depth
     * @param  bool              $onlyActive         render only active branch?
     * @param  bool              $escapeLabels       Whether or not to escape the labels
     * @param  bool              $addClassToListItem Whether or not page class applied to <li> element
     * @return string
     */
    protected function renderNormalMenu(
        AbstractContainer $container,
        $ulClass,
        $indent,
        $minDepth,
        $maxDepth,
        $onlyActive,
        $escapeLabels,
        $addClassToListItem,
        $liActiveClass
    ) {
        $html = '';


        // find deepest active
        $found = $this->findActive($container, $minDepth, $maxDepth);
        if ($found) {
            $foundPage  = $found['page'];
            $foundDepth = $found['depth'];
        } else {
            $foundPage = null;
        }

        // create iterator
        $iterator = new RecursiveIteratorIterator($container,
            RecursiveIteratorIterator::SELF_FIRST);
        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }

        // iterate container
        $prevDepth = -1;
        foreach ($iterator as $page) {
            $depth = $iterator->getDepth();
            $isActive = $page->isActive(true);
            if ($depth < $minDepth || !$this->accept($page)) {
                // page is below minDepth or not accepted by acl/visibility
                continue;
            } elseif ($onlyActive && !$isActive) {
                // page is not active itself, but might be in the active branch
                $accept = false;
                if ($foundPage) {
                    if ($foundPage->hasPage($page)) {
                        // accept if page is a direct child of the active page
                        $accept = true;
                    } elseif ($foundPage->getParent()->hasPage($page)) {
                        // page is a sibling of the active page...
                        if (!$foundPage->hasPages() ||
                            is_int($maxDepth) && $foundDepth + 1 > $maxDepth) {
                            // accept if active page has no children, or the
                            // children are too deep to be rendered
                            $accept = true;
                        }
                    }
                }

                if (!$accept) {
                    continue;
                }
            }

            // make sure indentation is correct
            $depth -= $minDepth;
            $myIndent = $indent . str_repeat('        ', $depth);

            if ($depth > $prevDepth) {
                // start new ul tag
                if ($ulClass && $depth ==  0) {
                    $ulClass = ' class="' . $ulClass . '"';
                } elseif ($page->getParent()) {
                    $ulClass = ' class="' . $this->getSubUlClass() . '"';
                } else {
                    $ulClass = '';
                }

                if ($this->getUlId() && $depth == 0) {
                    $ulId = ' id="' . $this->getUlId() . '"';
                } else {
                    $ulId = '';
                }

                $html .= $myIndent . '<ul' . $ulId . $ulClass . '>' . PHP_EOL;
            } elseif ($prevDepth > $depth) {
                // close li/ul tags until we're at current depth
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $ind = $indent . str_repeat('        ', $i);
                    $html .= $ind . '    </li>' . PHP_EOL;
                    $html .= $ind . '</ul>' . PHP_EOL;
                }
                // close previous li tag
                $html .= $myIndent . '    </li>' . PHP_EOL;
            } else {
                // close previous li tag
                $html .= $myIndent . '    </li>' . PHP_EOL;
            }

            // render li tag and page
            $liClasses = array();
            $liClasses[] = $this->getLiClass();
            // Is page active?
            if ($isActive) {
                $liClasses[] = 'active';
            }
            // Is page parent?
            if ($page->hasPages() && (!isset($maxDepth) || $depth < $maxDepth)) {
                $liClasses[] = ($depth == 0) ? 'dropdown': 'dropdown-submenu';
                $page->isDropdown = true;

                if ($depth > 0) {
                    $page->isSubmenu = true;
                }
            }
            // Add CSS class from page to <li>
            if ($addClassToListItem && $page->getClass()) {
                $liClasses[] = $page->getClass();
            }
            $liClass = empty($liClasses) ? '' : ' class="' . implode(' ', $liClasses) . '"';

            $html .= $myIndent . '    <li' . $liClass . '>' . PHP_EOL
                . $myIndent . '        ' . $this->htmlify($page, $escapeLabels, $addClassToListItem) . PHP_EOL;

            // store as previous depth for next iteration
            $prevDepth = $depth;
        }

        if ($html) {
            // done iterating container; close open ul/li tags
            for ($i = $prevDepth+1; $i > 0; $i--) {
                $myIndent = $indent . str_repeat('        ', $i-1);
                $html .= $myIndent . '    </li>' . PHP_EOL
                    . $myIndent . '</ul>' . PHP_EOL;
            }
            $html = rtrim($html, PHP_EOL);
        }

        return $html;
    }

    /**
     * Returns an HTML string containing an 'a' element for the given page if
     * the page's href is not empty, and a 'span' element if it is empty
     *
     * Overrides {@link AbstractHelper::htmlify()}.
     *
     * @param  AbstractPage $page               page to generate HTML for
     * @param  bool         $escapeLabel        Whether or not to escape the label
     * @param  bool         $addClassToListItem Whether or not to add the page class to the list item
     * @return string
     */
    public function htmlify(AbstractPage $page, $escapeLabel = true, $addClassToListItem = false)
    {
        // get label and title for translating
        $label = $page->getLabel();
        $title = $page->getTitle();

        // translate label and title?
        if (null !== ($translator = $this->getTranslator())) {
            $textDomain = $this->getTranslatorTextDomain();
            if (is_string($label) && !empty($label)) {
                $label = $translator->translate($label, $textDomain);
            }
            if (is_string($title) && !empty($title)) {
                $title = $translator->translate($title, $textDomain);
            }
        }

        // get attribs for element
        $element = 'a';
        $extended = '';
        $attribs = array(
            'id'     => $page->getId(),
            'title'  => $title,
            'href'   => '#',
        );

        $class = array();
        if ($addClassToListItem === false) {
            $class[] = $page->getClass();
        }
        if ($page->isDropdown) {
            $attribs['data-toggle'] = 'dropdown';
            $class[] = 'dropdown-toggle';

            if (!$page->isSubmenu && $this->getUseCaret()) {
                $extended = '<span class="caret"></span>';
            }
        }
        if (count($class) > 0) {
            $attribs['class'] = implode(' ', $class);
        }

        // does page have a href?
        $href = $page->getHref();
        if ($href) {
            $attribs['href'] = $href;
            $attribs['target'] = $page->getTarget();
        }

        $html = '<' . $element . $this->htmlAttribs($attribs) . '>';
        if ($escapeLabel === true) {
            $escaper = $this->view->plugin('escapeHtml');
            $html .= $escaper($label);
        } else {
            $html .= $label;
        }

        $html .= $extended;
        $html .= '</' . $element . '>';

        return $html;
    }

}