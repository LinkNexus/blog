<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{

    #[Route('/{id}', name: 'app_profile', requirements: ['id' => '\d+'])]
    #[Template('profile/index.html.twig')]
    public function index(User $user): array
    {
        return [
            'user' => $user
        ];
    }
}
