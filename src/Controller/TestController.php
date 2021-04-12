<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class TestController
 * @package App\Controller
 *
 * @IsGranted({"ROLE_USER"})
 */
class TestController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {
        return $this->render('base.html.twig', ['count' => 5]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(UserService $userService): Response
    {
        dd($userService->getProfile());
        return $this->render('base.html.twig', ['count' => 5]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function test(): Response
    {
        $response = new Response();
        $response->headers->setCookie(Cookie::create('test-cookie-2', 25, time() + 300));
        $response->headers->setCookie(Cookie::create('test-cookie-3', 285, time() + 300));

//        phpinfo();
//        session_start(); session_destroy(); return $response;
//        dd(sys_get_temp_dir());
//        dd(session_save_path());
        session_start();

        dump(session_id());

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
