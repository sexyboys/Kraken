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
class TaskActionTranslateType extends AbstractType
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
            ->add('keepOriginalContent','checkbox',array('label'=>'admin.task.form.keepcontent','required'=>false))
            ->add('languageOriginal','text',array('label'=>'admin.task.form.language.original','required'=>true))
            ->add('languageNeeded','text',array('label'=>'admin.task.form.language.needed','required'=>true))
            ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Kraken\Entities\Task\TaskActionTranslate', 'determineValidationGroups'),
        ));
    }
    public function getName()
    {
        return 'AdminTaskActionTranslate';
    }
}