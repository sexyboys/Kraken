<?php

namespace Kraken\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ScenarioType Form of Bloc for Scenario model management
 * @author Eric Pidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class ScenarioType extends AbstractType
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
            ->add('execMinutes','integer',array('label'=>'admin.scenario.form.execution','required'=>true))

        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Kraken\UserBundle\Entity\Scenario', 'determineValidationGroups'),
        ));
    }
    public function getName()
    {
        return 'AdminScenarioModel';
    }
}