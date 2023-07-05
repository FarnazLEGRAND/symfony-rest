<?php

 namespace App\Tests;

 use App\Repository\Database;
 use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

 class GenreApiTest extends WebTestCase
 {
     /**
      * La méthode setUp sera déclenchée avant l'exécution de chacun des tests de la classe actuelle.
      * Ici on lui dit de remettre à zéro la bdd en se basant sur le contenu du database.sql
      */
     public function setUp():void {
         Database::getConnection()->query(file_get_contents(__DIR__.'/../database.sql'));
     }

     public function testGetAllSuccess(): void
     {
         $client = static::createClient();
         $client->request('GET', '/api/genre');
         $json = json_decode($client->getResponse()->getContent(), true);

         $this->assertResponseIsSuccessful();

         $this->assertNotEmpty($json);
         $this->assertIsString($json[0]['label']);
         $this->assertIsInt($json[0]['id']);

     }


     public function testGetOneSuccess(): void
     {
         $client = static::createClient();
         $client->request('GET', '/api/genre/1');
         $json = json_decode($client->getResponse()->getContent(), true);

         $this->assertResponseIsSuccessful();

         $this->assertIsString($json['label']);
         $this->assertIsInt($json['id']);

     }
     /**
      * En général c'est bien de faire différents tests dédiés aux différents scénarios prévu. Ici on vérifie
      * qu'on a bien l'erreur attendue quand on requête une ressource inexistante
      */
     public function testGetOneNotFound(): void
     {
         $client = static::createClient();
         $client->request('GET', '/api/genre/100');

         $this->assertResponseStatusCodeSame(404);

     }

     public function testPostSuccess(): void
     {
         $client = static::createClient();
         $client->request('POST', '/api/genre', content: json_encode([
             'label' => 'From Test'
         ]));
         $json = json_decode($client->getResponse()->getContent(), true);

         $this->assertResponseIsSuccessful();

         $this->assertIsInt($json['id']);
     }
     public function testPostValidationFailed(): void
     {
         $client = static::createClient();
         $client->request('POST', '/api/genre', content: json_encode([
             'label' => ''
         ]));
         $json = json_decode($client->getResponse()->getContent(), true);

         $this->assertResponseStatusCodeSame(400);

         $this->assertStringContainsString('label', $json['errors']['detail']);


     }


     public function testPatchSuccess(): void
     {
         $client = static::createClient();
         $client->request('PATCH', '/api/genre/2', content: json_encode([
             'label' => 'From Test'
         ]));
         $json = json_decode($client->getResponse()->getContent(), true);

         $this->assertResponseIsSuccessful();


         $this->assertEquals($json['label'], 'From Test');
     }

     public function testPatchNotFound(): void
     {
         $client = static::createClient();
         $client->request('PATCH', '/api/genre/100');

         $this->assertResponseStatusCodeSame(404);

     }

     public function testDeleteSuccess(): void
     {
         $client = static::createClient();
         $client->request('DELETE', '/api/genre/1');

         $this->assertResponseIsSuccessful();

     }
 }
   4 changes: 2 additions & 2 deletions 4  
thunder-tests/thunderActivity.json
 
Expand Up	@@ -64,12 +64,12 @@
         "method": "POST",
         "sortNum": 0,
         "created": "2023-06-28T09:49:41.450Z",
         "modified": "2023-06-28T09:51:16.699Z",
         "modified": "2023-06-28T10:04:26.016Z",
         "headers": [],
         "params": [],
         "body": {
             "type": "json",
             "raw": "{\n  \"released\":\"2020-01-01\"\n\n}",
             "raw": "{\n  \"title\":\"\",\n  \"duration\": -100,\n  \"resume\":\"bloup\",\n  \"released\":\"2020-01-01\"\n\n}",
             "form": []
         },
         "tests": []
Expand Down	