<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Counter\Entity\Counter;
use App\DataLoad\Domain\Counter\Repository\CounterRepositoryInterface;
use App\DataLoad\Domain\Counter\Service\IncompleteCounterFinderInterface;

class IncompleteCounterFinder implements IncompleteCounterFinderInterface
{
    private CounterRepositoryInterface $counterRepository;

    public function __construct(CounterRepositoryInterface $counterRepository)
    {
        $this->counterRepository = $counterRepository;
    }

    /**
     * @return list<Counter>
     */
    public function find(): array
    {
        $incompleteCounters = [];
        $counters = $this->counterRepository->findAll();
        foreach($counters as $counter) {
            if ($counter->isFinished()) {
                continue;
            }
            if (
                (time() - $counter->getUpdatedAt()->getTimestamp()) > IncompleteCounterFinderInterface::EXPIRE_INTERVAL
            ) {
                continue;
            }
            $incompleteCounters[] = $counter;
        }

        return $incompleteCounters;
    }

    public function check(): bool
    {
        $counters = $this->find();
        return \count($counters) > 0;
    }
}