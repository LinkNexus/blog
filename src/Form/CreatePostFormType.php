<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\ChoiceFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\CodeEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePostFormType extends AbstractType
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    public function __construct(EntityManagerInterface $entityManager, Security $security) {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $this->security->getUser()->getUserIdentifier(),
        ]);

        $builder
            ->add('content', TextareaType::class, [
                'attr' => [
                    'contenteditable' => 'true',
                    'placeholder' => 'What\'s on your mind,'. $user->getUsername() .'?',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Post',
            ])
            ->add('restrictionType', ChoiceType::class, [
                'choices' => [
                    'Public' => null,
                    'Friends' => 'friends',
                    'Friends except...' => 'friends_except...',
                    'Specific friends' => 'specific_friends',
                    'Only me' => 'only_me',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Post::class,
            'allow_extra_fields' => true
        ]);
    }
}
