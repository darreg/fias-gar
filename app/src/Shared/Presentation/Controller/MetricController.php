<?php

namespace App\Shared\Presentation\Controller;

use App\Shared\Domain\Monitoring\MonitorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MetricController extends AbstractController
{
    private MonitorInterface $monitor;

    public function __construct(MonitorInterface $monitor)
    {
        $this->monitor = $monitor;
    }

    /**
     * @Route("/metrics", name="metrics")
     */
    public function index(): Response
    {
        return new Response(
            $this->monitor->expose(),
            200,
            ['Content-Type' => $this->monitor->exposeContentType()]
        );
    }
}
