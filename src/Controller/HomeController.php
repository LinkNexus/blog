<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CreatePostFormType;
use App\Form\SearchFormType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private readonly SluggerInterface $slugger;

    public function __construct(
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    )
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $this->getUser()->getUserIdentifier(),
        ]);

        $post = new Post();
        $createPostForm = $this->createForm(CreatePostFormType::class, $post);
        $createPostForm->handleRequest($request);

        if ($createPostForm->isSubmitted() && $createPostForm->isValid()) {
            $fileUploader = new FileUploader('post', $this->slugger);
            $files = $request->files->all();
            $content = $post->getContent();
            $processedContent = $this->processContentUrls($content, $files, 'post', $fileUploader);

            $post->setUser($user);
            $post->setContent($processedContent);

            $this->entityManager->persist($post);
            $this->entityManager->flush();
        }

        return $this->render('home/index.html.twig', [
            'user' => $user,
            'createPostForm' => $createPostForm->createView(),
        ]);
    }

    public function processContentUrls(
        string $content,
        array $files,
        string $entity,
        FileUploader $fileUploader
    ): string
    {
        // Define the new base URL pointing to the 'files' directory in 'public'
        $baseUrl = '/uploads/'. $entity .'s/';
        // Upload each file and store the mapping from the original URL to the new URL
        $urlMappings = [];

        foreach ($files as $file) {
            $newFileName = $fileUploader->upload($file);
            $urlMappings[$file->getClientOriginalName()] = $baseUrl . $newFileName;
        }

        // Replace the arbitrary URLs in content with the actual URLs
        $content = preg_replace_callback(
            '/<img\s+[^>]*src="([^"]+)"[^>]*>/i',
            function ($matches) use ($urlMappings) {
                $originalSrc = $matches[1];

                foreach ($urlMappings as $key => $urlMapping) {
                    if (str_contains($originalSrc, $key)) {
                        return str_replace($originalSrc, $urlMapping, $matches[0]);
                    }
                }

                return $matches[0];
            },
            $content
        );

        $content = preg_replace_callback(
            '/<video\s+[^>]*src="([^"]+)"[^>]*>/i',
            function ($matches) use ($urlMappings) {
                $originalSrc = $matches[1];

                foreach ($urlMappings as $key => $urlMapping) {
                    if (str_contains($originalSrc, $key)) {
                        return str_replace($originalSrc, $urlMapping, $matches[0]);
                    }
                }

                return $matches[0];
            },
            $content
        );

        $content = preg_replace_callback(
            '/<a\s+[^>]*href="([^"]+)"[^>]*>/i',
            function ($matches) use ($urlMappings) {
                $originalHref = $matches[1];

                foreach ($urlMappings as $key => $urlMapping) {
                    if (str_contains($originalHref, $key)) {
                        return str_replace($originalHref, $urlMapping, $matches[0]);
                    }
                }

                return $matches[0];
            },
            $content
        );

        return $content;
    }
}
