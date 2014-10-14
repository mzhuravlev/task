<?php

namespace LeaderIT\Bundle\TaskBundle\Controller;

use LeaderIT\Bundle\TaskBundle\DayManager;
use LeaderIT\Bundle\TaskBundle\Entity\Task;
use LeaderIT\Bundle\TaskBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Template("LeaderITTaskBundle:Default:default.json.twig")
 */

class DayController extends Controller
{
    public function getAction($day = 0)
    {
        $dayManager = new DayManager();

        $day == 0 ? $day = new \DateTime() : $day = \DateTime::createFromFormat("ddmmyyyy", $day);
        if(!$day) return array('data'=> array('status' => 'failure', 'message' => 'invalid date'));

        $tasks = $this->getDoctrine()->getRepository('LeaderITTaskBundle:Task')
            ->findBy(array('date' => $day, 'uid' => 'user'));

        $dayManager->loadTasks($tasks);
        $dayManager->processTasks();

        return array('data'=> array('status' => 'success', 'day' => $dayManager->getData()));
    }

    public function postAction()
    {
        return array('data'=> array('status' => 'failure', 'message' => 'method not defined'));
    }
    public function deleteAction()
    {
        return array('data'=> array('status' => 'failure', 'message' => 'method not defined'));
    }
}
