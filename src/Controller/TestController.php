<?php

namespace App\Controller;

use Datetime;
use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Genre;
use App\Entity\Auteur;
use App\Entity\Emprunteur;
use App\Entity\Emprunt;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/user', name: 'app_test_user')]
    public function user(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $userRepository = $em->getRepository(User::class);

        /* Requêtes de lecture : */

        /* - la liste complète de tous les utilisateurs (de la table `user`), triée par ordre alphabétique d'email */
        $users = $userRepository->findAll();



        /* - les données de l'utilisateur dont l'id est `1` */
        $user1 = $userRepository->find(1);

        /* - les données de l'utilisateur dont l'email est `foo.foo@example.com` */
        $userMail = $userRepository->findOneByEmail(
            [
                'email' => 'foo.foo@example.com'
            ]
        );
        /* - la liste des utilisateurs dont l'attribut `roles` contient le mot clé `ROLE_USER`, triée par ordre alphabétique d'email */
        $userRole = $userRepository->findUsersByRole('user');

        /* - la liste des utilisateurs inactifs (c-à-d dont l'attribut `enabled` est égal à `false`), triée par ordre alphabétique d'email */
        $userInactive = $userRepository->findUsersByActivity(false);


        return $this->render('test/user.html.twig', [
            'users' => $users,
            'user1' => $user1,
            'userMail' => $userMail,
            'userRole' => $userRole,
            'userInactive' => $userInactive,
        ]);
    }

    #[Route('/livre', name: 'app_test_livre')]
    public function livre(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $livreRepository = $em->getRepository(Livre::class);
        $genreRepository = $em->getRepository(Genre::class);
        $auteurRepository = $em->getRepository(Auteur::class);


        /* Requêtes de lecture : */

        /* - la liste complète de tous les livres, triée par ordre alphabétique de titre */
        $livres = $livreRepository->findAllBook();

        /* - les données du livre dont l'id est `1` */
        $livre1 = $livreRepository->find(1);

        /* - la liste des livres dont le titre contient le mot clé `lorem`, triée par ordre alphabétique de titre */
        $livreLorem = $livreRepository->findByKeyword('lorem');

        /* - la liste des livres dont l'id de l'auteur est `2`, triée par ordre alphabétique de titre */
        $livreAuteur = $livreRepository->findByAuteur(2);

        /* - la liste des livres dont le genre contient le mot clé `roman`, triée par ordre alphabétique de titre */
        $genreRoman = $livreRepository->findByGenre('roman');

        /* ajouter un nouveau livre */

        // - titre : Totum autem id externum
        // - année d'édition : 2020
        // - nombre de pages : 300
        // - code ISBN : 9790412882714
        // - auteur : Hugues Cartier (id `2`)
        // - genre : science-fiction (id `6`) 

        /* Requêtes de création : */

        $auteur = $auteurRepository->find(2);
        $genre = $genreRepository->find(6);

        // Ajout d'un nouveau livre
        $newLivre = new Livre();
        $newLivre->setTitre('Totum autem id externum');
        $newLivre->setAnneeEdition(2020);
        $newLivre->setNombrePage(300);
        $newLivre->setCodeIsbn(9790412882714);
        $newLivre->setAuteur($auteur);
        $newLivre->addGenre($genre);
        $em->persist($newLivre);
        $em->flush();

        /* Requêtes de mise à jour : */

        
        //  - modifier le livre dont l'id est `2`
        $livre2 = $livreRepository->find(2);
        //   - titre : Aperiendum est igitur
        $livre2->setTitre('Aperiendum est igitur');
        //   - genre : roman d'aventure (id `5`)
        $livre2->addGenre($genre);
        $em->persist($livre2);
        $em->flush();

        /* Requêtes de suppression : */

        //   - supprimer le livre dont l'id est `123` 

        $livre3 = $livreRepository->find(123);
        $em->remove($livre3);
        $em->flush();


        return $this->render('test/livre.html.twig', [
            'livres' => $livres,
            'livre1' => $livre1,
            'livreLorem' => $livreLorem,
            'livreAuteur' => $livreAuteur,
            'genreRoman' => $genreRoman,
            'newLivre' => $newLivre,
        ]);
    }



    /* ### Les emprunteurs */

    /* Requêtes de lecture : */
    #[Route('/emprunteur', name: 'app_test_emprunteur')]
    public function emprunteur(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $emprunteurRepository = $em->getRepository(Emprunteur::class);
        $userRepository = $doctrine->getRepository(User::class);



        // - la liste complète des emprunteurs, triée par ordre alphabétique de nom et prénom
        $emprunteurs = $emprunteurRepository->findAll();

        // - les données de l'emprunteur dont l'id est `3`
        $emprunteur3 = $emprunteurRepository->findById(3);

        // - les données de l'emprunteur qui est relié au user dont l'id est `3`
        $user3 = $userRepository->find(3);

        // - la liste des emprunteurs dont le nom ou le prénom contient le mot clé `foo`, triée par ordre alphabétique de nom et prénom
        $emprunteursFoo = $emprunteurRepository->findByEmprunteur('foo');

        // - la liste des emprunteurs dont le téléphone contient le mot clé `1234`, triée par ordre alphabétique de nom et prénom
        $emprunteursPhone = $emprunteurRepository->findByPhone('1234');

        // Liste des emprunteurs dont la date de création est antérieure au 01/03/2021 exclu (c-à-d strictement plus petit), triée par ordre alphabétique de nom et prénom
        $date = new DateTime('2021-03-01');
        $emprunteursCreatedAt = $emprunteurRepository->createAt($date);


        return $this->render('test/emprunteur.html.twig', [
            'emprunteurs' => $emprunteurs,
            'emprunteur3' => $emprunteur3,
            'user3' => $user3,
            'emprunteursFoo' => $emprunteursFoo,
            'emprunteursPhone' => $emprunteursPhone,
            'emprunteursCreatedAt' => $emprunteursCreatedAt,
        ]);
    }


    #[Route('/emprunt', name: 'app_test_emprunt')]
    public function emprunt(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $empruntRepository = $em->getRepository(Emprunt::class);
        $emprunteurRepository = $em->getRepository(Emprunteur::class);
        $livreRepository = $em->getRepository(Livre::class);

        ### Les emprunts

        /* Requêtes de lecture : */

        // Liste des 10 derniers emprunts au niveau chronologique, triée par ordre **décroissant** de date d'emprunt (le plus récent en premier)
        $empruntDate = $empruntRepository->findByDate();

        // Liste des emprunts de l'emprunteur dont l'id est `2`, triée par ordre **croissant** de date d'emprunt (le plus ancien en premier)
        $empruntByEmprunteur = $empruntRepository->findByEmprunteur(2);

        // Liste des emprunts du livre dont l'id est `3`, triée par ordre **décroissant** de date d'emprunt (le plus récent en premier)
        $empruntByLivre = $empruntRepository->findByLivre(3);

        // Liste des 10 derniers emprunts qui ont été retournés, triée par ordre **décroissant** de date de rendretour (le plus récent en premier)
        $empruntByRetour = $empruntRepository->findByRetour();

        // Liste des emprunts qui n'ont pas encore été retournés (c-à-d dont la date de retour est nulle), triée par ordre **croissant** de date d'emprunt (le plus ancien en premier)
        $empruntNotReturn = $empruntRepository->findByNotRetour();


        /* Requêtes de création : */

        $emprunteur = $emprunteurRepository->find(1);
        $livre = $livreRepository->find(1);

        // - ajouter un nouvel emprunt
        $newEmprunt = new Emprunt();
        // - date d'emprunt : 01/12/2020 à 16h00
        $newEmprunt->setDateEmprunt(new DateTime('2020-12-01 16:00:00'));
        // - date de retour : aucune date
        $newEmprunt->setDateRetour(null);
        // - emprunteur : foo foo (id `1`)
        $newEmprunt->setEmprunteur($emprunteur);
        // - livre : Lorem ipsum dolor sit amet (id `1`)
        $newEmprunt->setLivre($livre);
        $em->persist($newEmprunt);
        $em->flush();



        /* Requêtes de mise à jour : */

        // - modifier l'emprunt dont l'id est `3`
        $emprunt3 = $empruntRepository->find(3);
        // - date de retour : 01/05/2020 à 10h00
        $emprunt3->setDateRetour(new DateTime('2020-05-01 10:00:00'));
        $em->persist($emprunt3);
        $em->flush();


        /* Requêtes de suppression : */

        // - supprimer l'emprunt dont l'id est `42` */
        $emprunt4 = $empruntRepository->find(42);


        return $this->render('test/emprunt.html.twig', [
            'empruntDate' => $empruntDate,
            'empruntByEmprunteur' => $empruntByEmprunteur,
            'empruntByLivre' => $empruntByLivre,
            'empruntByRetour' => $empruntByRetour,
            'empruntNotReturn' => $empruntNotReturn,
            'emprunt3' => $emprunt3,
            'emprunt4' => $emprunt4,
        ]);
    }
}
