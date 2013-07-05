<?php

namespace Kraken\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TagType Form of Tag for Task model management
 * @author Eric Pidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class TagType extends AbstractType
{

    public function __construct() {
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            "tagMother" => "tagMother",
            "tagTitle" =>  "tagTitle",
            "tagDate" =>  "tagDate",
            "tagContent" =>  "tagContent"
        );

        $builder
            ->add('name', 'text', array('label'=> 'form.name',
                    "required" => true
                )
            )
            ->add('regex','text',array('label'=>'form.tag.regex',
                                              "required" => true
                                            )
                )
            ->add('type', 'choice', array('choices' => $choices,
                    'multiple' => false,
                    'expanded' => true,
                    'preferred_choices' => array(0),
                    'empty_data'  => 0
                )
            )

        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "Kraken\Entities\Tag",
            'validation_groups' => array('Kraken\Entities\Tag', 'determineValidationGroups'),
        ));
    }
    public function getName()
    {
        return 'TagTaskScenarioModel';
    }
}