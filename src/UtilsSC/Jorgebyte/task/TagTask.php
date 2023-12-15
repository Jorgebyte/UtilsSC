<?php

namespace UtilsSC\Jorgebyte\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat as JB;
use UtilsSC\Jorgebyte\utils\Utils;

class TagTask extends Task
{

    public function onRun(): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            $health = $player->getHealth();
            $ping = Utils::getPlayerPing($player);
            $device = Utils::getDevice($player);

            $player->setScoreTag("\n" . $device . "| Ping: " . JB::GREEN . $ping . "\n" . JB::RED . "20 | " .  JB::GRAY . $health);
        }
    }
}