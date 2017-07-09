<?php
/**
 * Created by PhpStorm.
 * User: kaharlykskyi
 * Date: 09.07.17
 * Time: 14:11
 */

namespace BinaryStudioAcademy\Game\Contracts\User;

use BinaryStudioAcademy\Game\Contracts\Room\AbstractRoom;

class User
{
    private $inventory = [];
    private $username;
    public $room;

    /**
     * Initialize new user
     * @param AbstractRoom $room
     * @param string $username
     */
    public function __construct(AbstractRoom $room, string $username)
    {
        $this->room = $room;
        $this->username = $username;
    }

    /**
     * User command `go`. Transfer user to new room
     * @param string $room
     */
    public function go(string $room)
    {
        $this->room = $this->room->getRoom($room);
    }

    /**
     * User command `grab`. Grab 1 coin from current room
     */
    public function grab()
    {
        $this->inventory['coin'][] = $this->room->getCoin();
    }

    /**
     * Method returns count of coins in user inventory
     * @return int
     */
    public function InventoryCoins()
    {
        return count($this->inventory['coin'] ?? []);
    }


}