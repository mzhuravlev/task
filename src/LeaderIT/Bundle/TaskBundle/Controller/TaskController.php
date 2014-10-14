<?php

namespace LeaderIT\Bundle\TaskBundle\Controller;

use LeaderIT\Bundle\TaskBundle\Entity\Task;
use LeaderIT\Bundle\TaskBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Template("LeaderITTaskBundle:Default:default.json.twig")
 */

class TaskController extends Controller
{
    public function postAction($id, Request $request)
    {
        // получить Task из формы
        $newTask = new Task();
        $form = $this->createForm(new TaskType(), $newTask);
        $form->handleRequest($request);

        if($form->isValid()) {


            if($id != 0) {
                // изменить

                $task = $this->getDoctrine()->getRepository('LeaderITTaskBundle:Task')->find($id);
                if(!$task) return array('data'=> array('status' => 'failure', 'message' => 'task not found', 'id' => $id));



                return array('data'=> array('status' => 'success', 'message' => 'modified task', 'id' => $id));
            } else {
                // добавить новый

                $em = $this->getDoctrine()->getManager();
                $em->persist($newTask);
                $em->flush();

                return array('data'=> array('status' => 'success', 'message' => 'added new task', 'id' => $newTask->getId()));
            }

        }


        return array('data'=> array('status' => 'failure', 'message' => 'form not valid'));
    }

    public function getAction($id)
    {
        $task = $this->getDoctrine()->getRepository('LeaderITTaskBundle:Task')->find($id);

        if($task) {
            return array('data'=> array('status' => 'success', 'message' => 'get task', 'id' => $id));
        }

        return array('data'=> array('status' => 'failure'));
    }

    public function deleteAction($id)
    {

        return array();
    }
}
