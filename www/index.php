<?php

require_once 'db.php';
require_once 'vendor/autoload.php';
require_once 'functions.php';

$db = new db();

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader('templates');
$twig = new Environment($loader);

Flight::set('twig', $twig);

Flight::route('/', function () {
    $db = new db();
    echo Flight::get('twig')->render('index.html',
        [
            'tiles' => $db->getTiles(),
            'images' => getImages()
        ]
    );
});

Flight::route('POST /addTile', function () {
    $db = new db();
    $tile_name = Flight::request()->data['tile_name'];
    $tile_url = Flight::request()->data['tile_url'];
    $tile_image = Flight::request()->data['tile_image'];
    $db->addNewTile($tile_name, $tile_url, $tile_image);
    Flight::redirect('/');
});

Flight::route('POST /updateTile', function () {
    $db = new db();
    $tile_id = Flight::request()->data['id'];
    $tile_order = Flight::request()->data['tile_order'];
    $tile_url = Flight::request()->data['tile_url'];
    $tile_name = Flight::request()->data['tile_name'];
    $tile_image = Flight::request()->data['tile_image'];
    $db->updateTile($tile_id, $tile_name, $tile_url, $tile_image, $tile_order);
    Flight::redirect('/');
});

Flight::route('/getTile/@id', function ($id) {
    $db = new db();
    echo json_encode($db->getTile($id));
});

Flight::route('/deleteTile/@id', function ($id) {
    $db = new db();
    $db->deleteTile($id);
    echo json_encode(array("success" => "true"));
});

Flight::route('POST /updateOrder', function () {
    $array = Flight::request()->data['data'];
    $db = new db();
    $db->updateOrder($array);
    echo json_encode(array("success" => "true"));
});

Flight::start();
