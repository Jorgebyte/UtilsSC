<?php

namespace UtilsSC\Jorgebyte\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as JB;
use UtilsSC\Jorgebyte\utils\Utils;

class GetPingCommand extends Command
{

    public function __construct()
    {
        parent::__construct("getping", "Ver tu ping - StreesCraft", null, ["ping", "myping"]);
        $this->setPermission("utilssc.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if ($sender instanceof Player) {
                $sender->sendMessage(Utils::PREFIX . JB::GRAY . "Tu Ping es de: " . JB::GREEN . Utils::getPlayerPing($sender));
                Utils::addSound($sender, "random.pop");
        }
    }
}