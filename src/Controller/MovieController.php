<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
// validation; 
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Une API REST est une manière d'interagir avec les données d'un serveur en utilisant des requêtes HTTP
 * L'idée est pour le serveur d'exposer des routes qui permettront à des clients de manipuler les donnéees stockées pour des
 */
#[Route('/api/movie')]
class MovieController extends AbstractController
{

    public function __construct(private MovieRepository $repo)
    {
    }

    #[Route(methods: 'GET')]
    public function all(): JsonResponse
    {
        return $this->json($this->repo->findAll());
    }

    #[Route('/{id}', methods: 'GET')]
    public function one(int $id): JsonResponse
    {
        $movie = $this->repo->findById($id);
        if ($movie == null) {
            return $this->json('Resource Not found', 404);
        }

        return $this->json($movie);
    }

    #[Route('/{id}', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $movie = $this->repo->findById($id);
        if ($movie == null) {
            return $this->json('Resource Not found', 404);
        }
        $this->repo->delete($id);
        // @@ -52,17 +55,18 @@ public function delete(int $id): JsonResponse
        // return $this->json(null, 204);
    }

    //route sur /api/movie en POST (chon oun bala adres ra goftam inja faghat method ra elam mikonam) et  Utiliser la méthode ->toArray()
    #[Route(methods: 'POST')]
    // requet HTTP

    // corecte manuelle
    // public function add(Request $request)
    // {
    // $data = $request->toArray();
    // $movie = new Movie($data['title'], $data['resume'],new\DateTime ($data['released']), $data['duration']);
    //    fin de corect

    // version Symfony
// pour validator on dois appeler dans chaque fonction ici  ValidatorInterface $validator
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $movie = $serializer->deserialize($request->getContent(), Movie::class, 'json');
        //  Validation
        $errors = $validator->validate($movie);
        if ($errors->count() > 0) {
            return $this->json(['errors' => $errors], 400);
        }
        // fin validation

        // fin version symfony remplaser version manuell
        $this->repo->persist($movie);
        return $this->json($movie, 201);
    }
    // method mis a jour un donner avec patch:mis ajour par ligne on a besoin son ID PATCH= melange findbyid et post
    #[Route('/{id}', methods: 'PATCH')]
    public function update(int $id, Request $request, SerializerInterface $serializer,  ValidatorInterface $validator)
    {

        $movie = $this->repo->findById($id);
        if ($movie == null) {
            return $this->json('Resource Not found', 404);
        }
// bedoune validation in ra bayad benvisam:
        // $serializer->deserialize($request->getContent(), Movie::class, 'json', [
        //     'object_to_populate' => $movie
        // ]);
// validator ajouter
try {
    $serializer->deserialize($request->getContent(), Movie::class, 'json', [
        'object_to_populate' => $movie
    ]);
} catch (\Exception $error) {
    return $this->json('Invalid body', 400);
}
$errors = $validator->validate($movie);
if ($errors->count() > 0) {
    return $this->json(['errors' => $errors], 400);
}
// fin validator
        $this->repo->update($movie);

        return $this->json($movie);
    }
// pour serch
    #[Route('/search/{term}', methods: 'GET')]
    public function search(string $term): JsonResponse
    {
        return $this->json($this->repo->search($term));
    }



}




// class MovieController extends AbstractController
// {
//     public function __construct(private MovieRepository $repo){}
//     #[Route('/api/movie', methods: 'GET')]

//     public function all()
//     {
//         return $this->json($this->repo->findAll());

//     }

//     #[Route('/{id}', methods: 'GET')]

//     public function one(int $id):JsonResponse
//     {
//         $movie=$this->repo->findById($id);
//         if($movie==null){
//         return $this->json('resource not found',404);
//     }
//     return $this->json($movie);



//     }
// }
