<?php

namespace App\Form;

use App\Entity\Promotions;
use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;

class PromotionsFormType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
            ])
            ->add('products', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'label' => 'Produit',
                'query_builder' => function (ProductsRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->where('c.id IS NOT NULL')
                        ->orderBy('c.name', 'ASC');
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotions::class,
            'constraints' => [
                new Callback([$this, 'validateExistingPromotion']),
            ],
        ]);
    }

    public function validateExistingPromotion($data, ExecutionContextInterface $context): void
    {
        $product = $data->getProducts();
        $date_deb = $data->getDateDeb();
        $date_fin = $data->getDateFin();
        $promotionRepository = $this->entityManager->getRepository(Promotions::class);

        // Check if there is an existing promotion for the selected product
        $existingPromotion = $promotionRepository->findOneBy([
            'products' => $product,
        ]);

        if ($existingPromotion) {
            // Check if the existing promotion overlaps with the new promotion
            if (($date_deb <= $existingPromotion->getDateFin() && $date_deb >= $existingPromotion->getDateDeb())
                || ($date_fin <= $existingPromotion->getDateFin() && $date_fin >= $existingPromotion->getDateDeb())
            ) {
                $context->buildViolation('Une promotion existe déjà pour ce produit pendant la période sélectionnée.')
                    ->atPath('products')
                    ->addViolation();
            }
        }
    }

    public function validateDates($data, ExecutionContextInterface $context)
    {
        // var_dump($data);die;
        $dateDebut = $data['date_deb'];
        $dateFin = $data['date_fin'];

        if ($dateDebut > $dateFin) {
            $context->buildViolation('La date de fin ne peut pas être antérieure à la date de début.')
                ->atPath('date_fin')
                ->addViolation();
        }
    }
}
