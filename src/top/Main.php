<?php

namespace top;

 use pocketmine\plugin\PluginBase;
 use pocketmine\Player; 
 use pocketmine\Server;
 use pocketmine\event\Listener;
 use pocketmine\event\player\PlayerJoinEvent;
 
 use pocketmine\command\Command;
 use pocketmine\command\CommandSender;
 
 use pocketmine\item\Item;
 use pocketmine\event\block\BlockPlaceEvent;
 use pocketmine\event\block\BlockBreakEvent;
 
 use pocketmine\block\Block;
 
 use pocketmine\utils\Config;
 use pocketmine\math\Vector3;
 
 use pocketmine\level\particle\{DustParticle, FlameParticle, FloatingTextParticle, EntityFlameParticle, CriticalParticle, ExplodeParticle, HeartParticle, LavaParticle, MobSpawnParticle, SplashParticle};
 

class Main extends PluginBase implements Listener {
	
	public $plugin;

	public function onEnable(){
		$this->getLogger()->info("§b EasyTop Enable...");
		
		@mkdir($this->getDataFolder() . "topten_data");
		
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	}
	
	public function setfarmdata(BlockBreakEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();
		$break = $event->getBlock();
		if($break->getId() === 141 || $break->getId() === 142 || $break->getId() === 59 || $break->getId() === 86 || $break->getId() === 103){
			$data = new Config($this->getDataFolder() . "topten_data/topfarm.yml", Config::YAML);
			$up = $data->get($name);
			$data->set($name, $up + 0.1);
			$data->save();
		}
	}
	
	public function createtopten(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$w = $this->getConfig()->get("world");
		$world = $player->getLevel()->getName() === "$w";
		$top = $this->getConfig()->get("enable");
		
		if($world){
			if($top == "true"){
				$this->TopFarm($player);
				$this->TopExp($player);
			
		}
	}
	
	public function settopdata(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();		
		
		$exp = new Config($this->getDataFolder() . "topten_data/topexp.yml", Config::YAML);
		$my = $player->getXpLevel();
		$exp->set($name, $my);
		$exp->save();
		
		$farm = new Config($this->getDataFolder() . "topten_data/topfarm.yml", Config::YAML);
		if(!$farm->exists($name)){
			$farm->set($name, 0);
			$farm->save();
		}
	}
	
	public function TopFarm($p){
		$player = $p->getPlayer();
		$data = new Config($this->getDataFolder() . "topten_data/topfarm.yml", Config::YAML);
		$swallet = $data->getAll();
		$c = count($swallet);
		$message = "";
		$top = "§d§lTop 10 player make farm!";
		arsort($swallet);
		$i = 1;
		foreach ($swallet as $name => $amount) {
					
			$message .= "§b ".$i.". §7".$name."  §akeep  §f".$amount." §aItem\n";
			if($i > 9){
				break;
			}
			++$i;
		}
		
		
		$x = $this->getConfig()->get("farm-x");
		$y = $this->getConfig()->get("farm-y");
		$z = $this->getConfig()->get("farm-x");
		
		$p = new FloatingTextParticle(new Vector3($x, $y + 1, $z), $message, $top);
		$player->getLevel()->addParticle($p);
	}
	
	public function TopExp($p){
		$player = $p->getPlayer();
		$data = new Config($this->getDataFolder() . "topten_data/topexp.yml", Config::YAML);
		$swallet = $data->getAll();
		$c = count($swallet);
		$message = "";
		$top = "§d§lTop 10 EXP!";
		arsort($swallet);
		$i = 1;
		foreach ($swallet as $name => $amount) {
					
			$message .= "§b ".$i.". §7".$name."    §f".$amount." §aXP_Level\n";
			if($i > 9){
				break;
			}
			++$i;
		}
		
		$x = $this->getConfig()->get("exp-x");
		$y = $this->getConfig()->get("exp-y");
		$z = $this->getConfig()->get("exp-x");
		
		$p = new FloatingTextParticle(new Vector3($x, $y + 1, $z), $message, $top);
		$player->getLevel()->addParticle($p);
	}
}
