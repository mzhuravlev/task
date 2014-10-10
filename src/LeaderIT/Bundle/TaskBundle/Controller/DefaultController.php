<?php

namespace LeaderIT\Bundle\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LeaderITTaskBundle:Default:index.html.twig', array('name' => $name));
    }
}
