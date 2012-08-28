<?php

namespace Netpeople\EmailTemplateBundle\Service;

use Netpeople\JangoMailBundle\JangoMail;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Netpeople\JangoMailBundle\Emails\EmailInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Description of EmailTemplate
 *
 * @author manuel
 */
class EmailTemplate
{

    /**
     *
     * @var JangoMail 
     */
    protected $jango;

    /**
     * @var TwigEngine 
     */
    protected $twig;

    /**
     * @var TranslatorInterface 
     */
    protected $trans;

    /**
     * @var ParameterBag 
     */
    protected $parameter;
    protected $view;
    protected $locale;

    public function __construct(JangoMail $jango, TwigEngine $twig, TranslatorInterface $trans)
    {
        $this->jango = $jango;
        $this->twig = $twig;
        $this->trans = $trans;
    }

    public function select($view)
    {
        $this->view = $view;
        return $this;
    }

    public function prepare(ParameterBag $parameter = NULL, $locale = NULL)
    {
        $this->parameter = $parameter ? : new ParameterBag();
        $this->locale = $locale;
        return $this;
    }

    public function send(EmailInterface $email)
    {
        if (!$this->twig->exists($this->view)) {
            //excepcion
            return FALSE;
        }

        //leo y guardo temporalmente el locale actual de la app
        $currentLocale = $this->trans->getLocale();
        if ($this->locale !== NULL) {
            $this->trans->setLocale($this->locale);
        }
        
        //agrego al propio objeto email como variable en la vista
        $this->parameter->set('email', $email);
        
        //seteo el mensaje a enviar
        $email->setMessage($this->twig->render($this->view, $this->parameter->all()));

        //vuelvo a colocar el locale original.
        $this->trans->setLocale($currentLocale);

        //envio el mensaje y retorno la respuesta de jango
        return $this->jango->send($email);
    }

}