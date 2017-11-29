<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var \AppBundle\Entity\Actor
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Actor")
     */
    private $actors;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="viewCount", type="integer", nullable=true, options={"default":0})
     */
    private $viewCount;

    /**
     * @var array
     *
     */
    private $tags;

    /**
     * @var int
     *
     * @ORM\Column(name="upVotes", type="integer", nullable=true, options={"default":0})
     */
    private $upVotes;

    /**
     * @var int
     *
     * @ORM\Column(name="downVotes", type="integer", nullable=true, options={"default":0})
     */
    private $downVotes;

    /**
     * @var \AppBundle\Entity\Comment
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Comment")
     */
    private $comments;



    /**
     * @var \AppBundle\Entity\Category
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category")
     */
    private $categories;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Movie
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set actors
     *
     * @param \AppBundle\Entity\Actor $actors
     *
     * @return Movie
     */
    public function setActors($actors)
    {
        $this->actors = $actors;

        return $this;
    }

    /**
     * Get actors
     *
     * @return \AppBundle\Entity\Actor
     */
    public function getActors()
    {
        return $this->actors;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Movie
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set viewCount
     *
     * @param integer $viewCount
     *
     * @return Movie
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    /**
     * Get viewCount
     *
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * Set tags
     *
     * @param array $tags
     *
     * @return Movie
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set upVotes
     *
     * @param integer $upVotes
     *
     * @return Movie
     */
    public function setUpVotes($upVotes)
    {
        $this->upVotes = $upVotes;

        return $this;
    }

    /**
     * Get upVotes
     *
     * @return int
     */
    public function getUpVotes()
    {
        return $this->upVotes;
    }

    /**
     * Set downVotes
     *
     * @param integer $downVotes
     *
     * @return Movie
     */
    public function setDownVotes($downVotes)
    {
        $this->downVotes = $downVotes;

        return $this;
    }

    /**
     * Get downVotes
     *
     * @return int
     */
    public function getDownVotes()
    {
        return $this->downVotes;
    }

    /**
     * Set comments
     *
     * @param \AppBundle\Entity\Comment $comments
     *
     * @return Movie
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return \AppBundle\Entity\Comment
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return \AppBundle\Entity\Category
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param \AppBundle\Entity\Category $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }


}

