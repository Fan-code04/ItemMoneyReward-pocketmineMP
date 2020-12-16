<?php

namespace Fan;


use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;

class Main extends PluginBase implements Listener{ 

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);    
        $this->getLogger()->info("Activez!"); 

        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		if(is_null($this->money)){
			$this->getServer()->getPluginManager()->disablePlugin($this);
			$this->getServer()->getLogger()->notice("[ItemBillet] Ce plugin a besoin de EconomyAPI pour pouvoir fonctionner §");
		}
    }

    public function onDisable() {
$this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->getLogger()->info("Désactivé!");
    }

    public function onInteract(PlayerInteractEvent $event){
         
        $player = $event->getPlayer();
        $monitem = $event->getItem();
        $configs = $this->cfg->getAll();
        $mydata = $this->getInConfig($monitem->getId(),$monitem->getDamage());

        if (!is_null($this->getinConfig($monitem->getId(),$monitem->getDamage()))){

            $id = Item::fromString($mydata);

            if ($monitem->getId() === $id->getId() and ($id->getDamage() == 0 or $monitem->getDamage() == $id->getDamage())) {

                $dedans = $configs[$mydata];

                        $player->getInventory()->removeItem(Item::get($id->getId(),$id->getDamage(),1));

                        $player->sendMessage($dedans["message"]);
                        $this->money->addMoney($player, $dedans["montant"]);
            }
        }
    }
 
    public function getInConfig(int $id, int $damage)
    {
        $configs = $this->cfg->getAll();
        $ids = array_keys($configs);

        if (in_array("$id",$ids)) {

            return "$id";

        } else if (in_array("$id:$damage",$ids)) {

            return "$id:$damage";

        }

        return null;
    }

}
