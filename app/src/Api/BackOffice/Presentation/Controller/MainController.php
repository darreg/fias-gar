<?php

namespace App\Api\BackOffice\Presentation\Controller;

use App\Auth\Domain\User\Entity\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
final class MainController extends AbstractController
{
    /**
     * @Route("/", name="admin_main")
     */
    public function index(): Response
    {
        return new Response('<html><body>Admin!</body></html>');
    }
}
