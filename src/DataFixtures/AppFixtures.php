<?php
namespace App\DataFixtures;
use App\Entity\Collect;
use App\Entity\Genre;
use App\Entity\Editeur;
use App\Entity\JeuVideo;
use App\Entity\JeuVideoPs;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer les genres
// Genre ACTION : Jeux d'action, combat
        $genreAction = new Genre();
        $genreAction->setNom('Action');
        $genreAction->setDescription('Jeux d\'action, de combat');
        $manager->persist($genreAction);
        // …
// Créer les éditeurs
        $editeurSony = new Editeur();
        $editeurSony->setNom('Sony Interactive Entertainment');
        $editeurSony->setPays('Japon');
        $editeurSony->setSiteWeb('https://www.sie.com');
        $manager->persist($editeurSony);
        // …
// Créer un jeu exemple
        $jeuVideo = new JeuVideo();
        $jeuVideo->setTitre('The Last of Us Part II');
        $jeuVideo->setEditeur($editeurSony);
        $jeuVideo->setGenre($genreAction);
        $jeuVideo->setDeveloppeur('Naughty Dog');
        $jeuVideo->setDateSortie(new \DateTime('2020-06-19'));
        $jeuVideo->setPrix(59.99);
        $manager->persist($jeuVideo);
        // ...

        $gAction = new Genre();
        $gAction->setNom('Action');
        $gAction->setDescription("Jeux d'action: jeux de plateforme, combat, tir (F¨S, TPS...)");
        $gAction->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($gAction);

        $gAventure = new Genre();
        $gAventure->setNom('Aventure');
        $gAventure->setDescription("Jeux d'aventure narrative, point and click...");
        $gAventure->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($gAventure);

        $gActionAventure = new Genre();
        $gActionAventure->setNom('Action Aventure');
        $gActionAventure->setDescription(": (Infiltration, survival, …)");
        $gActionAventure->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($gActionAventure);

        $gRpg = new Genre();
        $gRpg->setNom('RPG');
        $gRpg->setDescription(" Jeux de rôle, MMORPG, …");
        $gRpg->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($gRpg);

        $gStategie = new Genre();
        $gStategie->setNom('Strategie');
        $gStategie->setDescription(" : Jeux de stratégie (RTS, turn-based)");
        $gStategie->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($gStategie);

        $gSimulation = new Genre();
        $gSimulation->setNom('Simulation');
        $gSimulation->setDescription(" : Jeux de simulation, de gestion");
        $gSimulation->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($gSimulation);

        $gSport = new Genre();
        $gSport->setNom('Sport');
        $gSport->setDescription("Jeux de sport");
        $gSport->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($gSport);

        $gCourse = new Genre();
        $gCourse->setNom('Course');
        $gCourse->setDescription(": jeux de course par ex. automobil");
        $gCourse->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($gCourse);

        $gReflexion = new Genre();
        $gReflexion->setNom('Reflexion');
        $gReflexion->setDescription(" : Jeux de réflexion, puzzles, casse-tête");
        $gReflexion->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($gReflexion);



        $eNintendo = new Editeur();
        $eNintendo->setNom('Nintendo');
        $eNintendo->setPays('Japon');
        $eNintendo->setSiteWeb('https://www.nintendo.com');
        $eNintendo->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($eNintendo);




        $smash = new JeuVideo();
        $smash->setTitre('Super Smash Bros Ultimate');
        $smash->setEditeur($eNintendo);
        $smash->setGenre($gAction);
        $smash->setDeveloppeur('Nintendo EAD Tokyo');
        $smash->setDateSortie(new \DateTime('2018-12-07'));
        $smash->setPrix(59.99);
        $manager->persist($smash);


        $zelda = new JeuVideo();
        $zelda->setTitre('The Legend of Zelda: The Wind Waker');
        $zelda->setEditeur($eNintendo);
        $zelda->setGenre($gAventure);
        $zelda->setDeveloppeur('Nintendo EAD Tokyo');
        $zelda->setDateSortie(new \DateTime('2002-12-13'));
        $zelda->setPrix(39.99);
        $manager->persist($zelda);


        $xeno = new JeuVideo();
        $xeno->setTitre('Xenoblade Chronicles');
        $xeno->setEditeur($eNintendo);
        $xeno->setGenre($gRpg);
        $xeno->setDeveloppeur('Monolith Soft');
        $xeno->setDateSortie(new \DateTime('2010-06-10'));
        $xeno->setPrix(39.99);
        $manager->persist($xeno);


        $fireemblem = new JeuVideo();
        $fireemblem->setTitre('Fire Embleme: Three Houses');
        $fireemblem->setEditeur($eNintendo);
        $fireemblem->setGenre($gStategie);
        $fireemblem->setDeveloppeur('Intelligent Systems');
        $fireemblem->setDateSortie(new \DateTime('2019-07-26'));
        $fireemblem->setPrix(59.99);
        $manager->persist($fireemblem);


        $animal = new JeuVideo();
        $animal->setTitre('Animal Crossing: New Leaf');
        $animal->setEditeur($eNintendo);
        $animal->setGenre($gSimulation);
        $animal->setDeveloppeur('Nintendo EAD');
        $animal->setDateSortie(new \DateTime('2012-11-08'));
        $animal->setPrix(24.99);
        $manager->persist($animal);


        $wiifit = new JeuVideo();
        $wiifit->setTitre('Wii Fit');
        $wiifit->setEditeur($eNintendo);
        $wiifit->setGenre($gSport);
        $wiifit->setDeveloppeur('Nintendo EAD');
        $wiifit->setDateSortie(new \DateTime('2007-12-01'));
        $wiifit->setPrix(24.99);
        $manager->persist($wiifit);


        $mkworld = new JeuVideo();
        $mkworld->setTitre('Mario Kart World');
        $mkworld->setEditeur($eNintendo);
        $mkworld->setGenre($gCourse);
        $mkworld->setDeveloppeur('Nintendo EPD');
        $mkworld->setDateSortie(new \DateTime('2025-06-05'));
        $mkworld->setPrix(79.99);
        $manager->persist($mkworld);


        $toad = new JeuVideo();
        $toad->setTitre('Captain Toad Treasure Tracker');
        $toad->setEditeur($eNintendo);
        $toad->setGenre($gReflexion);
        $toad->setDeveloppeur('Nintendo EAD Tokyo');
        $toad->setDateSortie(new \DateTime('2014-11-13'));
        $toad->setPrix(39.99);
        $manager->persist($toad);

        $manager->flush();
        


