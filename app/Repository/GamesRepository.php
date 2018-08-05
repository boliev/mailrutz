<?php
namespace App\Repository;

use App\Game;

class GamesRepository
{
    public function all()
    {
        return Game::all();
    }
}