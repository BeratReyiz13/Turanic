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

namespace pocketmine\level;

use pocketmine\math\Vector3;
use pocketmine\utils\MainLogger;

class Position extends Vector3 {

	/** @var Level */
	public $level = null;

	/**
	 * @param int   $x
	 * @param int   $y
	 * @param int   $z
	 * @param Level $level
	 */
	public function __construct($x = 0, $y = 0, $z = 0, Level $level = null){
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		$this->level = $level;
	}

	public static function fromObject(Vector3 $pos, Level $level = null){
		return new Position($pos->x, $pos->y, $pos->z, $level);
	}

	/**
	 * Return a Position instance
	 * 
	 * @return Position
	 */
	public function asPosition() : Position{
		return new Position($this->x, $this->y, $this->z, $this->level);
	}

    /**
     * Returns the target Level, or null if the target is not valid.
     * If a reference exists to a Level which is closed, the reference will be destroyed and null will be returned.
     *
     * @return Level|null
     */
    public function getLevel(){
        if($this->level !== null and $this->level->isClosed()){
            MainLogger::getLogger()->debug("Position was holding a reference to an unloaded Level");
            $this->level = null;
        }

        return $this->level;
    }

    /**
     * Sets the target Level of the position.
     *
     * @param Level|null $level
     *
     * @return $this
     *
     * @throws \InvalidArgumentException if the specified Level has been closed
     */
    public function setLevel(Level $level = null){
        if($level !== null and $level->isClosed()){
            throw new \InvalidArgumentException("Specified level has been unloaded and cannot be used");
        }

        $this->level = $level;
        return $this;
    }

    /**
     * Checks if this object has a valid reference to a loaded Level
     *
     * @return bool
     */
    public function isValid() : bool{
        return $this->getLevel() instanceof Level;
    }

    /**
     * Returns a side Vector
     *
     * @param int $side
     * @param int $step
     *
     * @return Position
     *
     * @throws LevelException
     */
    public function getSide($side, $step = 1){
        assert($this->isValid());

        return Position::fromObject(parent::getSide($side, $step), $this->level);
    }

    public function __toString(){
        return "Position(level=" . ($this->isValid() ? $this->getLevel()->getName() : "null") . ",x=" . $this->x . ",y=" . $this->y . ",z=" . $this->z . ")";
    }

    public function toArray() : array{
        return [$this->x, $this->y, $this->z, $this->level->getFolderName()];
    }

    public function equals(Vector3 $v) : bool{
        if($v instanceof Position){
            return parent::equals($v) and $v->getLevel() === $this->getLevel();
        }
        return parent::equals($v);
    }

}