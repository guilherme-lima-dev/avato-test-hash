<?php

namespace App\Entity;

use App\Repository\AvatoRequestsRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvatoRequestsRepository::class)]
class AvatoRequests implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $requestNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $inputString = null;

    #[ORM\Column(length: 255)]
    private ?string $keyFound = null;

    #[ORM\Column(length: 255)]
    private ?string $hash = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $attempts = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $momentRequest = null;

    #[ORM\Column(length: 255)]
    private ?string $alias = null;

    public function __construct(
        int $requestNumber,
        string $inputString,
        string $keyFound,
        string $hash,
        string $attempts,
        DateTime $momentRequest,
        string $alias
    ){
        $this->requestNumber = $requestNumber;
        $this->inputString = $inputString;
        $this->keyFound = $keyFound;
        $this->hash = $hash;
        $this->attempts = $attempts;
        $this->momentRequest = $momentRequest;
        $this->alias = $alias;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestNumber()
    {
        return $this->requestNumber;
    }

    public function getInputString(): ?string
    {
        return $this->inputString;
    }

    public function getKeyFound(): ?string
    {
        return $this->keyFound;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function getAttempts(): ?string
    {
        return $this->attempts;
    }

    public function getMomentRequest(): ?\DateTime
    {
        return $this->momentRequest;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'requestNumber' => $this->requestNumber,
            'inputString' => $this->inputString,
            'keyFound' => $this->keyFound,
            'hash' => $this->hash,
            'attempts' => $this->attempts,
            'momentRequest' => $this->momentRequest instanceof \DateTimeInterface ? $this->momentRequest->format(\DateTime::RFC3339) : null,
            'alias' => $this->alias,
        ];
    }
}
