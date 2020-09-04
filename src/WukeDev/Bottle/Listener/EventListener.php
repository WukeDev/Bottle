<?php

declare(strict_types=1);

namespace WukeDev\Bottle\Listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use WukeDev\Bottle\Item\ExpBottle;
use WukeDev\Bottle\Item\LevelsBottle;
use pocketmine\block\Chest;
use pocketmine\block\Block;

class EventListener extends Listener{
    public function onInteract(PlayerInteractEvent $event){
        
        //checks if player activates bottle item
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();
        $count = $item->getCount();
        $meta = $item->getDamage();

        //LevelsBottle check
        $TestBottle = new LevelsBottle($meta);
        //if holding bottle item
        if ($item->getNamedTag() == $TestBottle->getNamedTag()){
            
            //if container block
            if ($block->getId() == Block::ITEM_FRAME_BLOCK or $block instanceof Chest){
                return false;
            }
            $TestBottle->activate($player);
            $NewBottle = new LevelsBottle($meta);
            $NewBottle->setCount($count -= 1);
            $player->getInventory()->setItemInHand($NewBottle);
            return true;
        }

        //ExpBottle check
        $TestBottle = new ExpBottle($meta);
        //if holding bottle item
        if ($item->getNamedTag() == $TestBottle->getNamedTag()){
            
            //if container block
            if ($block->getId() == Block::ITEM_FRAME_BLOCK or $block instanceof Chest){
                return false;
            }
            $TestBottle->activate($player);
            $NewBottle = new ExpBottle($meta);
            $NewBottle->setCount($count -= 1);
            $player->getInventory()->setItemInHand($NewBottle);
            return true;
        }
         
    }  
}