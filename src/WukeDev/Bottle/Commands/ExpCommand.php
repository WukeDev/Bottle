<?php

declare(strict_types=1);

namespace WukeDev\Bottle\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\permission\DefaultPermissions;
use pocketmine\utils\TextFormat;
use pocketmine\Server;
use WukeDev\Bottle\Item\ExpBottle;
use WukeDev\Bottle\Item\LevelsBottle;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\Plugin;
use WukeDev\Bottle\Main;

class ExpCommand extends Command implements PluginIdentifiableCommand{
    public function __construct()
    {
        parent::__construct("exp", "Gives players xp Bottles which can be redeemed for xp levels", "/exp <Player> <Levels> <Amount> <Type>");
        $this->register($this->getPlugin()->getServer()->getCommandMap());
        $this->setPermission("use.exp");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        
        if (!$sender->hasPermission("use.exp"))
        {
            return false;
        }
        
        //condition to check if any arguments are passed
        if (!isset($args[0])) 
        {
            $sender->sendMessage(TextFormat::RED . TextFormat::BOLD . $this->getUsage());
            return false;
        }

        //formats each argument to the right type
        try {
            $player = Server::getInstance()->getPlayer($args[0]);
            $levels = intval($args[1]);
            $count = intval($args[2]);
            $type = $args[3];
        } catch (\Throwable $th) {
            $sender->sendMessage(TextFormat::RED . TextFormat::BOLD . $this->getUsage());
            return false;
        }

        if ($type == "exp" or "experience" or "xp" or "e" or "x"){
            $Bottle = new ExpBottle($levels);
            $Bottle->setCount($count);
        }
        elseif ($type == "levels" or "lev" or "l"){
            $Bottle = new LevelsBottle($levels);
            $Bottle->setCount($count);
        }
        else{
            $sender->sendMessage(TextFormat::RED . TextFormat::BOLD . "Type argument must be either levels or exp");
            return false;
        }
        
        
        //gives the player the Bottle
        if ($player->getInventory()->canAddItem($Bottle)) 
        {
            $player->getInventory()->addItem($Bottle);
        }
        else{
            $sender->sendMessage(TextFormat::RED . TextFormat::BOLD . "Player has too many items in their inventory");
            return false;
        }

        return true;
        
    }

    public function getPlugin(): Plugin
    {
        return Main::getInstance();
    }
}