<?php
namespace Odl\AuthBundle\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/** @ODM\Document */
class Image
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field */
    private $name;

    /** @ODM\File */
    private $file;

    /** @ODM\Field */
    private $uploadDate;

    /** @ODM\Field */
    private $length;

    /** @ODM\Field */
    private $chunkSize;

    /** @ODM\Field */
    private $md5;

    /**
     * @ODM\Field(type="date")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @ODM\Field(type="date")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    public function getId()
    {
        return $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return the $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

	/**
     * @return the $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

	/**
     * @param field_type $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

	/**
     * @param field_type $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

	public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }
}