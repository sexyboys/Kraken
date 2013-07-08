<?php

namespace Kraken\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TaskSenderEmailType Form of Task management
 * @author Eric Pidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class TaskSenderEmailType extends AbstractType
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
            ->add('addSource','checkbox',array('label'=>'admin.task.form.source','required'=>false))
            ->add('stringEmails','text',array('label'=>'admin.task.form.emails','required'=>true))
            ->add('object','text',array('label'=>'admin.task.form.object','required'=>true))
            ->add('content','textarea',array('label'=>'admin.task.form.content',
                    "required" => false,
                    "max_length" => 99999999,
                    "trim"=>true
                )
            )
            ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Kraken\UserBundle\Entity\TaskSenderEmail', 'determineValidationGroups'),
        ));
    }
    public function getName()
    {
        return 'AdminTaskSenderEmail';
    }
}