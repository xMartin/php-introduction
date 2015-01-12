<?php

require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Response;

$config = Yaml::parse(file_get_contents('config.yml'));

define("TIMEZONE", $config['timezone']);
define("DATE_FORMAT", $config['date_format']);
define("TIME_FORMAT", $config['time_format']);

date_default_timezone_set(TIMEZONE);

function get_events() {
    $yaml_data = Yaml::parse(file_get_contents('events.yml'));

    $events = array_map(function($event) {
        $event['date'] = new DateTime($event['date']);

        return $event;
    }, $yaml_data);

    return $events;
}

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views'
]);

$app->get('/', function() use ($app) {
    $events = get_events();

    //arguments in reversed order, because we want reverse ordering
    usort($events, function($b, $a) {
        if ($a < $b) {
            return -1;
        } else if ($a > $b) {
            return 1;
        } else {
            return 0;
        }
    });

    //return "<pre>".var_export($events, true);

    return $app['twig']->render('event_list.twig', [
        'events' => $events,
        'date_format' => DATE_FORMAT,
        'time_format' => TIME_FORMAT
    ]);
});

$app->get('{id}', function($id) use ($app) {
    $events = get_events();

    foreach($events as $e) {
        if ($e['id'] === $id) {
            $event = $e;
            break;
        }
    }

    if (!$event) {
        $app->abort(404, "Event '$id' does not exist.");
    }

    return $app['twig']->render('event_details.twig', [
        'event' => $event,
        'date_format' => DATE_FORMAT,
        'time_format' => TIME_FORMAT
    ]);
});

$app->error(function (\Exception $e, $code) {
    return $e->getMessage();
});

$app->run();
