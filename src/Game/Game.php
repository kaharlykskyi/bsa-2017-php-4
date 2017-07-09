<?php

namespace BinaryStudioAcademy\Game;

use BinaryStudioAcademy\Game\Contracts\Io\Reader,
    BinaryStudioAcademy\Game\Contracts\Io\Writer,
    BinaryStudioAcademy\Game\Contracts\Room,
    BinaryStudioAcademy\Game\Contracts\Coin\Coin,
    BinaryStudioAcademy\Game\Contracts\User\User;

class Game
{
    const COINS_TO_WIN = 5;
    public $user;
    private $executor;

    /**
     * Initialize new game world.
     */
    public function __construct()
    {
        $hall = new Room\Hall;
        $basement = new Room\Basement;
        $bedroom = new Room\Bedroom;
        $cabinet = new Room\Cabinet;
        $corridor = new Room\Corridor;

        //creating game world (rooms, coins)
        $hall->addCoinToRoom(new Coin());
        $basement->addCoinToRoom(new Coin());
        $basement->addCoinToRoom(new Coin());
        $cabinet->addCoinToRoom(new Coin());
        $bedroom->addCoinToRoom(new Coin());
        $corridor->addAccessibleRoom($hall);
        $corridor->addAccessibleRoom($cabinet);
        $corridor->addAccessibleRoom($bedroom);
        $hall->addAccessibleRoom($basement);
        $hall->addAccessibleRoom($corridor);
        $basement->addAccessibleRoom($cabinet);
        $basement->addAccessibleRoom($hall);
        $cabinet->addAccessibleRoom($corridor);
        $bedroom->addAccessibleRoom($corridor);

        $this->user = new User($hall);
        $this->executor = new Executor($this);
    }

    public function start(Reader $reader, Writer $writer)
    {
        while(true) {
            $writer->write("game> ");
            $this->run($reader, $writer);
            if ($this->executor->isFinished()) {
                break;
            }
        }
        return false;
    }

    public function run(Reader $reader, Writer $writer)
    {
        $input = trim($reader->read());
        $args = explode(" ", $input);
        $command = array_shift($args);
        $this->executor->command($command, $args);
        $writer->writeln($this->executor->getMessage());
    }

    public function __get($var) {
        if ($var === "user") {
            return $this->user;
        }
        return null;
    }
}
