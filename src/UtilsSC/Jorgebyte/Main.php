<?php

namespace UtilsSC\Jorgebyte;

use pocketmine\plugin\PluginBase;
use UtilsSC\Jorgebyte\commands\EnderCommand;
use UtilsSC\Jorgebyte\commands\FeedCommand;
use UtilsSC\Jorgebyte\commands\GetPingCommand;
use UtilsSC\Jorgebyte\commands\LobbyCommand;
use UtilsSC\Jorgebyte\commands\WarpCommand;
use UtilsSC\Jorgebyte\event\LobbyEvent;
use UtilsSC\Jorgebyte\task\TagTask;

use muqsit\invmenu\InvMenuHandler;

class Main extends PluginBase
{

    public function onEnable(): void
    {
        if (!InvMenuHandler::isRegistered())
            InvMenuHandler::register($this);

        $this->saveDefaultConfig();
        $config = $this->getConfig();

        $this->loadCommand();
        $this->loadTask();
        $this->loadEvents();
    }

    public function loadTask(): void
    {
        $map = $this->getScheduler();
        $map->scheduleRepeatingTask(new TagTask(), 20);
    }

    public function loadCommand(): void
    {
        $map = $this->getServer()->getCommandMap();

        $map->register("LobbyCommand", new LobbyCommand($this));
        $map->register("GetPingCommand", new GetPingCommand());
        $map->register("FeedCommand", new FeedCommand($this));
        $map->register("EnderCommand", new EnderCommand());
        $map->register("WarpCommand", new WarpCommand($this));

    }

    public function loadEvents(): void
    {
        $events = [
            LobbyEvent::class
        ];
        $success = true;
        foreach ($events as $eventClass) {
            try {
                $event = new $eventClass($this);
                $this->getServer()->getPluginManager()->registerEvents($event, $this);
            } catch (\Exception $e) {
                $this->getLogger()->error("Error registering event: " . $e->getMessage());
                $success = false;
            }
        }
        if ($success) {
            $this->getLogger()->info("All events were successfully loaded.");
        } else {
            $this->getLogger()->error("StreesCraft: ERROR EVENTS");
        }
    }
}