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
<<<<<<< HEAD
    private $types;

    public function __construct($params) {
        $this->types = $params;
=======

    public function __construct() {
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883
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
<<<<<<< HEAD
            ->add('socialType','choice',array('label'=>'admin.task.form.social.type',"choices"=>$this->types,'required'=>true))
        ;
=======
            ;
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883

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