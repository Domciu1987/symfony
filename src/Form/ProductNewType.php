<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ProductNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nazwa produktu',
                    'attr' => ['class' => 'form-control block w-full px-2 py-1 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none m-4'],
                ]
            )
            ->add(
                'value',
                IntegerType::class,
                [
                    'label' => 'Ilość przyjęcia',
                    'attr' => ['class' => 'form-control block w-full px-2 py-1 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none m-4'],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Range(
                            [
                                'min' => 1,
                            ]
                        )
                    ]
                ]
            )
            ->add(
                'brochure', 
                FileType::class, 
                [
                    'label' => 'Plik (PDF)',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File(
                            [
                                'maxSize' => '2000k',
                                'mimeTypes' => [
                                    'application/pdf',
                                    'application/x-pdf',
                                ],
                                'mimeTypesMessage' => 'Proszę wybrać plik w formacie PDF',
                            ]
                        )
                    ],
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Potwierdź',
                    'attr' => ['class' => 'save bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-2'],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'maxValue' => 0,
        ]);
    }
}