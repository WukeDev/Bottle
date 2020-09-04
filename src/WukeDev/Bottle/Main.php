<?php

declare(strict_types=1);

namespace WukeDev\Bottle;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use WukeDev\Bottle\Commands\ExpCommand;
use pocketmine\utils\TextFormat;
use pocketmine\permission\PermissionManager;
use pocketmine\permission\Permission;
use WukeDev\Bottle\Commands\XpBottleCommand;
use WukeDev\Bottle\Commands\LevelsBottleCommand;
use pocketmine\event\player\PlayerInteractEvent;
use WukeDev\Bottle\Item\ExpBottle;
use WukeDev\Bottle\Item\LevelsBottle;
use pocketmine\block\Block;
use pocketmine\block\Chest;
use pocketmine\plugin\Plugin;


class Main extends PluginBase implements Listener{
    private static $instance;
    public $playerData = [];

    public function onEnable()
    {
        self::$instance = $this;
        
        
        $this->getLogger()->info(TextFormat::BOLD . TextFormat::DARK_GREEN . "Bottle," . TextFormat::GOLD . " Plugin Created By WukeDev");
        $this->getServer()->getCommandMap()->register("ExpCommand", new ExpCommand);
        $this->getServer()->getCommandMap()->register("XpBottleCommand", new XpBottleCommand);
        $this->getServer()->getCommandMap()->register("LevelsBottleCommand", new LevelsBottleCommand);
        
        $permissionExp = new Permission("use.exp", "for /exp command", "op");
        PermissionManager::getInstance()->addPermission($permissionExp);
        
        $permissionBottle = new Permission("use.xpbottle", "for /xpbottle command");
        PermissionManager::getInstance()->addPermission($permissionBottle);

        $permissionBottle = new Permission("use.levelsbottle", "for /levelsbottle command", "op");
        PermissionManager::getInstance()->addPermission($permissionBottle);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public static function getInstance(): Plugin{
        return self::$instance;
    }

}