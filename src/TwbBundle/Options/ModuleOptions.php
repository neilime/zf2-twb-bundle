<?php

namespace TwbBundle\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Hold options for TwbBundle module
 */
class ModuleOptions extends AbstractOptions
{
    protected $ignoredViewHelpers;
    
    protected $classMap = [];

    protected $typeMap = [];
    
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
    public function setClassMap($class_map)
    {
        $this->classMap = $class_map;
    }

    public function getTypeMap()
    {
        return $this->typeMap;
    }
    public function setTypeMap($type_map)
    {
        $this->typeMap = $type_map;
    }    
}
