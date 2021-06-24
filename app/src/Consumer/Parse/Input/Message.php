<?php

namespace App\Consumer\Parse\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class Message
{
    /**
     * @Assert\Length(max = 120)
     * @Assert\NotBlank
     */
    private string $fileToken;

    /**
     * @Assert\NotBlank
     */
    private string $xml;

    public static function createFromQueue(string $messageBody): self
    {
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->fileToken = $message['fileToken'];
        $result->xml = $message['xml'];

        return $result;
    }

    public function getFileToken(): string
    {
        return $this->fileToken;
    }

    public function getXml(): string
    {
        return $this->xml;
    }
}
