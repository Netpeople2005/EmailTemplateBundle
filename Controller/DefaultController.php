<?php

namespace Netpeople\EmailTemplateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('EmailTemplateBundle:Default:index.html.twig');
    }

    public function renderTemplateAction()
    {
        $data = json_decode($this->getRequest()->get('data'), TRUE);
        $view = $this->getRequest()->get('view');
        $locale = $this->getRequest()->get('locale');

        $currentLocale = $this->get('translator')->getLocale();
        $this->get('translator')->setLocale($locale);

        $response = $this->render($view, array_merge($data, array($locale)));

        $this->get('translator')->setLocale($currentLocale);

        return $response;
    }

}
