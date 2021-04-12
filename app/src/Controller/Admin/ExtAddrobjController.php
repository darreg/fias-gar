<?php

namespace App\Controller\Admin;

use App\DTO\ExtAddrobjDTO;
use App\Form\Type\ExtAddrobjType;
use App\Service\ExtAddrobjService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin/extaddrobj")
 */
class ExtAddrobjController extends AbstractController
{
    private ExtAddrobjService $extAddrobjService;
    private SerializerInterface $serializer;

    public function __construct(
        ExtAddrobjService $extAddrobjService,
        SerializerInterface $serializer
    ) {
        $this->extAddrobjService = $extAddrobjService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/new", name="extaddrobj-new", methods={"GET"})
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(ExtAddrobjType::class);
        return $this->render('admin/extaddrobj/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="extaddrobj-new-submit", methods={"POST"})
     */
    public function newSubmit(Request $request): Response
    {
        $form = $this->createForm(ExtAddrobjType::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = (array)$form->getData();
            $extAddrobjDto = ExtAddrobjDTO::fromArray($data);
            $objectid = $this->extAddrobjService->add($extAddrobjDto);
            return $this->redirectToRoute('extaddrobj-edit', ['objectid' => $objectid]);
        }

        return $this->render('admin/extaddrobj/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{objectid}", name="extaddrobj-edit", requirements={"objectid":"\d+"})
     */
    public function edit(Request $request, int $objectid): Response
    {
        $extAddrobj = $this->extAddrobjService->getOne($objectid);
        if ($extAddrobj === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $extAddrobjArray = $this->serializer->normalize(
            $extAddrobj,
            null,
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['extAddrobj']]
        );

        $extAddrobjDto = ExtAddrobjDTO::fromArray($extAddrobjArray);
        $form = $this->createForm(ExtAddrobjType::class, $extAddrobjDto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ExtAddrobjDTO $data */
            $data = $form->getData();
            $this->extAddrobjService->update($extAddrobj, $data);

            return $this->redirectToRoute('extaddrobj-edit', ['objectid' => $extAddrobj->getObjectid()]);
        }

        return $this->render('admin/extaddrobj/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
