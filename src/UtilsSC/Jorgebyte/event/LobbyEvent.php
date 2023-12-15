<?php

namespace UtilsSC\Jorgebyte\event;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;
use pocketmine\utils\TextFormat as JB;
use UtilsSC\Jorgebyte\Main;
use UtilsSC\Jorgebyte\utils\Utils;

class LobbyEvent implements Listener
{

    public $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event): void
    {
        $name = $event->getPlayer()->getName();
        $player = $event->getPlayer();
        $event->setJoinMessage(JB::GRAY . "[" . JB::GREEN . "+" . JB::GRAY . "] " . JB::GRAY . $name);
        $lobbyW = $this->plugin->getConfig()->get("lobby");
        $lobbyW = Server::getInstance()->getWorldManager()->getWorldByName($lobbyW);
        $player->teleport($lobbyW->getSafeSpawn());
        Utils::spawnFirePlayer($player);
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        $name = $event->getPlayer()->getName();
        //$player = $event->getPlayer();
        $event->setQuitMessage(JB::GRAY . "[" . JB::RED . "-" . JB::GRAY . "] " . JB::GRAY . $name);
    }
}