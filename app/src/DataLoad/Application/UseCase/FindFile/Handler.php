<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\FindFile;

use App\DataLoad\Application\Service\XmlFileFinderInterface;
use App\Shared\Domain\Bus\Query\QueryHandlerInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;

class Handler implements QueryHandlerInterface
{
    private XmlFileFinderInterface $finder;

    public function __construct(XmlFileFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    public function __invoke(Query $query): ?ResponseInterface
    {
        $files = $this->finder->find($query->getToken());

        $response = new Response();
        foreach ($files as $file) {
            $response->add($file);
        }

        return $response;
    }
}
