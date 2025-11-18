<?php
namespace App\DataFixtures;
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
    }
}
