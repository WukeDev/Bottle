<?php

declare(strict_types=1);

namespace WukeDev\Bottle\Item;


use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\level\particle\EntityFlameParticle;
use pocketmine\level\particle\HugeExplodeSeedParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\RedstoneParticle;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\utils\TextFormat;
use WukeDev\Bottle\Main;

class LevelsBottle extends Item{
    public function __construct($meta)
    {
        $config = Main::getInstance()->getConfig();
        
        parent::__construct($config->get("bottleID"), $meta, "Levels Bottle");
        $this->setCustomName(TextFormat::RESET . TextFormat::GREEN . TextFormat::BOLD . "Bottle");
        $this->setLore([TextFormat::RESET . TextFormat::LIGHT_PURPLE . "Redeem for " . strval($meta) . " levels"]);
        
        
        
    }

    public function playAnimations(Player $player){
        $level = $player->getLevel();
        
        //animations
        $level->addParticle(new HugeExplodeSeedParticle($player->getPosition()));
        $level->addParticle(new RedstoneParticle($player, 3));
        $level->addParticle(new EntityFlameParticle($player));
        $level->addParticle(new LavaParticle($player));
        $level->addSound(new BlazeShootSound($player));
        $player->sendMessage(TextFormat::BOLD . TextFormat::BLUE . "You redeemed " . strval($this->getDamage()) . " levels!");
    }

    public function activate(Player $player){
        $player->addXpLevels($player->getInventory()->getItemInHand()->getDamage());
        $this->playAnimations($player);
    }
    
}