<?php

namespace App\Controller;

use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

//@IsGranted({"ROLE_USER"})
/**
 * Class TestController.
 */
class TestController extends AbstractController
{
    /**
     * @Route("/", name="list")
     *
     * @IsGranted({"ROLE_USER"})
     */
    public function index(): Response
    {
        return $this->render('base.html.twig', ['count' => 5]);
    }

    /**
     * @Route("/base/profile", name="profile")
     */
    public function profile(UserService $userService): Response
    {
//        dd($this->getUser());
//        dd($userService->getProfile());

        return $this->render('base.html.twig', ['count' => 5]);
    }

    /**
     * @Route("/base/first", name="test1")
     *
     * @IsGranted({"ROLE_ADMIN"})
     */
    public function first(): JsonResponse
    {
//        dump($_SERVER['PHP_AUTH_USER']);
//        dd($_SERVER['PHP_AUTH_PW']);
//        dd($this->getUser());
        //throw new AccessDeniedException('Unable to access this page!');
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("/api/first", name="test2")
     */
    public function apiFirst(): JsonResponse
    {
//        dd($this->getUser());
        return new JsonResponse([
            'success' => true,
            'code' => 200,
            'name' => $this->getUser()->getUsername(),
        ]);
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

        return $response->setContent('Count:'.$_SESSION['count']);

//        dd('test!!');
//        dd('test!');
        return $this->render('base.html.twig', ['count' => 5]);

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
