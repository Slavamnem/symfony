<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(): Response
    {
        $response = new Response();
        $response->headers->setCookie(Cookie::create('test-cookie-2', 25, time() + 300));
        $response->headers->setCookie(Cookie::create('test-cookie-3', 285, time() + 300));

        phpinfo();
//        session_start(); session_destroy(); return $response;
        session_start();

        $count = 0;

        if (isset($_SESSION['count'])) {
            $count = $_SESSION['count'];
        }

        $_SESSION['count'] = $count + 1;

        return $response->setContent('Count:' . $_SESSION['count']);

//        dd('test!!');
//        dd('test!');
        return $this->render('base.html.twig', ['count' => 5]);
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
