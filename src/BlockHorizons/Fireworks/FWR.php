<?php

declare(strict_types = 1);

namespace BlockHorizons\Fireworks;

use BlockHorizons\Fireworks\entity\FireworksRocket;
use BlockHorizons\Fireworks\item\Fireworks;
use BlockHorizons\Fireworks\item\ExtraVanillaItems;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\StringToItemParser;
use pocketmine\data\bedrock\item\ItemTypeNames;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class FWR
{

    public function onLoad(): void
    {
//		ItemFactory::getInstance()->register(new Fireworks(new ItemIdentifier(ItemIds::FIREWORKS, 0), "Fireworks"), true);
        $itemDeserializer = GlobalItemDataHandlers::getDeserializer();
        $itemSerializer = GlobalItemDataHandlers::getSerializer();
        $stringToItemParser = StringToItemParser::getInstance();

        $fireworks = ExtraVanillaItems::FIREWORKS();
        $itemDeserializer->map(ItemTypeNames::FIREWORK_ROCKET, static fn() => clone $fireworks);
        $itemSerializer->map($fireworks, static fn() => new SavedItemData(ItemTypeNames::FIREWORK_ROCKET));
        $stringToItemParser->register("firework_rocket", static fn() => clone $fireworks);

        EntityFactory::getInstance()->register(FireworksRocket::class, static function (World $world, CompoundTag $nbt): FireworksRocket {
            return new FireworksRocket(EntityDataHelper::parseLocation($nbt, $world), Item::nbtDeserialize($nbt->getCompoundTag("Item")));
        }, ["FireworksRocket", EntityIds::FIREWORKS_ROCKET]);
    }
}