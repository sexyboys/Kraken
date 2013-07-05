<?php

namespace Kraken\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TaskSenderBlogType Form of Task management
 * @author Eric Pidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class TaskSenderBlogType extends AbstractType
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
            ->add('blogLogin','text',array('label'=>'admin.task.form.blog.login','required'=>true))
            ->add('blogPass','text',array('label'=>'admin.task.form.blog.pass','required'=>true))
            ->add('blogEmail','text',array('label'=>'admin.task.form.blog.email','required'=>true))
            ->add('blogLink','text',array('label'=>'admin.task.form.blog.link','required'=>true))

            ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Kraken\Entities\Task\TaskSenderBlog', 'determineValidationGroups'),
        ));
    }
    public function getName()
    {
        return 'AdminTaskSenderBlog';
    }
}