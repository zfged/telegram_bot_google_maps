<?php

use App\Conversations\mainConversation;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});
$botman->hears('/start', function ( $bot ) { $bot->startConversation ( new mainConversation ); } );

$botman->hears('/stop', function($bot) {
	$bot->reply('stopped');
})->stopsConversation();

$botman->hears('/pause', function($bot) {
	$bot->reply('stopped');
})->skipsConversation();