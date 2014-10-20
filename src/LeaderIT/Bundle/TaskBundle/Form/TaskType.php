<?php

namespace LeaderIT\Bundle\TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text',  array('label' => false, 'attr' => array('placeholder' => 'Название')))
            ->add('description', 'textarea',  array('label' => false, 'attr' => array('placeholder' => 'Описание')))
            //->add('subtask')
            //->add('link')
            //->add('date', 'date', array('label' => false))

            //->add('type')//, 'integer',  array('label' => 'Тип'))
            ->add('type', 'choice',  array('label' => false, 'choices'   => array(
                    '1' => 'Критическая',
                    '2' => 'Важная',
                    '3' => 'Обычная'
                )))
            //->add('priority')
            //->add('subtasks')
            //->add('folder')
            //->add('done')
            //->add('transfer')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LeaderIT\Bundle\TaskBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'leaderit_bundle_taskbundle_task';
    }
}
