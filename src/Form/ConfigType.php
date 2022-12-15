<?php

namespace App\Form;

use App\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('logo', FileType::class, [ 
                'label' => 'Logo (png/jpg)',
                // unmapped means that this field is not associated to any entity property 
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file  
                // every time you edit the Product details 
                'required' => false,
                // unmapped fields can't define their validation using annotation s 
                // in the associated entity, so you can use the PHP constraint cl asses 
                'constraints' => [ 
                new File([ 
                    'maxSize' => '5024k', 
                    'mimeTypes' => [ 
                    'image/png', 
                    'image/jpeg', 
                    ], 
                        'mimeTypesMessage' => 'Please upload a valid picture format', 
                ]) 
                ], 
            ])
            ->add('adresse')
            ->add('numTel')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
