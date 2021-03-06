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

namespace pocketmine\item;

class WrittenBook extends WritableBook{

    const GENERATION_ORIGINAL = 0;
	const GENERATION_COPY = 1;
	const GENERATION_COPY_OF_COPY = 2;
	const GENERATION_TATTERED = 3;

	const TAG_GENERATION = "generation"; //TAG_Int
	const TAG_AUTHOR = "author"; //TAG_String
	const TAG_TITLE = "title"; //TAG_String

	public function __construct(int $meta = 0){
		Item::__construct(self::WRITTEN_BOOK, $meta, "Written Book");
	}

	public function getMaxStackSize() : int{
		return 16;
	}

	/**
	 * Returns the generation of the book.
	 * Generations higher than 1 can not be copied.
	 *
	 * @return int
	 */
	public function getGeneration() : int{
		return $this->getNamedTag()->getInt(self::TAG_GENERATION, -1);
	}

	/**
	 * Sets the generation of a book.
	 *
	 * @param int $generation
	 */
	public function setGeneration(int $generation){
		if($generation < 0 or $generation > 3){
			throw new \InvalidArgumentException("Generation \"$generation\" is out of range");
		}
		$namedTag = $this->getNamedTag();
		$namedTag->setInt(self::TAG_GENERATION, $generation);
		$this->setNamedTag($namedTag);
	}

	/**
	 * Returns the author of this book.
	 * This is not a reliable way to get the name of the player who signed this book.
	 * The author can be set to anything when signing a book.
	 *
	 * @return string
	 */
	public function getAuthor() : string{
		return $this->getNamedTag()->getString(self::TAG_AUTHOR, "");
	}

	/**
	 * Sets the author of this book.
	 *
	 * @param string $authorName
	 */
	public function setAuthor(string $authorName){
		$namedTag = $this->getNamedTag();
		$namedTag->setString(self::TAG_AUTHOR, $authorName);
		$this->setNamedTag($namedTag);
	}

	/**
	 * Returns the title of this book.
	 *
	 * @return string
	 */
	public function getTitle() : string{
		return $this->getNamedTag()->getString(self::TAG_TITLE, "");
	}

	/**
	 * Sets the author of this book.
	 *
	 * @param string $title
	 */
	public function setTitle(string $title){
		$namedTag = $this->getNamedTag();
		$namedTag->setString(self::TAG_TITLE, $title);
		$this->setNamedTag($namedTag);
	}
}