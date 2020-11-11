<?php
/**
 * 模型相关MAP类
 * Class ModelMap
 * @package app\model\common
 */
namespace app\model\common;

class ModelMap{
    /**
     * 状态方式map信息
     * @param string $type
     * @param integer $type_id
     * @return array|string
     */
    public function getStatusMap($type='all',$type_id=0){
        $map = [
            0 => '待审核',
            1 => '正常',
            2 => '未通过审核',
            3 => '黑名单'
        ];
        switch ($type){
            case 'all':
                return $map;
                break;
            default:
                return empty($map[$type_id]) ? '' : $map[$type_id];
                break;
        }
    }

    /**
     * 获取志愿者健康状态值
     * @param string $type 健康type
     * @param int $typeId typeid
     * @return array|mixed|string
     */
    public function getHealthMap($type='all', $typeId=0){
        $map = [
            1=>'健康',
            2=>'一般(无慢性、传染性疾病)',
            3=>'体弱(无慢性、传染性疾病)',
            4=>'有慢性、传染性疾病'
        ];
        switch ($type) {
            case 'all':
                return $map;
                break;
            default:
                return empty($map[$typeId]) ? '' : $map[$typeId];
                break;
        }
    }

    /**
     * 获取志愿者健康状态值
     * @param string $type 健康type
     * @param int $typeId typeid
* @return array|mixed|string
*/
    public function getVolunteerStatusMap($type='all', $typeId=0){
        $map = [
            0=>'待审',
            1=>'通过审核',
            2=>'未通过审核'
        ];
        switch ($type) {
            case 'all':
                return $map;
                break;
            default:
                return empty($map[$typeId]) ? '' : $map[$typeId];
                break;
        }
    }

    /**
     * 获取志愿者健康状态值
     * @param string $type 健康type
     * @param int $typeId typeid
     * @return array|mixed|string
     */
    public function getVolunteerServiceMap($type='all', $typeId=0){
        $map = [
            0=>'无服务经验',
            1=>'0-3个月',
            2=>'3个月-一年',
            3=>'一年以上'
        ];
        switch ($type) {
            case 'all':
                return $map;
                break;
            default:
                return empty($map[$typeId]) ? '' : $map[$typeId];
                break;
        }
    }

    /**
     * 获取组织状态map
     * @param string $type
     * @param int $typeId
     * @return array|mixed|string
     */
    public function getOrgStatusMap($type='all', $typeId=0)
    {
        $map = [
            0=>'待审核',
            1=>'通过审核',
            2=>'未通过审核'
        ];
        switch ($type) {
            case 'all':
                return $map;
                break;
            default:
                return empty($map[$typeId]) ? '' : $map[$typeId];
                break;
        }
    }

    /**
     * 获取活动状态map
     * @param string $type
     * @param int $typeId
     * @return array|mixed|string
     */
    public function getActivityStatusMap($type='all', $typeId=0)
    {
        $map = [
            0=>'待审核',
            1=>'通过审核',
            2=>'未通过审核',
            3=>'已发布',
        ];
        switch ($type) {
            case 'all':
                return $map;
                break;
            default:
                return empty($map[$typeId]) ? '' : $map[$typeId];
                break;
        }
    }
    /**
     * 获取空闲状态map
     * @param string $type
     * @param int $typeId
     * @return array|mixed|string
     */
    public function getBeasyStatusMap($type='all', $typeId=0)
    {
        $map = [
            0 => '空闲状态',
            1 => '空闲',
            2 => '在岗'
        ];
        switch ($type) {
            case 'all':
                return $map;
                break;
            default:
                return empty($map[$typeId]) ? '' : $map[$typeId];
                break;
        }
    }
    /**
     * 获取广告状态map
     * @param string $type
     * @param int $typeId
     * @return array|mixed|string
     */
    public function getAdvertStatusMap($type='all', $typeId=0)
    {
        $map = [
            0 => '待审核',
            1 => '通过审核',
            2 => '未通过审核',
            3 => '已发布',
            4 => '已下架',
        ];
        switch ($type) {
            case 'all':
                return $map;
                break;
            default:
                return empty($map[$typeId]) ? '' : $map[$typeId];
                break;
        }
    }

    /**
     * 获取广告状态map
     * @param string $type
     * @param int $typeId
     * @return array|mixed|string
     */
    public function getTypeStatusMap($type='all', $typeId=0)
    {
        $map = [
            0 => '待审核',
            1 => '通过审核',
            2 => '未通过审核',
        ];
        switch ($type) {
            case 'all':
                return $map;
                break;
            default:
                return empty($map[$typeId]) ? '' : $map[$typeId];
                break;
        }
    }
}