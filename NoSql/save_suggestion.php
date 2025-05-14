<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->esportify->suggestions;

$data = [
    'name' => $_POST['name'],
    'email' => $_POST['email'],
    'message' => $_POST['message'],
    'created_at' => new MongoDB\BSON\UTCDateTime()
];

$insertResult = $collection->insertOne($data);

if ($insertResult->getInsertedCount() === 1) {
    echo "Suggestion enregistrée avec succès !";
}