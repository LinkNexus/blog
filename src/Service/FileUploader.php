<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private string $entity;
    private readonly SluggerInterface $slugger;

    public function __construct(
        string $entity,
        SluggerInterface $slugger
    )
    {
        $this->entity = $entity;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file): string
    {
        $session = new Session();
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename. '-' .uniqid(). '.' .$file->guessExtension();
        $targetDirectory = __DIR__. '/../../public/uploads/'. $this->entity .'s/';

        try {
            $file->move($targetDirectory, $fileName);
            $session->getFlashBag()->add('success', 'File uploaded with success');
            return $fileName;
        } catch (FileException $e) {
            $session->getFlashBag()->add('error', 'File upload failed');
        }
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

}