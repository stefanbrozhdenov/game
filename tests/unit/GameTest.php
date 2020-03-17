<?php

use PHPUnit\Framework\TestCase;
use Services\Game;

class GameTest extends TestCase
{

    private $gameService;

    protected function setUp(): void
    {
        $this->gameService = new Game();
        $this->gameService->setPlayers('Stefan,Ivan,Petar');
        $this->gameService->setChoises('Rock,Paper,Scissors');
        $this->gameService->setRounds(3);
    }

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testPlay()
    {
        $result =  $this->gameService->play();
        $winner = $this->gameService->getFinalWinner();
        if ($winner === $this->gameService::NOWINNER) {
            $this->expectOutputString("All players have equal scores!We don't have a winner", $result);
        } else {
            $this->expectOutputString("The winner is {$winner}", $result);
        }
    }

    public function testWinner()
    {
        $this->assertCount(
            1,
            $this->gameService->winner(),
            "Array should have only one winner"
        );
    }

    public function testGeneratePlayersChoise()
    {
        $this->assertIsArray($this->invokeMethod($this->gameService, 'generatePlayersChoise', array(["Ivan", "Petar", "Stefan"])));
    }

    public function testGenerateComputerChoise()
    {
        $this->assertIsString($this->invokeMethod($this->gameService, 'generateComputerChoise'));
    }

    public function testGetWinners()
    {
        $this->assertEquals(
            ['Ivan' => 'Rock'],
            $this->invokeMethod($this->gameService, 'getWinners', array('Rock', ['Ivan' => 'Rock', 'Stefan' => 'Paper'])),
            "Expected winner don't match"
        );
    }
}
