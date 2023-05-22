<?php

namespace App\Form;

use App\Entity\Promotions;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PromotionsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_deb', DateType::class, [
                'label' => 'Date de début',
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('date_fin', DateType::class, [
                'label' => 'Date de fin',
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new GreaterThan([
                        'propertyPath' => 'parent.all[date_deb].data',
                        'message' => 'La date de fin doit être ultérieure à la date de début.',
                    ]),
                ],
            ])
            ->add('pourcentage', IntegerType::class, [
                'label' => 'Pourcentage',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotions::class,
        ]);
    }

    public function validateDates($data, ExecutionContextInterface $context)
    {
        $dateDebut = $data['date_deb'];
        $dateFin = $data['date_fin'];

        if ($dateDebut > $dateFin) {
            $context->buildViolation('La date de fin ne peut pas être antérieure à la date de début.')
                ->atPath('date_fin')
                ->addViolation();
        }
    }
}
