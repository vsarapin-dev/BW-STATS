<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

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
    public const ENEMY_CURRENT_MMR = 'enemy_current_mmr';
    public const ENEMY_MAX_MMR = 'enemy_max_mmr';
    public const IS_SMURF = 'is_smurf';
    public const IS_LEAVER = 'is_leaver';
    public const IS_NOT_CALIBRATED = 'is_not_calibrated';
    public const IS_DROPPED = 'is_dropped';


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
            self::ENEMY_CURRENT_MMR => [$this, 'enemyCurrentMmr'],
            self::ENEMY_MAX_MMR => [$this, 'enemyMaxMmr'],
            self::IS_SMURF => [$this, 'isSmurf'],
            self::IS_LEAVER => [$this, 'isLeaver'],
            self::IS_NOT_CALIBRATED => [$this, 'isNotCalibrated'],
            self::IS_DROPPED => [$this, 'isDropped'],
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

    public function enemyCurrentMmr(Builder $builder, $value)
    {
        $builder->where('enemy_current_mmr', $value);
    }

    public function enemyMaxMmr(Builder $builder, $value)
    {
        $builder->where('enemy_max_mmr', $value);
    }

    public function resultId(Builder $builder, $value)
    {
        $builder->whereIn('result_id', $value);
    }

    public function isSmurf(Builder $builder, $value)
    {
        $builder->where('is_smurf', $value);
    }

    public function isLeaver(Builder $builder, $value)
    {
        $builder->where('is_leaver', $value);
    }

    public function isNotCalibrated(Builder $builder, $value)
    {
        $builder->where('is_not_calibrated', $value);
    }

    public function isDropped(Builder $builder, $value)
    {
        $builder->where('is_dropped', $value);
    }
}