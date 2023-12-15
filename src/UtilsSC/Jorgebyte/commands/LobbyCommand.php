<?php

namespace UtilsSC\Jorgebyte\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as JB;
use UtilsSC\Jorgebyte\Main;
use UtilsSC\Jorgebyte\utils\Utils;

class LobbyCommand extends Command
{

    public $plugin;

    public function __construct(Main $plugin)
    {
        parent::__construct("lobby", "Vuelve al mundo principal - StreesCraft", null, ["hub"]);
        $this->setPermission("utilssc.command");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if ($sender instanceof Player) {
            $lobbyWorld = $this->plugin->getConfig()->get("lobby");
            $lobbyWorld = Server::getInstance()->getWorldManager()->getWorldByName($lobbyWorld);

            if ($lobbyWorld !== null) {
                $sender->teleport($lobbyWorld->getSafeSpawn());
                $sender->sendMessage(Utils::PREFIX . JB::GREEN . "Has vuelto al mundo principal!");
                Utils::addSound($sender, "random.orb", 1, 1);
            } else {
                $sender->sendMessage(Utils::PREFIX . JB::RED . "El Lobby aun no ha sido configurado!!!");
                Utils::addSound($sender,"random.break");
            }
        } else {
            $sender->sendMessage(Utils::PREFIX . JB::RED . "This command can only be executed by players.");
        }
    }
}