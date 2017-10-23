<?php

/*
 *
 * ___  ___               _ _____            __ _         
 * |  \/  |              (_)  __ \          / _| |        
 * | .  . | __ _ ______ _ _| /  \/_ __ __ _| |_| |_ _   _ 
 * | |\/| |/ _` |_  / _` | | |   | '__/ _` |  _| __| | | |
 * | |  | | (_| |/ / (_| | | \__/\ | | (_| | | | |_| |_| |
 * \_|  |_/\__,_/___\__,_|_|\____/_|  \__,_|_|  \__|\__, |
 *                                                   __/ |
 *                                                  |___/
 * Copyright (C) 2017 @MazaiCrafty (https://twitter.com/MazaiCrafty)
 *
 * This program is free software
 * Plugin for Pocketmine-MP
 *
 * Description:
 * It's a plugin with a Report function and NG word function.
 *
 *
 */

namespace MazaiCrafty;

/*
 * Use
 */

# Base
use pocketmine\plugin\PluginBase;

# Server
use pocketmine\Server;

# Player
use pocketmine\Player;

# Command
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

# Event
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

# Utils
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class MainClass extends PluginBase implements Listener
{
    function onEnable()
    {
        $server = Server::getInstance();
        
        $server->getLogger()->info("˜bChat AdminTools plugin enabling");
        if (!file_exists($this->getDataFolder())) {
            mkdir($this->getDataFolder(), 0744, true);
            
            $this->Config = new Config($this->getDataFolder() . "Config.yml", Config::YAML);
            $this->Report = new Config($this->getDataFolder() . "Report.yml", Config::YAML);
            
            if (!$this->Config->exists("Config")) {
                $this->Config->set("Prohibited Word", "Example");
                $this->Config->save();
                
                if (!$this->Report->exists("Report")) {
                    $this->Report->set("Player", "Report");
                    
                    $this->Report->save();
                }
            }
        }
        
        function onDisable()
        {
            $server = Server::getInstance();
            
            $server->getLogger()->info("˜eChat Admintool plugin disabling...");
        }
        
        function onCommand(CommandSender $sender, Command $command,string $label, array $args): bool
        {
            switch (strtolower($command->getName())) {
                case 'report':
                    if (!isset($args[0]))
                        return false;
                    $player = $sender->getName();
                    $this->report->set($player, $args[0]);
                    $this->report->save();
                    $sender->sendMessage("Send completely!");
                    return true;
                    break;
                
                case 'report.list':
                    $data    = $this->report->getAll(true);
                    $dataall = implode("\n", $data);
                    $sender->sendMessage("" . $dataall . "");
                    return true;
                    break;
            }
        }

        function registerAll()
        {
            $server = Server::getInstance();
            $server->getPluginManager()->registerEvents($this, $this);
        }
        
        /**
         * chatRestrict
         *
         * @param $event PlayerChatEvent
         */
        function chatRestrict(PlayerChatEvent $event)
        {
            $message = $event->getMessage();
            $player  = $event->getPlayer();
            
            $str = $this->Config->get("Prohibited Word");
            
            $replace = $this->Config->get("Replace");
            
            $chat = str_replace($str, $replace, $message);
            
            $event->setMessage($chat);
            
        }
    }
}