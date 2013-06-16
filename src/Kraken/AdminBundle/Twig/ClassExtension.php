<?php

namespace Kraken\AdminBundle\Twig;

class ClassExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'classname' => new \Twig_Filter_Method($this, 'classFilter'),
        );
    }

    public function classFilter($object)
    {
        if($object!=null){
        $result = explode('\\',get_class($object));
        return $result[count($result)-1];
        }
        else return "";
    }

    public function getName()
    {
        return 'class_extension';
    }
}
