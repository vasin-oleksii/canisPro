<?php

namespace App\Form;

use App\Entity\Chien;
use App\Entity\Inscription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $seance = $options['seance'];
        
        $builder
            ->add('chien', EntityType::class, [
                'class' => Chien::class,
                'choice_label' => 'nomChien',
                'label' => 'Choisir votre chien',
                'query_builder' => function ($repo) use ($seance) {
                    $inscribed = $repo->createQueryBuilder('c')
                        ->join('c.inscriptions', 'i')
                        ->where('i.seance = :seance')
                        ->setParameter('seance', $seance)
                        ->select('c.id')
                        ->getQuery()
                        ->getResult();
                    
                    $inscribedIds = array_map(fn($item) => $item['id'], $inscribed);
                    
                    $qb = $repo->createQueryBuilder('c');
                    if (!empty($inscribedIds)) {
                        $qb->where('c.id NOT IN (:ids)')
                           ->setParameter('ids', $inscribedIds);
                    }
                    return $qb;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
            'seance' => null,
        ]);
    }
}
