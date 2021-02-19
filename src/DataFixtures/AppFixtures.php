<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        # Création des catégories
        $politique = new Category();
        $politique->setName('Politique')->setAlias('politique');

        $economie = new Category();
        $economie->setName('Economie')->setAlias('economie');

        $sante = new Category();
        $sante->setName('Santé')->setAlias('sante');

        $culture = new Category();
        $culture->setName('Culture')->setAlias('culture');

        # Je souhaite sauvegarder dans ma BDD les catégories
        $manager->persist($politique);
        $manager->persist($economie);
        $manager->persist($sante);
        $manager->persist($culture);

        # J'execute ma requete d'enregistrement
        $manager->flush();

        # Création d'un User
        $user = new User();
        $user->setFirstname('Hugo')
            ->setLastname('LIEGEARD')
            ->setEmail('hugo@actu.news')
            ->setPassword('demo')
            ->setRoles(['ROLE_USER']);

        # Sauvegarde dans la BDD
        $manager->persist($user);
        $manager->flush();

        # Création des Articles | Politique
        for ($i = 0; $i < 3; $i++) {

            $post = new Post();
            $post->setName('Lorem ipsum dolor ' . $i)
                ->setAlias('lorem-ipsum-dolor-' . $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse exercitationem facere possimus quis repellat repellendus reprehenderit, tempora ut. Distinctio eum expedita fuga, libero odit rem repellat repellendus reprehenderit unde voluptatibus?</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setUser($user)
                ->setCategory($politique);

            # On demande l'enregistrement de l'article
            $manager->persist($post);

        }

        # Création des Articles | Economie
        for ($i = 3; $i < 7; $i++) {

            $post = new Post();
            $post->setName('Lorem ipsum dolor ' . $i)
                ->setAlias('lorem-ipsum-dolor-' . $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse exercitationem facere possimus quis repellat repellendus reprehenderit, tempora ut. Distinctio eum expedita fuga, libero odit rem repellat repellendus reprehenderit unde voluptatibus?</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setUser($user)
                ->setCategory($economie);

            # On demande l'enregistrement de l'article
            $manager->persist($post);

        }

        # Création des Articles | Santé
        for ($i = 7; $i < 11; $i++) {

            $post = new Post();
            $post->setName('Lorem ipsum dolor ' . $i)
                ->setAlias('lorem-ipsum-dolor-' . $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse exercitationem facere possimus quis repellat repellendus reprehenderit, tempora ut. Distinctio eum expedita fuga, libero odit rem repellat repellendus reprehenderit unde voluptatibus?</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setUser($user)
                ->setCategory($sante);

            # On demande l'enregistrement de l'article
            $manager->persist($post);

        }

        # Création des Articles | Culture
        for ($i = 11; $i < 14; $i++) {

            $post = new Post();
            $post->setName('Lorem ipsum dolor ' . $i)
                ->setAlias('lorem-ipsum-dolor-' . $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse exercitationem facere possimus quis repellat repellendus reprehenderit, tempora ut. Distinctio eum expedita fuga, libero odit rem repellat repellendus reprehenderit unde voluptatibus?</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setUser($user)
                ->setCategory($culture);

            # On demande l'enregistrement de l'article
            $manager->persist($post);

        }

        # On execute la demande d'enregistrement dans la BDD
        $manager->flush();

    }
}
