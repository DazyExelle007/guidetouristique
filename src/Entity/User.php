<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    use Timestampable;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\NotBlank
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Pin::class, mappedBy="user", orphanRemoval=true)
     */
    private $pins;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank
     * 
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * 
     */
    private $parcPlace;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * 
     */
    private $languageUse;

      // ... other fields

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="guide_image", fileNameProperty="imageGuide")
     * 
     * @var File|null
     */
    private $imageFile;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageGuide;

    public function __construct()
    {
        $this->pins = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Pin>
     */
    public function getPins(): Collection
    {
        return $this->pins;
    }

    public function addPin(Pin $pin): self
    {
        if (!$this->pins->contains($pin)) {
            $this->pins[] = $pin;
            $pin->setUser($this);
        }

        return $this;
    }

    public function removePin(Pin $pin): self
    {
        if ($this->pins->removeElement($pin)) {
            // set the owning side to null (unless already changed)
            if ($pin->getUser() === $this) {
                $pin->setUser(null);
            }
        }

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() .' ' . $this->getLastName();
    }

    public function gravatarUrl(int $size = 200)
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($this->getEmail()))).'/?s='.$size;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getParcPlace(): ?string
    {
        return $this->parcPlace;
    }

    public function setParcPlace(string $parcPlace): self
    {
        $this->parcPlace = $parcPlace;

        return $this;
    }

    public function getLanguageUse(): ?string
    {
        return $this->languageUse;
    }

    public function setLanguageUse(string $languageUse): self
    {
        $this->languageUse = $languageUse;

        return $this;
    }
        /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdatedAt(new \DateTimeImmutable());
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    public function getImageGuide(): ?string
    {
        return $this->imageGuide;
    }

    public function setImageGuide(?string $imageGuide): self
    {
        $this->imageGuide = $imageGuide;

        return $this;
    }

    public function serialize()
{
    return serialize(array(
        $this->id,
        $this->email,
        $this->password,
        // see section on salt below
        // $this->salt,
    ));
}

public function unserialize($serialized)
{
    list (
        $this->id,
        $this->email,
        $this->password,
        // see section on salt below
        // $this->salt
    ) = unserialize($serialized);
}

}
