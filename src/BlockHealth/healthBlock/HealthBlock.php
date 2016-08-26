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

use pocketmine\block\Block;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class HealthBlock {

    /** @var HealthBlockHandler */
    private $handler;

    /** @var Block */
    private $block;

    /** @var int */
    private $maxHealth;

    /** @var int */
    private $health;

    /** @var int */
    private $regenerateHealth;

    /** @var int */
    private $toRegenerateMax;

    /** @var int */
    private $toRegenerate;

    /**
     * HealthBlock constructor.
     *
     * @param HealthBlockHandler $handler
     * @param Block $block
     */
    public function __construct(HealthBlockHandler $handler, Block $block) {
        $this->handler = $handler;
        $this->block = $block;
        $config = $this->handler->getPlugin()->getConfig();
        if(isset($config->get("blocks")[$block->getId()])) {
            $this->maxHealth = (int) $config->get("blocks")[$block->getId()];
            $this->health = (int) $config->get("blocks")[$block->getId()];
        }
        else {
            $this->maxHealth = $config->get("inverseDefaultHealth");
            $this->health = $config->get("inverseDefaultHealth");
        }
        if($config->get("regeneration")) {
            $this->regenerateHealth = (int) $config->get("regenerationAmount");
            $this->toRegenerateMax = (int) $config->get("regenerationSeconds");
            $this->toRegenerate = (int) $config->get("regenerationSeconds");
        }
    }

    /**
     * Return Block instance
     *
     * @return Block
     */
    public function getBlock() {
        return $this->block;
    }

    public function addHealth($amount) {
        $this->health += $amount;
        if($this->health > $this->maxHealth) {
            $this->health = $this->maxHealth;
        }
    }

    public function removeHealth($amount) {
        $this->health -= $amount;
        if($this->health <= 0) {
            $this->block->getLevel()->setBlock($this->block, Block::get(0));
            $this->handler->removeHealthBlock($this);
        }
    }

    public function removeHealthByPlayer(Player $player, $amount) {
        $this->removeHealth($amount);
        $config = $this->handler->getPlugin()->getConfig();
        $player->sendMessage($this->translateColours(str_replace("{rn}", $this->health, str_replace("{max}", $this->maxHealth, $config->get("removeMessage")))));
        $player->sendPopup($this->translateColours(str_replace("{rn}", $this->health, str_replace("{max}", $this->maxHealth, $config->get("removePopup")))));
    }

    public function tick() {
        $this->toRegenerate--;
        if($this->toRegenerate <= 0) {
            $this->addHealth($this->regenerateHealth);
            $this->toRegenerate = $this->toRegenerateMax;
        }
    }

    /**
     * Translate message colours
     *
     * @param string $message
     * @return string Message
     */
    private function translateColours($message) {
        $message = str_replace("{BLACK}", TextFormat::BLACK, $message);
        $message = str_replace("{DARK_BLUE}", TextFormat::DARK_BLUE, $message);
        $message = str_replace("{DARK_GREEN}", TextFormat::DARK_GREEN, $message);
        $message = str_replace("{DARK_AQUA}", TextFormat::DARK_AQUA, $message);
        $message = str_replace("{DARK_RED}", TextFormat::DARK_RED, $message);
        $message = str_replace("{DARK_PURPLE}", TextFormat::DARK_PURPLE, $message);
        $message = str_replace("{GOLD}", TextFormat::GOLD, $message);
        $message = str_replace("{GRAY}", TextFormat::GRAY, $message);
        $message = str_replace("{DARK_GRAY}", TextFormat::DARK_GRAY, $message);
        $message = str_replace("{BLUE}", TextFormat::BLUE, $message);
        $message = str_replace("{GREEN}", TextFormat::GREEN, $message);
        $message = str_replace("{AQUA}", TextFormat::AQUA, $message);
        $message = str_replace("{RED}", TextFormat::RED, $message);
        $message = str_replace("{LIGHT_PURPLE}", TextFormat::LIGHT_PURPLE, $message);
        $message = str_replace("{YELLOW}", TextFormat::YELLOW, $message);
        $message = str_replace("{WHITE}", TextFormat::WHITE, $message);

        $message = str_replace("{OBFUSCATED}", TextFormat::OBFUSCATED, $message);
        $message = str_replace("{BOLD}", TextFormat::BOLD, $message);
        $message = str_replace("{STRIKETHROUGH}", TextFormat::STRIKETHROUGH, $message);
        $message = str_replace("{UNDERLINE}", TextFormat::UNDERLINE, $message);
        $message = str_replace("{ITALIC}", TextFormat::ITALIC, $message);
        $message = str_replace("{RESET}", TextFormat::RESET, $message);
        $message = str_replace("{>}", TextFormat::BOLD . TextFormat::RED . "> " . TextFormat::RESET, $message);
        $message = str_replace("{<}", TextFormat::BOLD . TextFormat::RED . " <" . TextFormat::RESET, $message);
        $message = str_replace("{*}", TextFormat::BOLD . TextFormat::GRAY . "[" . TextFormat::RED . "Bot" . TextFormat::GRAY . "] " . TextFormat::RESET, $message);

        return $message;
    }

}