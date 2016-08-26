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

use BlockHealth\healthBlock\HealthBlockHandler;
use pocketmine\plugin\PluginBase;

class BlockHealth extends PluginBase {

    /** @var BlockHealth */
    private static $object = null;

    /** @var EventListener */
    private $eventListener;

    /** @var HealthBlockHandler */
    private $healthBlockHandler;

    public function onLoad() {
        if(!self::$object instanceof BlockHealth) {
            self::$object = $this;
        }
    }

    public function onEnable() {
        $this->checkFolders();
        $this->setHealthBlockHandler();
        $this->setEventListener();
        $this->getLogger()->info("BlockHealth by @EntirelyQuartz was enabled");
    }

    public function onDisable() {
        $this->getLogger()->info("BlockHealth by @EntirelyQuartz was disabled");
    }

    /**
     * Return BlockHealth instance
     *
     * @return BlockHealth
     */
    public static function getInstance() {
        return self::$object;
    }

    /**
     * Return EventListener instance
     *
     * @return EventListener
     */
    public function getEventListener() {
        return $this->eventListener;
    }

    /**
     * Return HealthBlockHandler instance
     *
     * @return HealthBlockHandler
     */
    public function getHealthBlockHandler() {
        return $this->healthBlockHandler;
    }

    /**
     * Register EventListener instance
     */
    public function setEventListener() {
        $this->eventListener = new EventListener($this);
    }

    /**
     * Register HealthBlockHandler instance
     */
    public function setHealthBlockHandler() {
        $this->healthBlockHandler = new HealthBlockHandler($this);
    }

    /**
     * Check all folders
     */
    public function checkFolders() {
        if(!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
        $this->saveDefaultConfig();
    }

}