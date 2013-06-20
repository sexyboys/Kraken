<?php

namespace Kraken\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TaskCrawlWebType Form of Task management
 * @author Eric Pidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class TaskCrawlWebType extends AbstractType
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
            ->add('multipageRegex','text',array('label'=>'admin.task.form.multipage.regex','required'=>false))
            ->add('multipageLimit','text',array('label'=>'admin.task.form.multipage.limit','required'=>false))
            ->add('linkMoreRegex','text',array('label'=>'admin.task.form.more','required'=>false))

        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Kraken\Entities\Task\TaskCrawlWeb', 'determineValidationGroups'),
        ));
    }
    public function getName()
    {
        return 'AdminTaskCrawlWeb';
    }
}