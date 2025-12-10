<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Classe utilisée pour construire le menu principal et le fil d'Ariane (breadcrumb) de l'application.
 */
class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Construit le menu principal de navigation (affiché via {{ knp_menu_render('main') }}).
     *
     * @param array $options
     * @return ItemInterface
     */
    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'navbar-nav']); 

        $menu->addChild('Accueil', ['route' => 'homepage'])
            ->setExtra('icon', 'fas fa-home'); 

        $menu->addChild('Jeux Vidéo', ['route' => 'app_jeu_video_index'])
            ->setExtra('icon', 'fas fa-gamepad');

        $menu->addChild('Genre de jeux vidéo', ['route' => 'app_genre_index'])
            ->setExtra('icon', 'fas fa-tag');

        $menu->addChild('Éditeurs de jeux vidéo', ['route' => 'app_editeur_index'])
            ->setExtra('icon', 'fas fa-building');

        $menu->addChild('Collections de jeux vidéo', ['route' => 'app_collection_index'])
            ->setExtra('icon', 'fas fa-book');

        return $menu;
    }

    /**
     * Construit le fil d'Ariane (breadcrumb) (affiché via {{ knp_menu_render('breadcrumb') }}).
     *
     * IMPORTANT : Ce menu est généralement construit DANS chaque contrôleur, en fonction de la route actuelle.
     * Pour ce TP, cette méthode est juste un point de départ.
     *
     * @param array $options
     * @return ItemInterface
     */
    public function createBreadcrumbMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'breadcrumb']);

        $menu->addChild('Accueil', ['route' => 'homepage']);

        return $menu;
    }
}