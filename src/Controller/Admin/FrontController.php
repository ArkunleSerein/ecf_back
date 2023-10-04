<?php

namespace App\Controller;

use App\Entity\user;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FrontController extends AbstractController
{
    #[Route('/', name: 'app_admin_home')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('front/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}', name: 'app_front_user_show')]
    public function userShow(user $user): Response
    {

        return $this->render('front/user_show.html.twig', [
            'user' => $user,
        ]);
    }
}
