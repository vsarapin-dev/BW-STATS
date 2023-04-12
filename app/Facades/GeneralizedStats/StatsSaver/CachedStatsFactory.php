<?php


namespace App\Facades\GeneralizedStats\StatsSaver;

class CachedStatsFactory
{
    private $cache = [];
    private int $seasonId;
    private int $userId;

    /**
     * CachedStatsFactory constructor.
     * @param int $seasonId
     * @param int $userId
     */
    public function __construct(int $seasonId, int $userId)
    {
        $this->seasonId = $seasonId;
        $this->userId = $userId;
    }

    public function createObject($className) {
        if (!isset($this->cache[$className])) {
            $this->cache[$className] = new $className();
        }
        $this->cache[$className]->retrieveData($this->seasonId, $this->userId);
        return $this->cache[$className];
    }
}
