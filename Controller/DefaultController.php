<?php

namespace Netpeople\EmailTemplateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Netpeople\EmailTemplateBundle\Locator\FilesBundle;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $bundles = array_keys($this->get('kernel')->getBundles());

        return $this->render('EmailTemplateBundle:Default:index.html.twig', array(
                    'bundles' => $bundles
                ));
    }

    public function renderTemplateAction()
    {
        $data = (array) json_decode($this->getRequest()->get('data'), TRUE);
        $view = $this->getRequest()->get('view');
        $locale = $this->getRequest()->get('locale');

        $currentLocale = $this->get('translator')->getLocale();
        $this->get('translator')->setLocale($locale);

        $response = $this->render($view, array_merge($data, array($locale)));

        $this->get('translator')->setLocale($currentLocale);

        return $response;
    }

    public function getFilesFromViewAction()
    {
        $bundleName = $this->getRequest()->get('bundle');

        $dir = $this->get('kernel')->locateResource("@$bundleName");

        $files = FilesBundle::getFiles(rtrim($dir, '/') . '/Resources/views/');

        $views = array();
        foreach ($files as $view) {
            $views[] = str_replace('/', ':', substr($view, strpos($view, '/Resources/views/') + 17));
        }
        
        return $this->render('EmailTemplateBundle:Default:view_files.html.twig', array(
                    'files' => $views
                ));

    }

}
