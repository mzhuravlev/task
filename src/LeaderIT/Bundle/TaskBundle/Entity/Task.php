<?php

namespace LeaderIT\Bundle\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 */
class Task
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $uid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var boolean
     */
    private $subtask;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var integer
     */
    private $priority;

    /**
     * @var array
     */
    private $subtasks;

    /**
     * @var integer
     */
    private $folder;

    /**
     * @var boolean
     */
    private $done;

    /**
     * @var integer
     */
    private $transfer;

    /**
     * @var string
     */
    private $link;


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
     * Set name
     *
     * @param string $name
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
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
     * Set subtask
     *
     * @param boolean $subtask
     * @return Task
     */
    public function setSubtask($subtask)
    {
        $this->subtask = $subtask;

        return $this;
    }

    /**
     * Get subtask
     *
     * @return boolean 
     */
    public function getSubtask()
    {
        return $this->subtask;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Task
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Task
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set subtasks
     *
     * @param array $subtasks
     * @return Task
     */
    public function setSubtasks($subtasks)
    {
        $this->subtasks = $subtasks;

        return $this;
    }

    /**
     * Get subtasks
     *
     * @return array 
     */
    public function getSubtasks()
    {
        return $this->subtasks;
    }

    /**
     * Set folder
     *
     * @param integer $folder
     * @return Task
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return integer 
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set done
     *
     * @param boolean $done
     * @return Task
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done
     *
     * @return boolean 
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * Set transfer
     *
     * @param integer $transfer
     * @return Task
     */
    public function setTransfer($transfer)
    {
        $this->transfer = $transfer;

        return $this;
    }

    /**
     * Get transfer
     *
     * @return integer 
     */
    public function getTransfer()
    {
        return $this->transfer;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Set description
     *
     * @param string $link
     * @return Task
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }



    public function __construct()
    {
        $this->subtask = false;
        $this->folder = 0;
        $this->done = false;
        $this->transfer = 0;
        $this->type = 3;
        $this->priority = 100+$this->id;
        $this->subtasks = "";
        $this->description = "";
        $this->name = "";
        $this->date = new \DateTime();
        $this->link = "";
        $this->uid = 'user';
    }

    public function serialize() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date->format("ddmmyyyy"),
            'type' => $this->type,
            'priority' => $this->priority,
            'done' => $this->done,
            'transfer' => $this->transfer,
            'subtasks' => $this->subtasks,
        );
    }
}
