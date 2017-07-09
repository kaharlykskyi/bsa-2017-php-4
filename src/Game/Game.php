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
    private $username = "";

    /**
     * Initialize new game world.
     */
    public function __construct()
    {
        $this->hall = new Room\Hall;
        $this->basement = new Room\Basement;
        $this->bedroom = new Room\Bedroom;
        $this->cabinet = new Room\Cabinet;
        $this->corridor = new Room\Corridor;

        //creating game world (rooms, coins)
        $this->hall->addCoinToRoom(new Coin());
        $this->basement->addCoinToRoom(new Coin());
        $this->basement->addCoinToRoom(new Coin());
        $this->cabinet->addCoinToRoom(new Coin());
        $this->bedroom->addCoinToRoom(new Coin());
        $this->corridor->addAccessibleRoom($this->hall);
        $this->corridor->addAccessibleRoom($this->cabinet);
        $this->corridor->addAccessibleRoom($this->bedroom);
        $this->hall->addAccessibleRoom($this->basement);
        $this->hall->addAccessibleRoom($this->corridor);
        $this->basement->addAccessibleRoom($this->cabinet);
        $this->basement->addAccessibleRoom($this->hall);
        $this->cabinet->addAccessibleRoom($this->corridor);
        $this->bedroom->addAccessibleRoom($this->corridor);

        $this->user = new User($this->hall, $this->username);
        $this->executor = new Executor($this);
    }

    public function start(Reader $reader, Writer $writer)
    {
        $writer->writeln("You can't play yet. Please read input and convert it to commands.");

        do {
            $writer->write("Type your name: ");
            $this->username = trim($reader->read());
        } while (empty($this->username));

        while(true) {
            $writer->write($this->username."> ");
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

}
