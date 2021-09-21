<?php

namespace App\Form;

use App\Entity\ProjectTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\DBAL\Types\TextType;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('timeOfProject', \Symfony\Component\Form\Extension\Core\Type\TextType::class);
        $builder->get('timeOfProject')
            ->addModelTransformer(new CallbackTransformer(
                function ($timeOfProjectAsArray) {
                    // transform the array to a string
                    if($timeOfProjectAsArray !==null){
                        return implode(', ', $timeOfProjectAsArray);
                    }
                },
                function ($timeOfProjectAsArray) {
                    // transform the string back to an array
                    return explode(', ', $timeOfProjectAsArray);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectTime::class,
            'csrf_protection' => false
        ]);
    }
}
