<?php

namespace App\BackOffice\Consumer\Parse;

use App\BackOffice\Consumer\Parse\Input\Message;
use App\BackOffice\DTO\FiasSaveDTO;
use App\BackOffice\Exception\FiasImportException;
use App\BackOffice\Manager\FiasXmlManager;
use App\BackOffice\Service\AsyncService;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ParseConsumer implements ConsumerInterface
{
    private ValidatorInterface $validator;
    private AsyncService $asyncService;

    public function __construct(
        ValidatorInterface $validator,
        AsyncService $asyncService
    ) {
        $this->validator = $validator;
        $this->asyncService = $asyncService;
    }

    /**
     * @throws FiasImportException
     */
    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = Message::createFromQueue($msg->getBody());
            $errors = $this->validator->validate($message);
            if ($errors->count() > 0) {
                return $this->reject(implode(PHP_EOL, (array)$errors));
            }
        } catch (JsonException $e) {
            return $this->reject($e->getMessage());
        }

        $data = FiasXmlmanager::parse($message->getXmlTag());

        $message = (new FiasSaveDTO(
            $message->getTableName(),
            $message->getPrimaryKeyName(),
            $message->getTableColumnNames(),
            $data
        ))->toAMQPMessage();
        $this->asyncService->publishToExchange(AsyncService::SAVE, $message);

        return self::MSG_ACK;
    }

    private function reject(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT;
    }
}
