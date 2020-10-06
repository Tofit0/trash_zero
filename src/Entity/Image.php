<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
//use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\Table(name="tz_image")
 * @ORM\HasLifecycleCallbacks
 *
 * @Vich\Uploadable
 *
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="imageName", size="imageSize", mimeType="imageMime", originalName="imageOriName")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int|null
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string")
     *
     * @var \string|null
     */
    private $imageMime;

    /**
     * @ORM\Column(type="string")
     *
     * @var \string|null
     */
    private $imageOriName;

    /**
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

//    /**
//     * @Assert\File(
//     *     mimeTypes = {"application/pdf", "application/x-pdf", "image/png", "image/jpeg", "image/svg+xml"},
//     *     mimeTypesMessage = "error_msg.image.error_format"
//     * )
//     */
//    private $file;
//
//
//    // In order to stock the filename temporaly
//    private $tempFilename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

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
        //dump($imageFile);
        //die;
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();

            $this->setImageMime($this->imageFile->getMimeType());
            $this->setImageName($this->imageFile->getFilename());
            $this->setImageOriName($this->imageFile->getFilename());
            $this->setAlt($this->imageFile->getFilename());
            $this->setUrl($this->imageFile->getPath());
            $this->setImageSize($this->imageFile->getSize());
            $this->setImageSize($this->imageFile->getSize());

        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

//    public function setFile(UploadedFile $file): self
//    {
//        $this->file = $file;
//
//        //check if we already had a file
//        if (null !== $this->url) {
//            //Keeping the file extension for later
//            $this->tempFilename = $this->url;
//
//            //Reinializing values
//            $this->url = null;
//            $this->alt = null;
//        }
//        return $this;
//    }
//
//    /**
//     * @ORM\PrePersist()
//     * @ORM\PreUpdate()
//     */
//    public function preUpload(): ?self
//    {
//        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
//        if (null === $this->file) {
//            return null;
//        }
//
//        // Le nom du fichier est son id, on doit juste stocker également son extension
//        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
//        $this->url = $this->file->guessExtension();
//
//
//        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
//        $this->alt = $this->file->getClientOriginalName();
//    }
//
//    /**
//     * @ORM\PostPersist()
//     * @ORM\PostUpdate()
//     */
//    public function upload()
//    {
//        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
//        if (null === $this->file) {
//            return;
//        }
//
//        // Si on avait un ancien fichier, on le supprime
//        if (null !== $this->tempFilename) {
//            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
//            if (file_exists($oldFile)) {
//                unlink($oldFile);
//            }
//        }
//
//        // On déplace le fichier envoyé dans le répertoire de notre choix
//        $this->file->move(
//            $this->getUploadRootDir(), // Le répertoire de destination
//            $this->id.'.'.$this->url   // Le nom du fichier à créer, ici « id.extension »
//        );
//    }
//
//    /**
//     * @ORM\PreRemove()
//     */
//    public function preRemoveUpload()
//    {
//        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
//        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->url;
//    }
//
//    /**
//     * @ORM\PostRemove()
//     */
//    public function removeUpload()
//    {
//        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
//        if (file_exists($this->tempFilename)) {
//            // On supprime le fichier
//            unlink($this->tempFilename);
//        }
//    }
//
//
//    public function getUploadDir()
//    {
//        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
//        return 'uploads/img';
//    }
//
//    protected function getUploadRootDir()
//    {
//        // On retourne le chemin relatif vers l'image pour notre code PHP
//        return __DIR__.'/../../web/'.$this->getUploadDir();
//    }
//
//    public function getWebPath()
//    {
//        return $this->getUploadDir().'/'.$this->getId().'.'.$this->getUrl();
//    }


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getImageMime(): ?string
    {
        return $this->imageMime;
    }

    public function setImageMime(string $imageMime): self
    {
        $this->imageMime = $imageMime;

        return $this;
    }

    public function getImageOriName(): ?string
    {
        return $this->imageOriName;
    }

    public function setImageOriName(string $imageOriName): self
    {
        $this->imageOriName = $imageOriName;

        return $this;
    }
}
