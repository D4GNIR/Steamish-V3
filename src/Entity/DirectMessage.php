<?php

namespace App\Entity;

use App\Repository\DirectMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DirectMessageRepository::class)]
class DirectMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'directMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private $createdBy;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'boolean')]
    private $hasBeenSeen = false;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'directMessagesReceiver')]
    #[ORM\JoinColumn(nullable: false)]
    private $receiver;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedBy(): ?Account
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Account $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getHasBeenSeen(): ?bool
    {
        return $this->hasBeenSeen;
    }

    public function setHasBeenSeen(bool $hasBeenSeen): self
    {
        $this->hasBeenSeen = $hasBeenSeen;

        return $this;
    }

    public function getReceiver(): ?Account
    {
        return $this->receiver;
    }

    public function setReceiver(?Account $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }
}
