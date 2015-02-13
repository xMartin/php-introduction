<?php

require "vendor/autoload.php";

use ImageDemo\ImageService;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;

date_default_timezone_set("Europe/Berlin");

$app = new Application();

$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views'
]);

$app->get('/static/{path}', function ($path) use ($app) {
    $path = 'data/static/' . $path;

    if (!file_exists($path)) {
        $app->abort(404);
    }

    return $app->sendFile($path);
});

$app->get("/", function() use ($app) {
    $image_service = new ImageService();
    $all_images = $image_service->getAll();

    return $app['twig']->render(
        'image_list.twig',
        [
            'images' => array_map(function($image) {
                return $image->toArray();
            }, $all_images)
        ]
    );
});

$app->get('/{id}', function($id) use ($app) {
    $image_service = new ImageService();

    try {
        $image = $image_service->getById($id);
    } catch (ImageDemo\NotFoundException $e) {
        $app->abort(404, "Image '$id' does not exist.");
    }

    return $app['twig']->render(
        'image.twig',
        [
            "image" => $image->toArray()
        ]
    );
});

$app->run();
