<?php

declare(strict_types=1);

namespace WukeDev\Bottle\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\Plugin;
use WukeDev\Bottle\Main;
use WukeDev\Bottle\Item\Bottle;
use pocketmine\entity\utils\ExperienceUtils;
use WukeDev\Bottle\Item\LevelsBottle;

class LevelsBottleCommand extends Command implements PluginIdentifiableCommand{
    public $playerData = [];
    
    public function __construct()
    {
        parent::__construct("levelsbottle", "Gives an exp Bottle which can be redeemed for exp levels", "/levelsbottle <Levels> <Amount>(default 1)");
        $this->register($this->getPlugin()->getServer()->getCommandMap());
        $this->setPermission("use.levelsbottle");
        $this->config = $this->getPlugin()->getConfig();
        $this->minimumLevel = $this->config->get("minimumLevel");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        
        if (!$sender->hasPermission("use.levelsbottle"))
        {
            $sender->sendMessage(TextFormat::RED . "Must have permission to use command");
            return false;
        }
        
        //condition to check if any arguments are passed
        if (!isset($args[0])) 
        {
            $sender->sendMessage(TextFormat::RED . TextFormat::BOLD . $this->getUsage());
            return false;

        }

        //checks if player
        if (!$sender instanceof Player){
            $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . "Must be a player to use command"); 
            return false;
        }

        if ($args[0] == "help"){
            $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . $this->getUsage());
            return true;
        }

        if ($args[0] == "about"){
            $sender->sendMessage(TextFormat::BOLD . TextFormat::GOLD . "Made by WukeDev");
            $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . $this->getUsage() . "\n");
            $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . $this->getDescription() . "\n");
        }
        
        if ($args[0] == "all"){
            $levels = intval($sender->getXpLevel());

            if ($levels <= 0){
                $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . "You're exp level is too low");
            }
            
            //item object initialization
            $Bottle = new LevelsBottle($levels);
            $Bottle->setCount(1);
            
            //final check
            if ($sender->getPlayer()->getInventory()->canAddItem($Bottle))
            {
            $sender->getPlayer()->getInventory()->addItem($Bottle);
            $sender->getPlayer()->subtractXpLevels($levels);
            $sender->sendMessage(TextFormat::BOLD . TextFormat::BLUE . "You bottled " . strval($levels) . " levels");
            return true;
            }
            else {
                $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . "Inventory full");
                return false;
            }
        }

        //formats each argument
        try {
            
            $levelInput = intval($args[0]);
            
            if (isset($args[1])){
                $amount = intval($args[1]);
            }
            else{
                //default value
                $amount = 1;
            }
            
            $levels = $levelInput * $amount;
            
            //exp level check
            if ($levels <= 0){
                $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . "Inputs must be numbers greater than 0");
                return false;
            }
            
            if ($levels > $sender->getXpLevel())
            {
                $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . "Your exp level is too low");
                return false;
            }

            if ($levels < $this->minimumLevel){
                $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . "You must bottle over " . $this->minimumLevel . " levels of exp");
                return false;
            }

            //item object initialization
            $Bottle = new LevelsBottle($levelInput);
            $Bottle->setCount($amount);
            
            //final check
            if ($sender->getPlayer()->getInventory()->canAddItem($Bottle))
            {
                $sender->getPlayer()->getInventory()->addItem($Bottle);
                $sender->getPlayer()->subtractXpLevels($levels);
                $sender->sendMessage(TextFormat::BOLD . TextFormat::BLUE . "You bottled " . strval($levels) . " levels");
                return true;

            }
            else {
                $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . "Inventory full");
                return false;
            }
            
        } catch (\Throwable $th) {
            $sender->sendMessage(TextFormat::BOLD . TextFormat::RED . $this->getUsage());
            return false;
        }
        
    }
    
    public function reduceExp(Player $player, int $levels){
        $player->setCurrentTotalXp($player->getCurrentTotalXp() - ExperienceUtils::getXpToReachLevel($levels));
    }

    public function getPlugin(): Plugin
    {
        return Main::getInstance();
    }

    
}