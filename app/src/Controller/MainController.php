<?php

namespace App\Controller;

use App\Repository\AddrobjAdmRepository;
use App\Repository\HouseAdmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        return new Response("<html><body>Welcome</body></html>");
    }
}
