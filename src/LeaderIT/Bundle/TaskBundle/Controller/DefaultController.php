<?php

namespace LeaderIT\Bundle\TaskBundle\Controller;

use LeaderIT\Bundle\TaskBundle\Entity\Task;
use LeaderIT\Bundle\TaskBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $task = new Task();
        $form = $this->createForm(new TaskType(), $task);
        return $this->render('LeaderITTaskBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

    public function redirectAction() {
        return $this->redirect($this->generateUrl('task_homepage'));
    }
}