// Création d'un utilisateur
        $user = new \App\Entity\Utilisateur();
        $user->setNom('Dupont');
        $user->setPerson('Jean');
        $user->setPseudo('jdupont');
        $user->setMail('jean@test.fr');
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdateAt(new \DateTimeImmutable());
        $manager->persist($user);
        $user1 = new \App\Entity\Utilisateur();
        $user1->setPerson('Laura');
        $user1->setNom('DARIEUD');
        $user1->setPseudo('Laura34');
        $user1->setMail('laura34@example.com');
        $user1->setCreatedAt(new \DateTimeImmutable());
        $user1->setUpdateAt(new \DateTimeImmutable());
        $manager->persist($user1);

// Ajout d'un jeu dans sa collection
        $collection = new Collect();
        $collection->setUtilisateur($user);
        $collection->setJeuVideo($jeuVideo);
        $collection->setStatut(\App\Enum\StatutJeuEnum::EN_COURS);
        $collection->setPrixAchat(45.00);
        $collection->setCreatedAt(new \DateTimeImmutable());
        $collection->setUpdateAt(new \DateTimeImmutable());
        $manager->persist($collection);

        $collection2 = new Collect();
        $collection2->setUtilisateur($user1);
        $collection2->setJeuVideo($jeuVideo);
        $collection2->setStatut(\App\Enum\StatutJeuEnum::POSSEDE);
        $collection2->setPrixAchat(50.00);
        $collection2->setCreatedAt(new \DateTimeImmutable());
        $collection2->setUpdateAt(new \DateTimeImmutable());
        $manager->persist($collection2);

        $manager->flush();
    }
}
