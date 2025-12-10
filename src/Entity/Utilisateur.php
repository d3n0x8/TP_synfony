<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Collect;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $person = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?string $dateNaissance = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $imageProfil = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Collect::class, orphanRemoval: true)]
    private Collection $collects;

    public function __construct()
    {
        $this->collects = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerson(): ?string
    {
        return $this->person;
    }

    public function setPerson(string $person): static
    {
        $this->person = $person;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getDateNaissance(): ?string
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?string $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getImageProfil(): ?string
    {
        return $this->imageProfil;
    }

    public function setImageProfil(?string $imageProfil): static
    {
        $this->imageProfil = $imageProfil;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return Collection<int, Collect>
     */
    public function getCollects(): Collection
    {
        return $this->collects;
    }

    public function addCollect(Collect $collect): static
    {
        if (!$this->collects->contains($collect)) {
            $this->collects->add($collect);
            $collect->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCollect(Collect $collect): static
    {
        if ($this->collects->removeElement($collect)) {
            if ($collect->getUtilisateur() === $this) {
                $collect->setUtilisateur(null);
            }
        }

        return $this;
    }
}
