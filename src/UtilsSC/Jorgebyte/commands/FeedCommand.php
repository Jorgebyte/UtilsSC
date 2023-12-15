<?php

namespace UtilsSC\Jorgebyte\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as JB;
use UtilsSC\Jorgebyte\Main;
use UtilsSC\Jorgebyte\utils\Utils;

class FeedCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        parent::__construct("feed", "Recupera el alimento - StreesCraft", null, ["maxfeed"]);
        $this->setPermission("feed.command");
        $this->setPermissionMessage(Utils::NOPERMS);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(JB::RED . "This command can only be executed in-game.");
            return true;
        }

        if (!$sender->hasPermission("feed.command")) {
            $sender->sendMessage(Utils::NOPERMS);
            return true;
        }

        if (isset($args[0]) && $sender->hasPermission("feed.player.command")) {
            $target = $this->plugin->getServer()->getPlayerExact($args[0]);
            if ($target instanceof Player) {
                $target->getHungerManager()->setFood($target->getHungerManager()->getMaxFood());
                $target->sendMessage(Utils::PREFIX . JB::GREEN . "Tu alimento ha sido restaurado!");
                Utils::addSound($target, "random.levelup");
                $sender->sendMessage(Utils::PREFIX . JB::GREEN . "Has alimentado al jugador: " . JB::GRAY . $target->getName());
                Utils::addSound($sender, "random.fizz");
            } else {
                $sender->sendMessage(Utils::PREFIX . JB::RED . "Este jugador no existe!");
            }
        } else {
            $sender->getHungerManager()->setFood($sender->getHungerManager()->getMaxFood());
            $sender->sendMessage(Utils::PREFIX . JB::GREEN . "Tu alimento ha sido restaurado!");
            Utils::addSound($sender, "random.levelup");
        }

        return true;
    }
}
