<?php

/*
 *
 *    _______                    _
 *   |__   __|                  (_)
 *      | |_   _ _ __ __ _ _ __  _  ___
 *      | | | | | '__/ _` | '_ \| |/ __|
 *      | | |_| | | | (_| | | | | | (__
 *      |_|\__,_|_|  \__,_|_| |_|_|\___|
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Turanic
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Sign as TileSign;
use pocketmine\tile\Tile;

class SignPost extends Transparent{

	protected $id = self::SIGN_POST;

	protected $itemId = Item::SIGN;

	/**
	 * SignPost constructor.
	 *
	 * @param int $meta
	 */
	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 1;
	}

	/**
	 * @return bool
	 */
	public function isSolid(){
		return false;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Sign Post";
	}

	protected function recalculateBoundingBox(){
        return null;
    }


	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
        if($face !== Vector3::SIDE_DOWN){

            Tile::createTile(Tile::SIGN, $this->getLevel(), TileSign::createNBT($this, $face, $item, $player));

            if($face === Vector3::SIDE_UP){
				$this->meta = floor((($player->yaw + 180) * 16 / 360) + 0.5) & 0x0F;
				$this->getLevel()->setBlock($block, $this, true);
			}else{
				$this->meta = $face;
				$this->getLevel()->setBlock($block, Block::get(Block::WALL_SIGN, $this->meta), true);
			}

			return true;
		}

		return false;
	}

	/**
	 * @param int $type
	 *
	 * @return bool|int
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(Vector3::SIDE_DOWN)->getId() === Block::AIR){
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		return false;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	public function getVariantBitmask(): int{
        return 0;
    }
}
