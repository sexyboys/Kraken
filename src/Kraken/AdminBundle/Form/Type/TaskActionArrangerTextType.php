<?php

namespace Kraken\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TaskActionTranslateType Form of Task management
 * @author Eric Pidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class TaskActionArrangerTextType extends AbstractType
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
                    "required" => true,
                    'attr' => array(
                        'placeholder' => '',
                        'help_block' => ''
                    ))
            )
            ->add('description','textarea',array('label'=>'form.description',
                    "required" => true,
                    "max_length" => 99999999,
                    "trim"=>false
                )
            )
            ->add('xslt','textarea',array('label'=>'admin.task.form.xslt',
                    "required" => true,
                    "max_length" => 99999999,
                    "trim"=>true,
                    'attr' => array(
                        'placeholder' => '',
                        'help_block' => 'form.task.xslt'
                    )
                )
            )
            ->add('separateList','checkbox',array('label'=>'admin.task.form.separate_list','required'=>false))
        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Kraken\Entities\Task\TaskActionArrangerText', 'determineValidationGroups'),
        ));
    }
    public function getName()
    {
        return 'AdminTaskActionArrangerText';
    }
}