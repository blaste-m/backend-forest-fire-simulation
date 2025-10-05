<?php

namespace App\Service;

class SimulationService
{
    public function simulate(int $height, int $width, float $probab, array $initial): array {

        $grid = array_fill(0, $height, array_fill(0, $width, 0));

        foreach($initial as [$i, $j]) {
            $grid[$i][$j] = 1;
        }

        $steps = [];

        while($this->hasFire($grid)) {
            $steps[] = $grid;
            $grid = $this->playNextStep($grid, $probab);
        }

        $steps[] = $grid;

        return $steps;
    }

    private function hasFire(array $grid): bool
    {
        foreach($grid as $row) {
            if (in_array(1, $row))
                return true;
        }
        return false;
    }

    private function randomFloat(): float
    {
        return mt_rand() / mt_getrandmax();
    }

    /***
     * @param int[][] $grid grille représentant la forêt
     * @param float $p probabilité de propagation de feu
     * @return array état de la forêt à l'étape T + 1
     */
    private function playNextStep(array $grid, float $p): array
    {
        $newGrid = $grid;
        $height = count($grid);
        $width = count($grid[0]);

        for ($i = 0; $i < $height; $i++) {
            for ($j = 0; $j < $width; $j++) {
                if ($grid[$i][$j] === 1) {
                    $newGrid[$i][$j] = -1; // une case en feu devient case en cendre
                    foreach ([[0, 1], [1, 0], [0, -1], [-1, 0], // propagation orthogonale
                                 //[1, 1], [-1, -1], [1, -1], [-1, 1] // propagation diagonale
                             ] as [$delta_i, $delta_j])
                    {
                        $neighbor_i = $i + $delta_i;
                        $neighbor_j = $j + $delta_j;

                        if ($neighbor_i >= 0 && $neighbor_i < $height
                            && $neighbor_j >= 0 && $neighbor_j < $width
                            && $grid[$neighbor_i][$neighbor_j] === 0
                        ) {
                            if ($this->randomFloat() < $p) {
                                $newGrid[$neighbor_i][$neighbor_j] = 1;
                            }
                        }
                    }
                }
            }
        }

        return $newGrid;
    }
}
