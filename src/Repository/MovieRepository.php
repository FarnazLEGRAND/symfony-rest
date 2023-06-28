<?php

namespace App\Repository;

use App\Entity\Genre;
use App\Entity\Movie;
use DateTime;

class MovieRepository
{

    /**
     * Méthode qui va faire une requête pour récupérer tous les produits de la base de données puis qui va boucler
     * sur les résultat de la requête pour transformer chaque ligne de résultat en instance de la classe Movie
     * @return Movie[] La liste des produits contenus dans la base de données;
     */
    // public function findAll(): array
    // {
    //     $list = [];
    //     $connection = Database::getConnection();

    //     $query = $connection->prepare("SELECT * FROM movie");

    //     $query->execute();

    //     foreach ($query->fetchAll() as $line) {
    //         $list[] = new Movie($line["title"], $line["resume"], new DateTime($line["released"]), $line['duration'], $line["id"]);
    //     }

    //     return $list;
    // }

    // modifier la requête du findAll PRESEDANT ET JE METTRE EN POSE - pour y mettre la requête avec double jointure en mettant un alias movie_id pour movie.id et genre_id pour genre.id puis, l'algo a faire est la suivante :

        public function findAll(): array
        {
            $list = [];
            $connection = Database::getConnection();
    
            $query = $connection->prepare("SELECT *, movie.id movie_id, genre.id genre_id FROM movie 
            LEFT JOIN genre_movie ON movie.id=genre_movie.id_movie
            LEFT JOIN genre ON genre.id=genre_movie.id_genre");
    
            $query->execute();
    
            /**
             * @var ?Movie
             */
            $previousMovie = null;
            foreach ($query->fetchAll() as $line) {
                if (empty($previousMovie) || $previousMovie->getId() != $line['movie_id']) {
                    $previousMovie = new Movie($line["title"], $line["resume"], new DateTime($line["released"]), $line['duration'], $line["movie_id"]);
                    $list[] = $previousMovie;
                }
                if (isset($line['genre_id'])) {
                    $previousMovie->addGenre(new Genre($line['label'], $line['genre_id']));
                }
            }
    
            return $list;
        }

        /* //En inversant la condition, un peu moins lisible selon moi comme recette demander
 $previousMovie = null;
        foreach ($query->fetchAll() as $line) {
            if (!empty($previousMovie) && $previousMovie->getId() == $line['movie_id']) {
                $previousMovie->addGenre(new Genre($line['label'], $line['genre_id']));
            } else {
                $previousMovie = new Movie($line["title"], $line["resume"], new DateTime($line["released"]), $line['duration'], $line["movie_id"]);
                $list[] = $previousMovie;

                if (isset($line['genre_id'])) {
                    $previousMovie->addGenre(new Genre($line['label'], $line['genre_id']));

                }
            }
        }
        */


    /**
     * @return Movie[] La liste des movies contenus dans la base de données;
     */
    public function findAllWithoutJoin(): array
    {
        $genreRepo = new GenreRepository();
        $list = [];
        $connection = Database::getConnection();

        $query = $connection->prepare("SELECT * FROM movie");

        $query->execute();

        
        foreach ($query->fetchAll() as $line) {
            $genres = $genreRepo->findByMovie($line['id']);
            $movie = new Movie($line["title"], $line["resume"], new DateTime($line["released"]), $line['duration'], $line["id"]);
            $movie->setGenres($genres);
            
            $list[] = $movie;
        }

        return $list;
    }




    /**
     * Méthode permettant de récupérer un produit spécifique en se basant sur son id
     * Si aucun produit n'existe pour cet id dans la base de données, on renvoie null
     * 
     * @param $id l'id du produit que l'on souhaite récupérer
     */
    public function findById(int $id):?Movie {

        $connection = Database::getConnection();

        $query = $connection->prepare("SELECT * FROM movie WHERE id=:id ");
        $query->bindValue(":id", $id);
        $query->execute();

        foreach ($query->fetchAll() as $line) {
            return new Movie($line["title"], $line["resume"], new DateTime($line["released"]), $line['duration'], $line["id"]);
        }
        return null;

    }

    /**
     * Méthode qui va prendre une instance de Movie en argument et va la transformer en requête INSERT INTO pour 
     * la faire persister en base de données
     * @param $movie Le produit que l'on souhaite faire persister (qui n'aura donc pas d'id au début de la méthode, car pas encore dans la bdd)
     */
    public function persist(Movie $movie) {
        $connection = Database::getConnection();

        $query = $connection->prepare("INSERT INTO movie (title,resume,released,duration) VALUES (:title,:resume,:released,:duration)");
        $query->bindValue(':title', $movie->getTitle());
        $query->bindValue(':resume', $movie->getResume());
        // ->format('Y-m-d') ordre de type ra migim
        $query->bindValue(':released', $movie->getReleased()->format('Y-m-d'));
        $query->bindValue(':duration', $movie->getDuration());
        

        $query->execute();

        //On assigne l'id auto incrémenté à l'instance de produit afin que l'objet soit complet après le persist
        $movie->setId($connection->lastInsertId());

        // On peux aussi domander en persiste que il prendre function associateGenre
        // foreach($movie->getGenres() as $genre) {
        //     $this->associateGenre($movie->getId(), $genre->getId());
        // }
        // fin appele associateGenre en persiste in yek option bishtare
    }

    /**
     * Méthode qui permet de supprimer un produit de la base de données en se basant sur son id
     * 
     * @param $id l'id du produit à supprimer
     */
    public function delete(int $id) {

        $connection = Database::getConnection();

        $query = $connection->prepare("DELETE FROM movie WHERE id=:id");
        $query->bindValue(":id", $id);
        $query->execute();
    }

    /**
     * Méthode pour mettre un jour un produit existant en base de données
     * 
     * @param Movie $movie Le produit à mettre à jour. Il doit avoir un id correspondant à une ligne de la bdd
     */
// method batraye yek film khas
     public function update(Movie $movie) {
        
        $connection = Database::getConnection();

        $query = $connection->prepare("UPDATE movie SET title=:title, resume=:resume, released=:released, duration=:duration WHERE id=:id");
        $query->bindValue(':title', $movie->getTitle());
        $query->bindValue(':resume', $movie->getResume());
        $query->bindValue(':released', $movie->getReleased()->format('Y-m-d'));
        $query->bindValue(':duration', $movie->getDuration());
        $query->bindValue(":id", $movie->getId());

        $query->execute();
    }
  
//  assosier un movie et un genres
public function associateGenre (int $idMovie, int $idGenre) {
    $connection = Database:: getConnection();
    $query = $connection->prepare ("INSERT INTO genre_movie (id_movie,id_genre) VALUES (:idMovie,:idGenre)");
    $query->bindValue(':idMovie', $idMovie);
    $query->bindValue(':idGenre', $idGenre);
    $query->execute ();

}

}
