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

1. Créer un contrôleur MovieController et lui rajouter un constructeur contenant un MovieRepository en private
	
2. Rajouter une route /api/movie, préciser dans la route que sa méthode est GET
	
3. Faire un return d'un $this->json() avec le findAll du repository à l'intérieur
	
4. Tester cette méthode sur thunder client en faisant une requête GET vers localhost:8000/api/movie

