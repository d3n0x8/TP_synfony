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
	$manager->flush();
        // ...


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
