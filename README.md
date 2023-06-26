# Symfony Rest 
Première API REST en symfony

## How To Use
1. cloner le projet
2. `composer install`
3. Créer et importer la base de données
4. `symfony server:start`

_______________________
*  Créer un database.sql avec une entité et un repository pour : un Movie avec un title en varchar, un resume en text, un released en Date et un length en int

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
             "length":120
            } 
  __________________________

 * Faire le GenreController en utilisant le GenreRepository