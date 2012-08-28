<?php

namespace Netpeople\EmailTemplateBundle\Service;

use Netpeople\JangoMailBundle\JangoMail;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Netpeople\JangoMailBundle\Emails\EmailInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Netpeople\EmailTemplateBundle\Exception\EmailTemplateException;

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
    protected $parameters;
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

    public function prepare(array $parameters = array(), $locale = NULL)
    {
        $this->parameter = $parameters;
        $this->locale = $locale;
        return $this;
    }

    public function send(EmailInterface $email)
    {
        if (!$this->view) {
            throw new EmailTemplateException(sprintf("Debe establecer una vista a enviar por correo"));
        }

        if (!$this->twig->exists($this->view)) {
            throw new EmailTemplateException(sprintf("No existe la vista <b>$this->view</b> para enviar por correo."));
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

        if (empty($email->getMessage())) {
            throw new EmailTemplateException(sprintf("La vista <b>$this->view</b> no tiene ningun contenido a Mandar"));
        }

        //envio el mensaje y retorno la respuesta de jango
        return $this->jango->send($email);
    }

    /**
     * Devuelve el error que contiene el servicio jango_mail
     */
    public function getError()
    {
        return $this->jango->getError();
    }

}