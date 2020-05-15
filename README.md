
                            /////////////////////////////////////////////////////////////
                            ////////////////////////VOYAGE VOYAGE///////////////////////
                            ///////////////////////////////////////////////////////////

Blog de voyage avec accés à des articles, création d'articles, modifications d'articles et 
possibilité de poster des commentaires.

Réalisé dans le cadre de la formation HumanBooster @ Bel Air Camp (2018/2019).

ROLES :

    Issa : créateur du blog

coding rules

    Nom des variables est en anglais
    Utilisation de symfony
    Pensez à lire la documentation de symfony

Requirements

    Php 7.2
    Symfony 4.2.2 (composer create-project symfony/skeleton storm)
    MySQl

Installation Step By Step

1./ Cloner le projet depuis github

 git clone git@github.com:IssaDia/blogVoyageSymfony.git

2./ Allez dans le répertoire dans lequel vous avez téléchargé le projet


3./ Récupérer via composer les dépendances du projet

composer install

4./ Configurer la base de données 

éditer le fichier .env pour ajouter votre nom de base de données, login et mots de passe mysql. 
(Exemple: DATABASE_URL=mysql://student:'mot de passe'@127.0.0.1:3306/kikipaye)

5./ Créer la base de données

 php bin/console doctrine:database:create

6./ Créer les tables etc...

 php bin/console doctrine:migrations:migrate

7./ Lancer le serveur php web-server

php bin/console server:run

8./ Accéder au site web via un navigateur http://127.0.0.1:8000


Fonctionnalités

    afficher une liste d'articles
    faire une recherche d'article
    sign up puis login permettant ajouter /supprimer /modifier/commenter des articles
    
    

    


