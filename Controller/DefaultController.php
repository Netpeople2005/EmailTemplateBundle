<?php

namespace Netpeople\EmailTemplateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('EmailTemplateBundle:Default:index.html.twig', array('name' => $name));
    }
}
