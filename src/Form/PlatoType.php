<?php

namespace App\Form;

use App\Entity\Plato;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PlatoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nombreDelPlato', TextType::class)
        ->add('peso', IntegerType::class)
        ->add('precio', MoneyType::class)
        ->add('kcal', IntegerType::class)
        ->add('imageFile', FileType::class, [
            'label' => 'Imagen del plato (requerido)',
            'mapped' => false,
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plato::class,
        ]);
    }
}
