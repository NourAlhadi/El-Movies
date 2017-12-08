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
     * @ORM\Column(name="view_count", type="integer", nullable=true, options={"default":0})
     */
    private $viewCount;


    /**
     * @var int
     *
     * @ORM\Column(name="total_counts", type="integer", nullable=true, options={"default":0})
     */
    private $totalCounts;

    /**
     * @var int
     *
     * @ORM\Column(name="stars_count", type="integer", nullable=true, options={"default":0})
     */
    private $starsCount;

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
     * @var string
     *
     * @ORM\Column(name="main_poster", type="string",nullable=true)
     */
    private $mainPoster;

    /**
     * @var string
     *
     * @ORM\Column(name="index_poster", type="string",nullable=true)
     */
    private $indexPoster;

    public function __construct()
    {
        if (is_null($this->viewCount )) $this->viewCount = 0;
        if (is_null($this->totalCounts )) $this->totalCounts = 0;
        if (is_null($this->starsCount )) $this->starsCount = 0;
    }

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
     * @return int
     */
    public function getTotalCounts()
    {
        return $this->totalCounts;
    }

    /**
     * @param int $totalCounts
     */
    public function setTotalCounts($totalCounts)
    {
        $this->totalCounts = $totalCounts;
    }

    /**
     * @return int
     */
    public function getStarsCount()
    {
        return $this->starsCount;
    }

    /**
     * @param int $starsCount
     */
    public function setStarsCount($starsCount)
    {
        $this->starsCount = $starsCount;
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

    /**
     * @return string
     */
    public function getMainPoster()
    {
        return $this->mainPoster;
    }

    /**
     * @param string $mainPoster
     */
    public function setMainPoster($mainPoster)
    {
        $this->mainPoster = $mainPoster;
    }

    /**
     * @return string
     */
    public function getIndexPoster()
    {
        return $this->indexPoster;
    }

    /**
     * @param string $indexPoster
     */
    public function setIndexPoster($indexPoster)
    {
        $this->indexPoster = $indexPoster;
    }

}

