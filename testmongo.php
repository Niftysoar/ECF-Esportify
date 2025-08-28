<?php
require 'vendor/autoload.php';

$c = new MongoDB\Client(getenv('MONGODB_URI'));
$db = $c->selectDatabase(getenv('MONGODB_DB'));

$r = $db->contact_messages->insertOne([
    'name'      => 'Test',
    'email'     => 't@e.com',
    'message'   => 'Hello',
    'createdAt' => new MongoDB\BSON\UTCDateTime()
]);

echo "ID: " . $r->getInsertedId() . PHP_EOL;