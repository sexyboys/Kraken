<?php

namespace Kraken\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TaskSenderSocialConnectType Form of Task management
 * @author Eric Pidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class TaskSenderSocialConnectType extends AbstractType
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
            ->add('login', 'text', array('label'=> 'form.login',
                    "required" => true
                )
            )
            ->add('password', 'text', array('label'=> 'form.pass',
                    "required" => true
                )
            );

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }
    public function getName()
    {
        return 'AdminTaskSenderSocialConnect';
    }
}