<?php

namespace UtilsSC\Jorgebyte\utils;

use BlockHorizons\Fireworks\entity\FireworksRocket;
use BlockHorizons\Fireworks\item\ExtraVanillaItems;
use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\network\mcpe\NetworkBroadcastUtils;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as JB;
use pocketmine\world\particle\BlockBreakParticle;

class Utils
{

    public const PREFIX = JB::BOLD . JB::GRAY . "[" . JB::YELLOW . "StreesCraft" . JB::GRAY . "] " . JB::RESET;

    public const NOPERMS = JB::BOLD . JB::GRAY . "[" . JB::RED . "!" . JB::GRAY . "] " . JB::RESET . JB::RED . "No tienes suficientes permisos para realisar esto.";

    public static function addSound(Player $player, string $sound, $volume = 1, $pitch = 1): void
    {
        $packet = new PlaySoundPacket();
        $packet->x = $player->getPosition()->getX();
        $packet->y = $player->getPosition()->getY();
        $packet->z = $player->getPosition()->getZ();
        $packet->soundName = $sound;
        $packet->volume = 1;
        $packet->pitch = 1;
        $player->getNetworkSession()->sendDataPacket($packet);
    }

    public static function addLightning(Player $player): void
    {
        $pos = $player->getPosition();
        $light2 = new AddActorPacket();
        $light2->actorUniqueId = Entity::nextRuntimeId();
        $light2->actorRuntimeId = 1;
        $light2->position = $player->getPosition()->asVector3();
        $light2->type = "minecraft:lightning_bolt";
        $light2->yaw = $player->getLocation()->getYaw();
        $light2->syncedProperties = new PropertySyncData([], []);

        $block = $player->getWorld()->getBlock($player->getPosition()->floor()->down());
        $particle = new BlockBreakParticle($block);

        $player->getWorld()->addParticle($pos, $particle, $player->getWorld()->getPlayers());
        $sound2 = PlaySoundPacket::create("ambient.weather.thunder", $pos->getX(), $pos->getY(), $pos->getZ(), 1, 1);

        NetworkBroadcastUtils::broadcastPackets($player->getWorld()->getPlayers(), [$light2, $sound2]);
    }

    public static function spawnFirePlayer(Player $player): void
    {
        $fireworks = ExtraVanillaItems::FIREWORKS();
        $fw = clone $fireworks;
        $fw->addExplosion(Fireworks::TYPE_CREEPER_HEAD, Fireworks::COLOR_GREEN, "", false, false);
        $fw->addExplosion(Fireworks::TYPE_CREEPER_HEAD, Fireworks::COLOR_RED, "", false, false);
        $fw->addExplosion(Fireworks::TYPE_CREEPER_HEAD, Fireworks::COLOR_BLUE, "", false, false);
        $fw->addExplosion(Fireworks::TYPE_CREEPER_HEAD, Fireworks::COLOR_WHITE, "", false, false);
        $fw->setFlightDuration(2);

        $playerPosition = $player->getPosition();

        $level = $player->getWorld();

        $vector3 = $playerPosition->add(0.5, 1, 0.5);

        $firework = new FireworksRocket(new Location($vector3->x, $vector3->y, $vector3->z, $level, lcg_value() * 360, 90), $fw);
        $firework->spawnToAll();
    }

    public static function getDevice(Player $player): string
    {
        $extraData = $player->getPlayerInfo()->getExtraData();

        if ($extraData["DeviceOS"] === DeviceOS::ANDROID && $extraData["DeviceModel"] === "") {
            return "Linux";
        }

        return match ($extraData["DeviceOS"]) {
            DeviceOS::ANDROID => JB::GREEN . "Android",
            DeviceOS::IOS => JB::YELLOW . "iOS",
            DeviceOS::OSX => "macOS",
            DeviceOS::AMAZON => "Fire OS",
            DeviceOS::GEAR_VR => "Gear VR",
            DeviceOS::HOLOLENS => "Hololens",
            DeviceOS::WINDOWS_10 => JB::RED . "Windows",
            DeviceOS::WIN32 => "Windows 7",
            DeviceOS::DEDICATED => JB::BLUE . "Dedicated",
            DeviceOS::TVOS => "tvOS",
            DeviceOS::PLAYSTATION => JB::RED . "Play" . JB::YELLOW . "Sta" . JB::BLUE . "tion",
            DeviceOS::NINTENDO => Jb::BLUE . "Nint" . JB::DARK_PURPLE . "endo". JB::RED . " Switch",
            DeviceOS::XBOX => JB::GREEN . "Xbox",
            DeviceOS::WINDOWS_PHONE => "Windows Phone",
            default => "Unknown"
        };
    }

    public static function getPlayerPing(Player $player): int
    {
        return $player->getNetworkSession()->getPing() - 13;
    }

    public static function getPlayerCountInWorld(string $worldName): string {
        $world = Server::getInstance()->getWorldManager()->getWorldByName($worldName);
        if ($world !== null) {
            $playerCount = count($world->getPlayers());
            if ($playerCount >= 1) {
                return $playerCount . " (". JB::GREEN . "Popular" .JB::GRAY .")";
            } else {
                return (string)$playerCount;
            }
        }
        return "0";
    }
}
