<?php

/*
 *               _ _
 *         /\   | | |
 *        /  \  | | |_ __ _ _   _
 *       / /\ \ | | __/ _` | | | |
 *      / ____ \| | || (_| | |_| |
 *     /_/    \_|_|\__\__,_|\__, |
 *                           __/ |
 *                          |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Altay
 *
 */

declare(strict_types=1);

namespace pocketmine\entity\hostile;

use pocketmine\entity\Ageable;
use pocketmine\entity\behavior\FloatBehavior;
use pocketmine\entity\behavior\LookAtPlayerBehavior;
use pocketmine\entity\behavior\MeleeAttackBehavior;
use pocketmine\entity\behavior\NearestAttackableTargetBehavior;
use pocketmine\entity\behavior\RandomLookAroundBehavior;
use pocketmine\entity\behavior\RandomStrollBehavior;
use pocketmine\entity\Monster;
use pocketmine\entity\passive\Villager;
use pocketmine\entity\Smite;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use function mt_rand;

class Ravager extends Monster implements Ageable, Smite{
	public const NETWORK_ID = self::RAVAGER;

	public $width = 1.5;
	public $height = 1.9;

	protected function initEntity() : void{
		$this->setMaxHealth(50);
		$this->setFollowRange(35);
		$this->setAttackDamage(12);
		$this->setMovementSpeed(0.4);

		parent::initEntity();
	}

	public function getName() : string{
		return "Ravager";
	}

	public function getDrops() : array{
		$drops = [
			ItemFactory::get(Item::SADDLE, 0, mt_rand(0, 1))
		];
		return $drops;
	}

	public function getXpDropAmount() : int{
		return $this->getRevengeTarget() instanceof Player ? 50 : 10;
	}

	protected function addBehaviors() : void{
		$this->behaviorPool->setBehavior(0, new FloatBehavior($this));
		$this->behaviorPool->setBehavior(1, new MeleeAttackBehavior($this, 1.0));
		$this->behaviorPool->setBehavior(2, new RandomStrollBehavior($this, 1.0));
		$this->behaviorPool->setBehavior(3, new LookAtPlayerBehavior($this, 8.0));
		$this->behaviorPool->setBehavior(4, new RandomLookAroundBehavior($this));

		$this->targetBehaviorPool->setBehavior(1, new NearestAttackableTargetBehavior($this, Player::class));
		$this->targetBehaviorPool->setBehavior(2, new NearestAttackableTargetBehavior($this, Villager::class));
	}
}
