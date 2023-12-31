<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $nb_books = null;

    #[ORM\Column(length: 255)]
    private ?string $Relation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Author
    {
        $this->id = $id;
    
        return $this;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNbBooks(): ?int
    {
        return $this->nb_books;
    }

    public function setNbBooks(?int $nbbooks): Author
    {
        $this->nb_books = $nbbooks;
    
        return $this;
    }

    public function getRelation(): ?string
    {
        return $this->Relation;
    }

    public function setRelation(string $Relation): static
    {
        $this->Relation = $Relation;

        return $this;
    }
    
}
