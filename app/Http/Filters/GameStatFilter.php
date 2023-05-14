<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;
use phpDocumentor\Reflection\Types\Array_;

class GameStatFilter extends AbstractFilter
{
    public const USER_ID = 'user_id';
    public const SEASON_ID = 'season_id';
    public const MAP_ID = 'map_id';
    public const RESULT_ID = 'result_id';
    public const MY_RACE_ID = 'my_race_id';
    public const ENEMY_RANDOM_RACE_ID = 'enemy_random_race_id';
    public const ENEMY_RACE_ID = 'enemy_race_id';

    public const GAME_NUMBER = 'game_number';
    public const ENEMY_NICKNAME = 'enemy_nickname';
    public const ENEMY_LOGIN = 'enemy_login';
    public const ENEMY_MMR_BETWEEN = 'enemy_mmr_between';
    public const IS_SMURF = 'is_smurf';


    protected function getCallbacks(): array
    {
        return [
            self::USER_ID => [$this, 'userId'],
            self::SEASON_ID => [$this, 'seasonId'],
            self::MAP_ID => [$this, 'mapId'],
            self::RESULT_ID => [$this, 'resultId'],

            self::GAME_NUMBER => [$this, 'gameNumber'],
            self::ENEMY_NICKNAME => [$this, 'enemyNickname'],
            self::ENEMY_LOGIN => [$this, 'enemyLogin'],
            self::MY_RACE_ID => [$this, 'myRaceId'],
            self::ENEMY_RANDOM_RACE_ID => [$this, 'enemyRandomRaceId'],
            self::ENEMY_RACE_ID => [$this, 'enemyRaceId'],
            self::ENEMY_MMR_BETWEEN => [$this, 'enemyMmrBetween'],
            self::IS_SMURF => [$this, 'isSmurf'],
        ];
    }

    public function userId(Builder $builder, $value)
    {
        $builder->where('user_id', $value);
    }

    public function gameNumber(Builder $builder, $value)
    {
        $builder->where('game_number', $value);
    }

    public function enemyRandomRaceId(Builder $builder, $value)
    {
        $builder->whereIn('enemy_random_race', $value);
    }

    public function enemyLogin(Builder $builder, $value)
    {
        $builder->where('enemy_login', 'like', "%{$value}%");
    }

    public function mapId(Builder $builder, $value)
    {
        $builder->whereIn('mapId', $value);
    }

    public function seasonId(Builder $builder, $value)
    {
        $builder->where('season_id', $value);
    }

    public function enemyNickname(Builder $builder, $value)
    {
        $builder->where('enemy_nickname', 'like', "%{$value}%");
    }

    public function myRaceId(Builder $builder, $value)
    {
        $builder->whereIn('my_race', $value);
    }

    public function enemyRaceId(Builder $builder, $value)
    {
        $builder->whereIn('enemy_race', $value);
    }

    public function enemyMmrBetween(Builder $builder, $value)
    {
        $builder->whereBetween('enemy_max_mmr', $value);
    }

    public function resultId(Builder $builder, $value)
    {
        $builder->whereIn('result_id', !is_array($value) ? [$value] : $value);
    }

    public function isSmurf(Builder $builder, $value)
    {
        $builder->where('is_smurf', $value);
    }
}
