# Symfony Rest 
Première API REST en symfony

## How To Use
1. cloner le projet
2. `composer install`
3. Créer et importer la base de données
4. `symfony server:start`

_______________________
*  Créer un database.sql avec une entité et un repository pour : un Movie avec un title en varchar, un resume en text, un released en Date et un duration en int

_______________________
    ** Premier contrôleur Rest :
 ---- avant tt dans le terminal:
 php bin/consol ma:con Movie: pour cree control
 -----par default
1. Créer un contrôleur MovieController et lui rajouter un constructeur contenant un MovieRepository en private
	
2. Rajouter une route /api/movie, préciser dans la route que sa méthode est GET
	
3. Faire un return d'un $this->json() avec le findAll du repository à l'intérieur
	
4. Tester cette méthode sur thunder client en faisant une requête GET vers localhost:8000/api/movie
	
5. Rajouter une nouvelle route sur /api/movie/{id} en GET également, et dedans faire un findById, si le retour est null on fait un return d'un $this->json avec un message Resource Not Found et le status 404  , sinon on renvoie le movie
	
6. Rajouter une nouvelle route /api/movie/{id} mais cette fois ci en method DELETE et dedans on supprime le movie

______________________
 
    ** Faire le POST à la main
	
1. Créer une nouvelle route sur /api/movie en POST, dans les arguments de la route, ajouter le Request $request
	
2. Utiliser la méthode ->toArray() de la $request pour récupérer les données du body sous forme de tableau associatif (par exemple si on stock ça dans une variable data on pourra accéder à $data['title']
	
3. Faire une instance de movie et dans le constructeur mettre les données du toArray aux bons emplacement, sachant que pour le "released" il faudra faire un new DateTime
	
4. Donner cette instance au persist du repo et faire un return de json() avec l'instance en argument et le status 201 (created)


         *  à mettre dans le body de la request POST avec Thunder Client
            {
             "title":"mon film",
             "resume":"le résumé",
             "released":"2020-01-01",
             "duration":120
            } 
__________________________

* Faire le GenreController en utilisant le GenreRepository

     attention pour cela j'ai besoin de : ON DELETE CASCADE
     Foreign Key (id_movie) REFERENCES movie(id) ON DELETE CASCADE,
     Foreign Key (id_genre) REFERENCES genre(id) ON DELETE CASCADE
__________________________

    ** Récupérer les Genre avec les Movie

1. Modifier l'entité Movie pour rajouter dedans une propriété genres de type array (et même Genre[] si on veut faire le commentaire de typage) initialisée en tableau vide (je recommande de la mettre hors du constructeur)
	
2. Toujours dans l'entité, générer les getters/setters puis rajouter une méthode addGenre(Genre $genre) qui va push l'argument dans la propriété $this->genres
	
3. Dans le MovieRepository, modifier la requête du findAll pour y mettre la requête avec double jointure en mettant un alias movie_id pour movie.id et genre_id pour genre.id puis, l'algo a faire est la suivante :
	* Juste au dessus de la boucle, déclarer une variable currentMovie initialisée null
	* Dans la boucle, faire un if qui vérifie est-ce que currentMovie n'est pas null et est-ce que son id correspond au line[movie_id]
	* Si oui, alors on va faire un addGenre sur currentMovie dans lequel on va mettre une nouvelle instance de Genre (en mettant bien genre_id pour l'id)
	* Si non, alors on va faire une instance de Movie, l'assigner à currentMovie et aussi la push dans la list. On fait ensuite un autre if pour vérifier si line[genre_id] isset, et si oui, on créer une instance de Genre qu'on met dans le addGenre du currentMovie
	
4. Essayer de voir comment faire la même chose pour le findById

 _______________________
 * Méthode alternative pour avoir le même résultat mais juste un peu moins optimisée : Créer une méthode findByMovie(int $movieId) dans le GenreRepository, et dans le MovieRepository::findAll() faire en sorte de lancer le findByMovie dans le foreach pour chaque film

 ________________________
 * Dans le GenreController, rajouter une route sur localhost:8000/api/genre/movie/{id} qui utilisera le findByMovie du GenreRepository pour renvoyer les genres associés à un film donné

 ________________________
    **Des tests. En vous "inspirant" de la méthode testGetAll faire les tests suivants :

1. Vérifier que le /api/movie/id fonctionne et renvoie bien un truc qui ressemble à un film
	
2. Vérifier que le /api/movie/id renvoie bien un 404 quand on lui donne un id qui n'existe pas
	
3. Vérifier que le /api/movie en POST fonctionne quand on lui envoie un film en JSON (on pourra par exemple vérifier que la réponse contient bien un id)
	
4. Vérifier que le /api/movie/id en PATCH fonctionne et met bien à jour le champ qu'on lui dit de modifier
	
5. Vérifier qu'on a un 404 sur le PATCH aussi si on donne un truc qui existe pas

___________________________
    **En vous inspirant de la partie validation du post du MovieController, faire :

1. La validation du Patch de MovieController (juste avant le update)
	
2. La validation du POST de GenreController, on veut juste que le label ne soit pas vide
	
3. La validation du PATCH du GenreController