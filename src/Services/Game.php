<?php

namespace Services;

use Services\GameInterface;

class Game implements GameInterface
{

    private $rounds = 1;
    private $players = [];
    private $choises = [];
    private $finalWinner;
    const NOWINNER = 'NOWINNER';

    /**
     * This method shoud return the winner of the games
     *
     * @returns void
     */
    public function play(): void
    {
        $maxRounds = 1;
        $winners = [];
        while ($maxRounds <= $this->rounds) {
            $winners[] = array_keys($this->winner())[0];
            $maxRounds++;
        }
        $winenrsScore = array_count_values($winners);
        $highestScore = max($winenrsScore);
        if ($highestScore === 1) {
            $this->finalWinner = self::NOWINNER;
            print("All players have equal scores!We don't have a winner");
            return;
        }

        foreach ($winenrsScore as $key => $score) {
            if ($score === $highestScore) {
                $this->finalWinner = $key;
                print("The winner is {$this->finalWinner}");
                break;
            }
        }
    }

    /**
     * Group all winners from all rounds in array
     *
     * @returns array
     */
    public function winner(): array
    {
        $computerChoise =  $this->generateComputerChoise();
        $playerChoise = $this->generatePlayersChoise($this->players);
        $winners = $this->getWinners($computerChoise, $playerChoise);
        if (empty($winners)) {
            $winners =  $this->winner();
        }
        return $winners;
    }

    /**
     * Loop utill we have a winner
     * 
     * @param string $computerChoise
     * @param array $playerChoise
     *
     * @returns array
     */
    private function getWinners(string $computerChoise, array $playerChoise): array
    {

        $winners = [];
        foreach ($playerChoise as $name => $choise) {
            if ($choise === $computerChoise) {
                $winners[$name] = $choise;
            }
        }
        $winnerChoise = $this->generatePlayersChoise($winners);

        if (count($winners) === 0) {
            return $winners;
        }

        if (count($winners) !== 1) {
            $winners = $this->getWinners($computerChoise, $winnerChoise);
        }
        return $winners;
    }

    /**
     * Set choises form arguments
     * 
     * @param string $choises
     *
     * @returns void
     */
    public function setChoises(string $choises): void
    {
        $this->choises = explode(",", $choises);
    }

    /**
     * Generate random computer choise
     * 
     *
     * @returns string
     */
    private function generateComputerChoise(): string
    {
        $select = random_int(0, count($this->choises) - 1);
        return $this->choises[$select];
    }

    /**
     * Generate random players choise
     * @param array $players
     *
     * @returns array
     */
    private function generatePlayersChoise($players): array
    {
        $playerChoise = [];
        foreach ($players as $player) {
            $playerChoise[$player] = $this->generateComputerChoise();
        }
        return $playerChoise;
    }

    /**
     * Set players from arguments
     * @param string $players
     *
     * @returns void
     */
    public function setPlayers(string $players): void
    {
        $this->players = explode(",", $players);
    }

    /**
     * Set Rounds from arguments
     * @param int $rounds
     *
     * @returns void
     */
    public function  setRounds(int $rounds): void
    {
        $this->rounds = $rounds;
    }

    /**
     * Get finnal winner
     * 
     *
     * @returns string
     */
    public function  getFinalWinner(): ?string
    {
        return $this->finalWinner;
    }
}
