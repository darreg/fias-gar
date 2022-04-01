<?php

declare(strict_types=1);

namespace App\Auth\Presentation\Controller;

use App\Auth\Application\UseCase\Join\Confirm\Command as ConfirmCommand;
use App\Auth\Application\UseCase\Join\Request\Command as RequestCommand;
use App\Auth\Infrastructure\Form\JoinForm;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoinController extends AbstractController
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/join", methods={"GET"}, name="join_by_email")
     */
    public function getJoinByEmailForm(): Response
    {
        $command = new RequestCommand();
        $form = $this->createForm(JoinForm::class, $command);
        return $this->render('auth/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/join", methods={"POST"})
     */
    public function saveJoinByEmailForm(Request $request): Response
    {
        $command = new RequestCommand();
        $form = $this->createForm(JoinForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->dispatch($command);
                return $this->redirectToRoute('main');
            } catch (DomainException $e) {
                echo $e->getMessage();
            }
        }
        return $this->render('auth/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm/{token}", name="auth_join_confirm")
     */
    public function confirm(string $token): Response
    {
        $command = new ConfirmCommand($token);
        try {
            $this->commandBus->dispatch($command);
            return $this->redirectToRoute('main');
        } catch (DomainException $e) {
            echo $e->getMessage();
            return $this->redirectToRoute('main');
        }
    }
}
