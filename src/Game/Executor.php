<?php
/**
 * Created by PhpStorm.
 * User: kaharlykskyi
 * Date: 09.07.17
 * Time: 14:30
 */

namespace BinaryStudioAcademy\Game;


use BinaryStudioAcademy\Game\Exception\NotFound;

class Executor
{
    private $game;
    private $isFinished;
    private $msg;

    /**
     * Initialize new game.
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->isFinished = false;
        $this->msg = "";
    }

    /**
     * Receive user command and call appropriate method with args.
     * @param $command
     * @param $args
     */
    public function command($command, $args)
    {
        try {
            $reflectionMethod = new \ReflectionMethod(__CLASS__, $command);
            $reflectionMethod->invokeArgs($this, $args);
        } catch (\ReflectionException $e) {
            $this->msg = "Unknown command: '" . $command . "'.";
        }
    }

    /**
     * User command `go <room>`.
     * If room exists and accessible the user moves to this room.
     * @param $room
     */
    public function go($room)
    {
        try {
            $this->game->user->go($room);
            $this->where();
        } catch (NotFound $e) {
            $this->msg = $e->getMessage();
        }
    }

    /**
     * User command `where`.
     * Tells the user where he is and where he can go.
     */
    public function where()
    {
        $room = $this->game->user->room;
        $roomName = $room->getRoomName();
        $accessibleRooms = $room->AccessibleRoom();
        $this->msg = "You're at " . $roomName . ". You can go to: " . $accessibleRooms . ".";
    }

    /**
     * User command `grab`.
     * Takes a coin if it exists.
     * If count of coins is 5 => game over.
     */
    public function grab()
    {
        try {
            $this->game->user->grab();
            if ($this->game->user->InventoryCoins() === Game::COINS_TO_WIN) {
                $this->isFinished = true;
                $this->msg = "Good job. You've completed this quest. Bye!";
            } else {
                $this->msg = "Congrats! Coin has been added to inventory.";
            }
        } catch (NotFound $e)
        {
            $this->msg = $e->getMessage();
        }
    }

    /**
     * User command `status`.
     * Tells the user where he is and how many coins he has.
     */
    public function status()
    {
        $room = $this->game->user->room->getRoomName();
        $inventoryTotalCoins = $this->game->user->InventoryCoins();
        $this->msg = "You're at " . $room . ". You have " . $inventoryTotalCoins . " coins.";
    }

    /**
     * User command `observe`.
     * Tells the user how many coins are in current room.
     */
    public function observe()
    {
        $count = $this->game->user->room->CoinCount();
        $this->msg = "There " . $count . " coin(s) here.";
    }

    /**
     * User command `exit`.
     * Emergency exit from the game.
     */
    public function exit()
    {
        $this->isFinished = true;
        $this->msg = "Bye";
    }

    /**
     * User command `help`.
     * Tells the user a list of available commands.
     */
    public function help()
    {
        $this->msg =
            "\n  ################HELP################" . PHP_EOL .
            "  where - show info about current room and possible routes." . PHP_EOL .
            "  status - show info about their coins and current room." . PHP_EOL .
            "  help - show list of commands." . PHP_EOL .
            "  go <room> - go to the room." . PHP_EOL .
            "  observe - find coins in the room." . PHP_EOL .
            "  grab - pick up a coin." . PHP_EOL .
            "  exit - exit from game." . PHP_EOL;
    }

    /**
     * Method returns the value of isFinished property.
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    /**
     * Method return message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->msg ?? "";
    }
}