<?php
/**
 * Created by PhpStorm.
 * User: kaharlykskyi
 * Date: 08.07.17
 * Time: 11:05
 */

namespace BinaryStudioAcademy\Game\Contracts\Room;

use BinaryStudioAcademy\Game\Contracts\Coin\Coin,
    BinaryStudioAcademy\Game\Exception\NotFound;


abstract class AbstractRoom
{
    public $room = array();
    public $coin = array();

    /**
     * Method returns name of current room
     * @return bool|string
     * @throws NotFound exception if room not found
     */
    public function getRoomName()
    {
        if (get_called_class() !== false) {
            $roomName = str_replace(__NAMESPACE__ . "\\", "", get_called_class());
            return strtolower($roomName);
        } else {
            throw new NotFound("Undefined room.");
        }
    }

    /**
     * Method add accessible room to current
     * @param AbstractRoom $room
     * @return $this
     */
    public function addAccessibleRoom(AbstractRoom $room)
    {
        $this->room[$room->getRoomName()] = $room;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws NotFound exception if room not found
     */
    public function getRoom(string $name)
    {
        if (!isset($this->room[$name])) {
            throw new NotFound("Can not go to " . $name. ".");
        }
        return $this->room[$name];
    }

    /**
     * Method add coin to current room
     * @param Coin $coin
     * @return AbstractRoom
     */
    public function addCoinToRoom(Coin $coin) : AbstractRoom
    {
        $this->coin[] = $coin;
        return $this;
    }

    /**
     * @return mixed
     * @throws NotFound
     */
    public function getCoin()
    {
        if (empty($this->coin)) {
            throw new NotFound("There is no coins left here. Type 'where' to go to another location.");
        }
        return array_pop($this->coin);
    }

    /**
     * Method returns accessible rooms that user can go from current room
     * @return string
     */
    public function AccessibleRoom() : string
    {
        $roomNames = array_keys($this->room);
        return implode(', ', $roomNames);
    }

    /**
     * Method returns count of coins
     * @return array|int
     */
    public function CoinCount() : int
    {
        return count($this->coin);
    }
}