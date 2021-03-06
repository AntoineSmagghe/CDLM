<?php

namespace App\Entity;

use DateTime;
use App\Entity\Img;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article implements Translatable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "S'il te plait, rentre au moins {{ limit }} caractères."
     * )
     */
    private $title;

    /**
     * @ORM\Column(length=128, unique=true)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $text;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_event;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $format;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\Img", mappedBy="article", cascade={"persist", "remove"})
     */
    private $imgs;

    /**
     * @Assert\All({
     *      @Assert\Image(mimeTypes="image/*")
     * })
     */
    private $imgsFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users")
     */
    private $modified_by;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Assert\All({
     *      @Assert\Url(
     *          message = "l'url {{ value }} n'est pas valide",
     *      )
     * })
     */
    private $api_data = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SocialNetwork", mappedBy="article", cascade={"persist", "remove"})
     * @Assert\Type(type="App\Entity\SocialNetwork")
     * @Assert\Valid
     */
    private $socialNetwork;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Assert\All({
     *      @Assert\Url(
     *          message = "l'url {{ value }} n'est pas valide",
     *      )
     * })
     */
    private $youtube = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Assert\All({
     *      @Assert\Url(
     *          message = "l'url {{ value }} n'est pas valide",
     *      )
     * })
     */
    private $facebook = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Assert\All({
     *      @Assert\Url(
     *          message = "l'url {{ value }} n'est pas valide",
     *      )
     * })
     */
    private $vimeo = [];

    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->imgs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->date_event;
    }

    public function setDateEvent(?\DateTimeInterface $date_event): self
    {
        $this->date_event = $date_event;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;
        
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|Img[]|null
     */
    public function getImgs()
    {
        return $this->imgs;
    }

    public function addImg(Img $img): self
    {
        if (!$this->imgs->contains($img)) {
            $this->imgs[] = $img;
            $img->addArticle($this);
        }

        return $this;
    }

    public function removeImg(Img $img): self
    {
        if ($this->imgs->contains($img)) {
            $this->imgs->removeElement($img);
            $img->removeArticle($this);
        }
        return $this;
    }

    /**
    * @return mixed
    */
    public function getImgsFile()
    {
        return $this->imgsFile;
    }
    
    /**
     * @param mixed $imgsFile
     * @return Article
     */
    public function setImgsFile($images): self
    {
        foreach($images as $image)
        {
            $img = new Img();
            $img->setImgData($image);
            $this->addImg($img);
        }
        return $this;
    }
    
    public function getUser() 
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getModifiedBy(): ?Users
    {
        return $this->modified_by;
    }

    public function setModifiedBy(?Users $modified_by): self
    {
        $this->modified_by = $modified_by;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getApiData(): ?array
    {
        return $this->api_data;
    }

    public function setApiData(?array $api_data): self
    {
        $this->api_data = $api_data;

        return $this;
    }

    public function getSocialNetwork(): ?SocialNetwork
    {
        return $this->socialNetwork;
    }

    public function setSocialNetwork(?SocialNetwork $socialNetwork): self
    {
        $this->socialNetwork = $socialNetwork;

        // set (or unset) the owning side of the relation if necessary
        $newArticle = null === $socialNetwork ? null : $this;
        if ($socialNetwork->getArticle() !== $newArticle) {
            $socialNetwork->setArticle($newArticle);
        }

        return $this;
    }

    public function getYoutube(): ?array
    {
        return $this->youtube;
    }

    public function setYoutube(?array $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getFacebook(): ?array
    {
        return $this->facebook;
    }

    public function setFacebook(?array $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getVimeo(): ?array
    {
        return $this->vimeo;
    }

    public function setVimeo(?array $vimeo): self
    {
        $this->vimeo = $vimeo;

        return $this;
    }
}