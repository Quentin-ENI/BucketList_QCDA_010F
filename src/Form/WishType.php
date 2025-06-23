<?php

namespace App\Form;

use App\Entity\Wish;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Your idea : "
            ])
            ->add('description', TextareaType::class, [
                "label" => "Please describe it : "
            ])
            ->add('author', TextType::class, [
                "label" => "Your username : "
            ])
            ->add('isPublished', CheckboxType::class, [
                "attr" => ["checked" => "true"],
                "label" => "Published"
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (png or jpg, max 1Mo)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '1024k',
                        extensions: ['png', 'jpg'],
                        extensionsMessage: 'Please upload a valid PDF document',
                    )
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $wish = $event->getData();
                $form = $event->getForm();

                // Vérifie si une image existe
                if ($wish && $wish->getImageFilename()) {
                    $form->add('delete-image', CheckboxType::class, [
                        'label' => 'Delete current image',
                        'mapped' => false,
                        'required' => false,
                    ]);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
