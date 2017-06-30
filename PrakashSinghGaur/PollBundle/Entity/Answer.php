<?php

namespace PrakashSinghGaur\PollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="PrakashSinghGaur\PollBundle\Repository\AnswerRepository")
 */
class Answer
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
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @var bool
     *
     * @ORM\Column(name="yes", type="boolean")
     */
    private $yes;

    /**
     * @var string
     *
     * @ORM\Column(name="no", type="boolean")
     */
    private $no;

    /**
     * @var string
     *
    * @ORM\Column(name="not_sure", type="boolean")
     */
    private $notSure;

    /**
     * @var datetime
     *
     * @ORM\Column(name="createdOn", type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="answers")
     * @ORM\JoinColumn(name="createdby", referencedColumnName="id")
     */
    private $createdBy;


    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set yes
     *
     * @param boolean $yes
     *
     * @return Answer
     */
    public function setYes($yes)
    {
        $this->yes = $yes;

        return $this;
    }

    /**
     * Get yes
     *
     * @return boolean
     */
    public function getYes()
    {
        return $this->yes;
    }

    /**
     * Set no
     *
     * @param boolean $no
     *
     * @return Answer
     */
    public function setNo($no)
    {
        $this->no = $no;

        return $this;
    }

    /**
     * Get no
     *
     * @return boolean
     */
    public function getNo()
    {
        return $this->no;
    }

    /**
     * Set notSure
     *
     * @param boolean $notSure
     *
     * @return Answer
     */
    public function setNotSure($notSure)
    {
        $this->notSure = $notSure;

        return $this;
    }

    /**
     * Get notSure
     *
     * @return boolean
     */
    public function getNotSure()
    {
        return $this->notSure;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Answer
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set question
     *
     * @param \PrakashSinghGaur\PollBundle\Entity\Question $question
     *
     * @return Answer
     */
    public function setQuestion(\PrakashSinghGaur\PollBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \PrakashSinghGaur\PollBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Answer
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
