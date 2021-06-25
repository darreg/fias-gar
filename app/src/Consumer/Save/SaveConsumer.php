<?php

namespace App\Consumer\Save;

use App\Consumer\Save\Input\Message;
use App\Manager\FiasDbManager;
use Doctrine\DBAL\Logging\SQLLogger;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SaveConsumer implements ConsumerInterface
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private FiasDbManager $fiasDbManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        FiasDbManager $fiasDbManager
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->fiasDbManager = $fiasDbManager;
    }

    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = Message::createFromQueue($msg->getBody());
            $errors = $this->validator->validate($message);
            if ($errors->count() > 0) {
                return $this->reject((string)$errors);
            }
        } catch (JsonException $e) {
            return $this->reject($e->getMessage());
        }

        $tagData = $this->fiasDbManager->rebuildTagData($message->getData(), $message->getTableColumnNames());

        $sqlLogger = $this->disableSqlLogger();
        $this->fiasDbManager->upsert(
            $message->getTableName(),
            $message->getPrimaryKeyName(),
            $tagData,
            $message->getTableColumnNames()
        );
        $this->enableSqlLogger($sqlLogger);

        return self::MSG_ACK;
    }

    private function reject(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT;
    }

    public function disableSqlLogger(): ?SQLLogger
    {
        $configuration = $this->entityManager->getConnection()->getConfiguration();
        if ($configuration === null) {
            return null;
        }
        $sqlLogger = $configuration->getSQLLogger();
        $configuration->setSQLLogger();

        return $sqlLogger;
    }

    public function enableSqlLogger(?SQLLogger $sqlLogger): void
    {
        $configuration = $this->entityManager->getConnection()->getConfiguration();
        if ($configuration === null) {
            return;
        }

        $configuration->setSQLLogger($sqlLogger);
    }
}
