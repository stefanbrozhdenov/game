<?php

require 'vendor/autoload.php';

$game = new Services\Game;
$arguments = getopt("p:c:r:");
$game->setPlayers($arguments["p"]);
$game->setChoises($arguments["c"]);
$game->setRounds($arguments["r"]);

$game->play();
