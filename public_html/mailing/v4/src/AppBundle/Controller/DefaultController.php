<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $message = (new \Swift_Message('Testing'))
            ->setFrom(array('meganalizarsa@gmail.com'=>'Meganalizar S.A.'))
            ->setTo('mdampuero@gmail.com')
            ->setBody('<html><body><h1>Prueba</h1></body></html>','text/html');
            $this->get('mailer')->send($message); 
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
