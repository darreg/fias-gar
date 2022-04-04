<?php

namespace App\Api\Api\Presentation\Controller;

use App\Auth\Domain\User\Entity\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return new Response('<html><body>Welcome!</body></html>');
    }
}
