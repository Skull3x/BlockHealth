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

namespace BlockHealth\healthBlock;

use pocketmine\scheduler\PluginTask;

class HealthBlockRegeneration extends PluginTask {

    /** @var HealthBlockRegeneration */
    private $handler;

    /**
     * HealthBlockRegeneration constructor.
     *
     * @param HealthBlockHandler $handler
     */
    public function __construct(HealthBlockHandler $handler) {
        parent::__construct($handler->getPlugin());
        $this->handler = $handler;
    }

    public function onRun($currentTick) {
        $this->handler->tick();
    }

}