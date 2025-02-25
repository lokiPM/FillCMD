<?php

namespace lokiPM\FillCMD;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\Player;

class Main extends PluginBase {

    public function onEnable(){
        $this->getLogger()->info("FillCMD Plugin has been enabled.");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        if($command->getName() === "fill"){
            if(!$sender instanceof Player){
                $sender->sendMessage(TextFormat::RED . "This command can only be used in-game.");
                return true;
            }

            if(!$sender->hasPermission("fillcmd.use")){
                $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command.");
                return true;
            }

            if(count($args) !== 7){
                $sender->sendMessage(TextFormat::RED . "Usage: /fill <x1> <y1> <z1> <x2> <y2> <z2> <block>");
                return true;
            }

            list($x1, $y1, $z1, $x2, $y2, $z2, $blockName) = $args;
            $level = $sender->getLevel();

            $block = Block::fromString($blockName);
            if($block === null){
                $sender->sendMessage(TextFormat::RED . "Invalid block name.");
                return true;
            }

            $x1 = (int)$x1;
            $y1 = (int)$y1;
            $z1 = (int)$z1;
            $x2 = (int)$x2;
            $y2 = (int)$y2;
            $z2 = (int)$z2;

            for($x = min($x1, $x2); $x <= max($x1, $x2); $x++){
                for($y = min($y1, $y2); $y <= max($y1, $y2); $y++){
                    for($z = min($z1, $z2); $z <= max($z1, $z2); $z++){
                        $level->setBlock(new Vector3($x, $y, $z), $block);
                    }
                }
            }

            $sender->sendMessage(TextFormat::GREEN . "Blocks have been filled.");
            return true;
        }

        return false;
    }
}
