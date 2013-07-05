<?php

namespace Kraken\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ConditionStringType Form of Condition management
 * @author Eric Pidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class ConditionStringType extends AbstractType
{

    public function __construct() {
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label'=> 'form.name',
                    "required" => true
                )
            )
            ->add('description','textarea',array('label'=>'form.description',
                                              "required" => false,
                                              "max_length" => 99999999,
                                              "trim"=>true
                                            )
                )
            ->add('dataString','text',array('label'=>'admin.condition.form.value','required'=>true))
            ->add('positionType','text',array('label'=>'admin.task.form.multipage.limit','required'=>false))
            ->add('positionRegex','text',array('label'=>'admin.task.form.more','required'=>false))
            ->add('sign','text',array('label'=>'admin.task.form.more','required'=>false))

        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }
    public function getName()
    {
        return 'AdminTaskCrawlWeb';
    }
}