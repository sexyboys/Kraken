<?php

namespace Kraken\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TaskSenderSocialType Form of Task management
 * @author Eric Pidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class TaskSenderSocialType extends AbstractType
{
    private $types;

    public function __construct($params) {
        $this->types = $params;
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
            ->add('socialType','choice',array('label'=>'admin.task.form.social.type',"choices"=>$this->types,'required'=>true))
        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Kraken\UserBundle\Entity\TaskSenderSocial', 'determineValidationGroups'),
        ));
    }
    public function getName()
    {
        return 'AdminTaskSenderSocial';
    }
}