<?php

namespace LeaderIT\Bundle\TaskBundle\Controller;

use LeaderIT\Bundle\TaskBundle\Entity\Task;
use LeaderIT\Bundle\TaskBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Template("LeaderITTaskBundle:Default:default.json.twig")
 */
class TaskController extends Controller
{
    private function strposOffset($search, $string, $offset)
    {
        /*** explode the string ***/
        $arr = explode($search, $string);
        /*** check the search is not out of bounds ***/
        switch( $offset )
        {
            case $offset == 0:
                return false;
                break;

            case $offset > max(array_keys($arr)):
                return false;
                break;

            default:
                return strlen(implode($search, array_slice($arr, 0, $offset)));
        }
    }

    public function postAction($id, Request $request)
    {
        $delimeter = "--";

        // получить Task из формы
        $newTask = new Task();
        $form = $this->createForm(new TaskType(), $newTask);
        $form->handleRequest($request);

        $user = $this->get('security.context')->getToken()->getUser()->getUsername();


        $subtask = $request->request->get('subtask');
        $done = $request->request->get('done');

        if ($id != 0) {
            // изменить

            $em = $this->getDoctrine()->getManager();
            $task = $em->getRepository('LeaderITTaskBundle:Task')->findBy(array('id' => $id, 'uid' => $user));
            if (!$task) return array('data' => array('status' => 'failure', 'message' => 'task not found', 'id' => $id));

            if ($done) {
                $done == 'yes' ? $task[0]->setDone(true) : $task[0]->setDone(false);
            } else if ($subtask) {
                $description = $task[0]->getDescription();

                $pos = $this->strposOffset($delimeter, $description, $subtask);

                if(substr($description, $pos+strlen($delimeter), strlen("[+]")) == "[+]"){
                    $description = substr($description, 0, $pos + strlen($delimeter)) . substr($description, $pos + strlen($delimeter)+strlen("[+]"));
                } else {
                    $description = substr($description, 0, $pos + strlen($delimeter)) . "[+]" . substr($description, $pos + strlen($delimeter));
                }

                $task[0]->setDescription($description);


            } else {
                $task[0]->setName($newTask->getName());
                $task[0]->setDescription($newTask->getDescription());
                $task[0]->setType($newTask->getType());
            }

            $em->flush();

            return array('data' => array('status' => 'success', 'message' => 'modified task', 'task' => $task[0]->serialize()));
        } else {
            // добавить новый

            $date = \DateTime::createFromFormat("dmY", $request->request->get('leaderit_bundle_taskbundle_task')['date']);
            if ($date) $newTask->setDate($date);

            $newTask->setUid($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($newTask);
            $em->flush();
            $newTask->setPriority(101+$newTask->getId());
            $em->flush();

            return array('data' => array('status' => 'success', 'message' => 'added new task', 'date' => $date, 'id' => $newTask->getId()));
        }


        return array('data' => array('status' => 'failure', 'message' => 'form not valid'));
    }

    public function getAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser()->getUsername();

        $task = $this->getDoctrine()->getRepository('LeaderITTaskBundle:Task')->findBy(array('id' => $id, 'uid' => $user));

        if ($task) {
            $form = $this->createForm(new TaskType(), $task[0]);
            return $this->render("LeaderITTaskBundle:Default:form.html.twig", array('form' => $form->createView()));
        }

        return array('data' => array('status' => 'failure'));
    }

    public function deleteAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $task = $this->getDoctrine()->getRepository('LeaderITTaskBundle:Task')->findBy(array('id' => $id, 'uid' => $user));
        $delete = 0;

        if ($task) {
            $em->remove($task[0]);
            $em->flush();
            $delete++;
        }

        return array('data' => array('status' => 'success', 'message' => 'delete ' . $delete . ' tasks'));
    }
}
