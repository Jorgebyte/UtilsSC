<?php

namespace UtilsSC\Jorgebyte\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as JB;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
class EnderCommand extends Command
{

    public function __construct()
    {
        parent::__construct("ec", "Ver Tu EnderChest - StreesCraft", null, ["enderchest"]);
        $this->setPermission("utilssc.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player)
            return;

        $menu = InvMenu::create(InvMenuTypeIds::TYPE_HOPPER);
        if ($sender->hasPermission("utilssc.command")) {
            $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);

        }
        $menu->getInventory()->setContents($sender->getEnderInventory()->getContents());
        $menu->setInventoryCloseListener(function (Player $player, Inventory $inventory): void {
            $player->getEnderInventory()->setContents($inventory->getContents());
        });

        $menu->send($sender, JB::GREEN . ("EnderChest"));
    }
}