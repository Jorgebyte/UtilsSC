<?php

namespace UtilsSC\Jorgebyte\commands;

use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\VanillaItems;
use pocketmine\Server;
use pocketmine\utils\TextFormat as JB;
use pocketmine\player\Player;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use UtilsSC\Jorgebyte\utils\Utils;
use UtilsSC\Jorgebyte\Main;

class WarpCommand extends Command
{

    public $plugin;

    public function __construct(Main $plugin)
    {
        parent::__construct("warp", "Navega por diversos mundos - StreesCraft", null, ["warps"]);
        $this->setPermission("utilssc.command");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $lobbyW = $this->plugin->getConfig()->get("lobby");
        $nexus = $this->plugin->getConfig()->get("nexus");
        $minapvp = $this->plugin->getConfig()->get("minapvp");

        if ($sender instanceof Player) {
            $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
            $menu->setName(JB::YELLOW . "Warps". JB::RED . " - " . JB::YELLOW . "Strees");
            $inv = $menu->getInventory();

            $inv->setItem(49, VanillaItems::SNOWBALL()
                ->setCustomName(JB::BOLD . JB::RED . "Regresar")
                ->setLore([JB::RESET . JB::GRAY . "Cerrar Menu"]));

            $inv->setItem(22, VanillaItems::BRICK()
                ->setCustomName(JB::BOLD . JB::RED . "Lobby")
                ->setLore([JB::RESET . JB::GRAY . "Users: " .Utils::getPlayerCountInWorld($lobbyW)]));

            $inv->setItem(23, VanillaItems::DIAMOND()
                ->setCustomName(JB::BOLD . JB::LIGHT_PURPLE . "Nexus")
                ->setLore([JB::RESET . JB::GRAY . "Users: " . Utils::getPlayerCountInWorld($nexus)]));

            $inv->setItem(21, VanillaItems::DIAMOND_SWORD()
                ->setCustomName(JB::BOLD . JB::GRAY . "Mina" . JB::RED . "PvP")
                ->setLore([JB::RESET . JB::GRAY . "Users: " . Utils::getPlayerCountInWorld($minapvp)]));

            for ($i = 0; $i <= 53; $i++) {
                $list = array_merge(range(10, 16), range(19, 25), range(28, 34), range(37, 43), [49]);
                if (in_array($i, $list))
                    continue;
                $inv->setItem($i, VanillaBlocks::STAINED_GLASS_PANE()->setColor(DyeColor::RED())->asItem()->setCustomName("§r§0"));
            }

            $menu->setListener(function(InvMenuTransaction $transaction) : InvMenuTransactionResult {

                $player = $transaction->getPlayer();
                $item = $transaction->getItemClicked();

                return $transaction->discard()->then(function(Player $player) use($item) {
                    $lobbyW = $this->plugin->getConfig()->get("lobby");
                    $lobbyW = Server::getInstance()->getWorldManager()->getWorldByName($lobbyW);
                    $nexus = $this->plugin->getConfig()->get("nexus");
                    $nexus = Server::getInstance()->getWorldManager()->getWorldByName($nexus);
                    $minapvp = $this->plugin->getConfig()->get("minapvp");
                    $minapvp = Server::getInstance()->getWorldManager()->getWorldByName($minapvp);

                    if ($item->getTypeId() === ItemTypeIds::SNOWBALL) {
                        $player->removeCurrentWindow();
                        $player->sendMessage(Utils::PREFIX . JB::GRAY . "Has visto el menu de Warps");
                    }
                    if ($item->getTypeId() === ItemTypeIds::BRICK) {
                        if ($lobbyW !== null) {
                            $player->teleport($lobbyW->getSafeSpawn());
                            $player->sendMessage(Utils::PREFIX . JB::GREEN . "Te has teletransportado al spawn.");
                            Utils::addSound($player, "random.orb");
                        } else{
                            $player->sendMessage(Utils::PREFIX . JB::RED . "El spawn del modo: " . JB::GRAY . "Lobby" . JB::RED . " aun no ha sido colocado");
                            $player->removeCurrentWindow();
                            Utils::addSound($player,"random.break");
                        }
                    }
                    if ($item->getTypeId() === ItemTypeIds::DIAMOND) {
                        if ($nexus !== null) {
                            $player->teleport($nexus->getSafeSpawn());
                            $player->sendMessage(Utils::PREFIX . JB::GREEN . "Te has teletransportado al Modo Nexus.");
                            Utils::addSound($player, "random.orb");
                        } else{
                            $player->sendMessage(Utils::PREFIX . JB::RED . "El spawn del modo: " . JB::LIGHT_PURPLE . "Nexus" . JB::RED . " aun no ha sido colocado");
                            $player->removeCurrentWindow();
                            Utils::addSound($player,"random.break");
                        }
                    }
                    if ($item->getTypeId() === ItemTypeIds::DIAMOND_SWORD) {
                        if ($minapvp !== null) {
                            $player->teleport($minapvp->getSafeSpawn());
                            $player->sendMessage(Utils::PREFIX . JB::GREEN . "Te has teletransportado al Modo MinaPvP.");
                            Utils::addSound($player, "random.orb");
                        } else{
                            $player->sendMessage(Utils::PREFIX . JB::RED . "El spawn del modo: " . JB::DARK_RED . "MinaPvP" . JB::RED . " aun no ha sido colocado");
                            $player->removeCurrentWindow();
                            Utils::addSound($player,"random.break");
                        }
                    }
                });
            });
            $menu->send($sender);
        }
    }
}