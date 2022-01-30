<?php

namespace App\Entity;

use App\Repository\NoticeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Notice
 * @ORM\Entity(repositoryClass=NoticeRepository::class)
 * @ORM\Table(name="notice", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="email_city", columns={"email", "city"})
 * })
 */
class Notice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     * @ORM\Column(type="string", length=255, name="email")
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255, name="city")
     */
    private $city;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private $temp_limit;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $email_sent_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTempLimit(): ?int
    {
        return $this->temp_limit;
    }

    public function setTempLimit(int $temp_limit): self
    {
        $this->temp_limit = $temp_limit;

        return $this;
    }

    public function getEmailSentAt(): ?\DateTimeInterface
    {
        return $this->email_sent_at;
    }

    public function setEmailSentAt(?\DateTimeInterface $email_sent_at): self
    {
        $this->email_sent_at = $email_sent_at;

        return $this;
    }
}
