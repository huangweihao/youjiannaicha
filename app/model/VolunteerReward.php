<?php
/**
 * 志愿者奖励类
 */
namespace app\model;
class VolunteerReward extends Base {
    protected $table = '';
    function __construct()
    {
        $this->table = $this->tableInit('volunteer_reward');
    }

    /**
     * 添加
     * @param $data
     * @return int
     */
    public function inData($data)
    {
        return $this->insertDbData($this->table, $data);
    }
}