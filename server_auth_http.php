<?php
//get the user and password to validate the user is good
$user = array_key_exists( 'PHP_AUTH_USER', $_SERVER ) ? $_SERVER['PHP_AUTH_USER'] : '';
$pwd = array_key_exists( 'PHP_AUTH_PW', $_SERVER ) ? $_SERVER['PHP_AUTH_PW'] : '';

print_r($user,$pwd);
if($user != "richend" || $pwd != "1234"){
    die;
}



// we define all the available resources

$allowedResourceType = [
    'books',
    'authors',
    'genres',
];

// we validate that the resource is available

$resourceType = $_GET['resource_type'];
if ( !in_array($resourceType, $allowedResourceType)) {
    die;
}

// we define the resources

$books = [
    1 => [
        'titulo' => 'Lo que el viento se llevo',
        'id_autor' => 2,
        'id_genero' => 2,
    ],
    2 => [
        'titulo' => 'La Iliada',
        'id_autor' => 1,
        'id_genero' => 1,
    ],
    3 => [
        'titulo' => 'La Odisea',
        'id_autor' => 1,
        'id_genero' => 1,
    ],
];

//we indicate to the client that he will receive in a json
header('Content-Type: application/json');
//Detecting the id of the resource
$resourceId = array_key_exists('resource_id',$_GET) ? $_GET['resource_id'] : '';

//we generate the response assuming that the request is correct
switch( strtoupper($_SERVER['REQUEST_METHOD'])) {
    case 'GET':
        if ( empty($resourceId)){
            echo json_encode($books);
        }else{
            if( array_key_exists($resourceId, $books)){
                echo json_encode($books[$resourceId]);
            }
        }
        
        break;
    case 'POST':
        $json = file_get_contents('php://input');
        $books[] = json_decode($json, true);
        // echo array_keys( $books )[count($books) -1];
        echo json_encode($books);
        break;
    case 'PUT':
        print_r('entro');

        if (!empty($resourceId) && array_key_exists($resourceId, $books)){
            $json = file_get_contents('php://input');
            $books[$resourceId] = json_decode($json, true);
            echo json_encode($books);
        }
        break;
    case 'DELETE':
        if (!empty($resourceId) && array_key_exists($resourceId, $books)){

            unset( $books[ $resourceId]);    
            echo json_encode($books);      
        }
        break;
        break;
}

// Starting the terminal in server 1
// php -S localhost:8000 server.php

// Execute
// curl http://localhost -v
// curl http://localhost/\?resource_type\=books
// curl http://localhost/\?resource_type\=books | jq

//Exercise 2
//curl "http://localhost/rest_php/server.php?resource_type=books&resource_id=2"

//Exercise 3
//curl -X 'POST' http://localhost/rest_php/server.php?resource_type=books -d '{"titulo":"Nuevo Libro","id_autor":1,"id_genero":2}'

//Exercise 4
// curl -X 'PUT' "http://localhost/rest_php/server.php?resource_type=books&resource_id=2" -d '{"titulo":"Nuevo Libro","id_autor":1,"id_genero":2}'
//Exercise 5
// curl -X 'DELETE' "http://localhost/rest_php/server.php?resource_type=books&resource_id=2"

//Exercise 6 authentication
//curl "http://richend:1234@localhost/rest_php/server_auth_http.php?resource_type=books"

?>