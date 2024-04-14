<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideosController extends AbstractController
{
    #[Route('/videos', name: 'app_videos')]
    public function index(): Response
    {
        return $this->render('videos/index.html.twig', [
            'controller_name' => 'VideosController',
        ]);
    }
}
