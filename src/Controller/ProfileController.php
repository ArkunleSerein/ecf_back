<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Emprunteur;
use App\Entity\Emprunt;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile_index')]
    public function index(Emprunteur $emprunteur, User $user): Response
    {
        $this->filterSessionUser($user);
        $emprunteur = $user->getEmprunteur();
        $emprunts = $emprunteur->getEmprunts();



        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'emprunts' => $emprunts,
            'emprunteur' => $emprunteur,
        ]);
    }
    public function filterSessionUser(User $user)
    {

        $sessionUser = $this->getUser();

        if ($sessionUser != $user) {
            // l'utilisateur connecté essaye de consulter le profil d'un autre utilisateur
            throw new NotFoundHttpException();
        }
    }
}
