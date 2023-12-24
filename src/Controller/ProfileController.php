<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{

    #[Route('/{username}', name: 'app_profile')]
    #[Template('profile/index.html.twig')]
    public function index(User $user): array
    {
        return [
            'user' => $user
        ];
    }
}
