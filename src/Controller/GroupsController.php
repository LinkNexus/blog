<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/groups', name: 'app_groups_')]
class GroupsController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('groups/index.html.twig', [
            'controller_name' => 'GroupsController',
        ]);
    }
}
