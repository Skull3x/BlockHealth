<?php

/**
 * This is EntirelyQuartz property.
 *
 * Copyright (C) 2016 EntirelyQuartz
 *
 * This is free software, you can redistribute it but you cannot modify it any way
 * or use it with commercial purposes unless otherwise given permission to do so.
 * If you have not been given explicit permission to modify this software you
 * should take the appropriate actions to remove this software from your device immediately.
 *
 * @author EntirelyQuartz
 * @twitter EntirelyQuartz
 *
 */

namespace BlockHealth;

use BlockHealth\healthBlock\HealthBlock;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class EventListener implements Listener {

    /** @var BlockHealth */
    private $plugin;

    /**
     * EventListener constructor.
     *
     * @param BlockHealth $plugin
     */
    public function __construct(BlockHealth $plugin) {
        $this->plugin = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event) {
        $config = $this->plugin->getConfig();
        if(!$config->get("enableAllWorlds") and !in_array($event->getPlayer()->getLevel()->getName(), $config->get("worlds"))) {
            return;
        }
        $healthBlock = $this->plugin->getHealthBlockHandler()->getHealthBlock($event->getBlock());
        if($healthBlock instanceof HealthBlock) {
            $healthBlock->removeHealthByPlayer($event->getPlayer(), 1);
            $event->setCancelled();
        }
        else {
            if(isset($config->get("blocks")[$event->getBlock()->getId()])) {
                $healthBlockHandler = $this->plugin->getHealthBlockHandler();
                $healthBlockHandler->registerHealthBlock($event->getBlock());
                $healthBlockHandler->getHealthBlock($event->getBlock())->removeHealthByPlayer($event->getPlayer(), 1);
                $event->setCancelled();
            }
            elseif($config->get("inverseMode")) {
                $healthBlockHandler = $this->plugin->getHealthBlockHandler();
                $healthBlockHandler->registerHealthBlock($event->getBlock());
                $healthBlockHandler->getHealthBlock($event->getBlock())->removeHealthByPlayer($event->getPlayer(), 1);
                $event->setCancelled();
            }
        }
    }

}