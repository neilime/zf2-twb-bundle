<?php

namespace TwbBundle\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Hold options for TwbBundle module
 */
class ModuleOptions extends AbstractOptions
{
    protected $ignoredViewHelpers;
    
    protected $classMap;

    protected $typeMap;
    
    /**
     * Constructor
     * 
     * @param array|Traversable|null $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function getIgnoredViewHelpers()
    {
        return $this->ignoredViewHelpers;
    }
    public function setIgnoredViewHelpers($ignoredViewHelpers)
    {
        $this->ignoredViewHelpers = $ignoredViewHelpers;
    }
    
    public function getClassMap()
    {
        return $this->classMap;
    }
    public function setClassMap($classMap)
    {
        $this->classMap = $classMap;
    }

    public function getTypeMap()
    {
        return $this->typeMap;
    }
    public function setTypeMap($typeMap)
    {
        $this->typeMap = $typeMap;
    }    
}
