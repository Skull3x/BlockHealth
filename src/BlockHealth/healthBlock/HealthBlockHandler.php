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

use BlockHealth\BlockHealth;
use pocketmine\block\Block;
use pocketmine\level\Position;

class HealthBlockHandler {

    /** @var BlockHealth */
    private $plugin;

    /** @var HealthBlock[] */
    private $healthBlocks = [];

    /**
     * HealthBlockHandler constructor.
     *
     * @param BlockHealth $plugin
     */
    public function __construct(BlockHealth $plugin) {
        $this->plugin = $plugin;
        $this->start();
    }

    /**
     * Return BlockHealth instance
     *
     * @return BlockHealth
     */
    public function getPlugin() {
        return $this->plugin;
    }

    /**
     * Return HealthBlock array
     *
     * @return HealthBlock[]
     */
    public function getHealthBlocks() {
        return $this->healthBlocks;
    }

    /**
     * Return a HealthBlock by his position
     *
     * @param Position $position
     * @return HealthBlock|null
     */
    public function getHealthBlock(Position $position) {
        $block = null;
        foreach($this->healthBlocks as $healthBlock) {
            if($this->comparePositions($healthBlock->getBlock(), $position)) {
                $block = $healthBlock;
            }
        }
        return $block;
    }

    /**
     * Register a health block
     *
     * @param Block $block
     */
    public function registerHealthBlock(Block $block) {
        $this->healthBlocks[] = new HealthBlock($this, $block);
    }

    /**
     * Remove a health block
     *
     * @param HealthBlock $block
     */
    public function removeHealthBlock(HealthBlock $block) {
        unset($this->healthBlocks[array_search($block, $this->healthBlocks)]);
    }

    /**
     * Return true if both positions are equal, false if not
     *
     * @param Position $position
     * @param Position $position2
     * @return bool
     */
    private function comparePositions(Position $position, Position $position2) {
        if($position->level->getName() == $position2->getLevel()->getName() and round($position->x) == round($position2->x) and round($position->y) == round($position2->y) and round($position->z) == round($position2->z)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function tick() {
        foreach($this->healthBlocks as $block) {
            $block->tick();
        }
    }

    public function start() {
        if($this->plugin->getConfig()->get("regeneration")) {
            $this->plugin->getServer()->getScheduler()->scheduleRepeatingTask(new HealthBlockRegeneration($this), 20);
        }
    }

}