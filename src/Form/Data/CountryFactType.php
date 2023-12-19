<?php

namespace App\Form\Data;

use App\Entity\Configuration\ContentType;
use App\Entity\Configuration\FactType;
use App\Entity\Data\CountryFact;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryFactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('factType', EntityType::class, [
                'class' => FactType::class,
                'choice_label' => 'description',
                'placeholder'=>'Choose fact type',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('f')
                        ->addOrderBy('f.description', 'ASC');
                },
            ])
            ->add('contentType', EntityType::class, [
                'class' => ContentType::class,
                'choice_label' => 'description',
                'placeholder'=>'Choose content type',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->addOrderBy('c.description', 'ASC');
                },
            ])
            ->add('content')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CountryFact::class,
        ]);
    }
}
