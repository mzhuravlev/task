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

    public function postAction(Request $request) {

        $tasks = $request->request->get("tasks");

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('LeaderITTaskBundle:Task');

        $i = 0;

        foreach($tasks as $el) {
            $task = $repository->find($el['id']);
            if($task) {
                $task->setPriority($el['priority']);
                $em->flush();
                $i++;
            }
        }

        $data = array('message' => 'modified '.$i.' tasks');

        return array('data' => $data);
    }

    public function pullAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('LeaderITTaskBundle:Task')->findBy(array('done' => false));
        $date = new \DateTime();

        if($tasks) {
            foreach($tasks as $task) {
                $task->setDate($date);
            }

            $em->flush();
            return array('data'=> array('status' => 'success', 'message' => 'tasks pulled'));
        }

        return array('data'=> array('status' => 'success', 'message' => 'no tasks to pull'));
    }
    public function deleteAction()
    {
        return array('data'=> array('status' => 'failure', 'message' => 'method not defined'));
    }
}
