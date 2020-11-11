<?php
/**
 * 重构缓存
 * Class RebuildCache
 * @package app\controller
 */
namespace app\controller;

use app\model\Shop as ShopModel;
use app\model\UserDetail as UserDetailModel;
use app\model\Lucky as LuckyModel;

class RebuildCache{
    /**
     * 重构用户
     * @param string $userUnionId
     * @return mixed
     */
    public function rebuildUser($userUnionId){
        $userUnionId = explode('_',$userUnionId);
        if(count($userUnionId) == 2){
            return $this->getUserFromDb($userUnionId);
        }else{
            return [];
        }
    }
    /**
     * 从数据库获取
     * @param array $userUnionId
     * @return mixed
     */
    private function getUserFromDb($userUnionId){
        $condition = ['user_id' => ['rule'=>'equal','val'=>$userUnionId[1]]];
        $result = (new UserDetailModel($userUnionId[0]))->getDetail('cache',$condition);
        $cacheData = [];
        if(!empty($result)){
            $cacheData = [
                'n' => $result['nickname'],
                'p' => $result['photo']
            ];
        }
        return $cacheData;
    }
    /**
     * 重构商家
     * @param $cityId
     * @param $shopId
     * @return mixed
     */
    public function rebuildShop($cityId,$shopId){
        return $this->getShopFromDb($cityId,$shopId);
    }
    /**
     * 从数据库获取
     * @param int $cityId
     * @param int $shopId
     * @return mixed
     */
    private function getShopFromDb($cityId,$shopId){
        $condition = ['id' => ['rule'=>'equal','val'=>$shopId]];
        $result = (new ShopModel($cityId))->getDetail('info',$condition);
        $cacheData = [];
        if(!empty($result)){
            $cacheData = [
                'n' => $result['name'],
                'lo' => $result['longitude'],
                'la' => $result['latitude'],
                'a' => $result['address'],
                'p' => $result['phone'],
                'ft' => $result['focus_type'],
                'fn' => $result['focus_name'],
                'fq' => $result['focus_qrcode']
            ];
        }
        return $cacheData;
    }
    /**
     * 重构抽奖
     * @param $cityId
     * @param $shopId
     * @param $luckyId
     * @return mixed
     */
    public function rebuildLucky($cityId,$shopId,$luckyId){
        return $this->getLuckyFromDb($cityId,$shopId,$luckyId);
    }
    /**
     * 从数据库获取
     * @param int $cityId
     * @param int $shopId
     * @param int $luckyId
     * @return mixed
     */
    private function getLuckyFromDb($cityId,$shopId,$luckyId){
        $condition = ['id' => ['rule'=>'equal','val'=>$luckyId]];
        $luckyDbIns = new LuckyModel($cityId,$shopId);
        $result = $luckyDbIns->getDetail('cache',$condition,null,['cityId'=>$cityId,'shopId'=>$shopId]);
        $cacheData = [];
        $currentTime = time();
        if(!empty($result)){
            if($result['status'] == 1){
                if($result['end_time'] > $currentTime){
                    if(!empty($result['stuff'])){
                        $result['stuff'] = @json_decode($result['stuff'],true);
                    }
                    if(!empty($result['stuff'])){
                        $stuff = [];
                        $cacheAmount = [];
                        foreach($result['stuff'] as $key=>$value){
                            $stuff[$key]['p'] = $value['p'];
                            $stuff[$key]['n'] = $value['n'];
                            $stuff[$key]['c'] = $value['c'];
                            $stuff[$key]['l'] = $value['l'];
                            $stuff[$key]['u'] = 0;
                            $cacheAmount['g'.$key] = $value['l'];
                        }
                        $cacheData = [
                            'n' => $result['name'],
                            'p' => $result['photo'],
                            'a' => $result['allow_support'],
                            'k' => $result['join_type'],
                            'r' => $result['recommend_id'],
                            'j' => $result['base_peoples'],
                            'c' => $result['check_type'],
                            'y' => $result['open_type'],
                            'b' => $result['begin_time'],
                            'o' => $result['open_time'],
                            'e' => $result['end_time'],
                            't' => $result['expire_type'],
                            'x' => $result['expire_time'],
                            'f' => json_encode($stuff,JSON_UNESCAPED_UNICODE)
                        ];
                        $cacheData = $cacheData+$cacheAmount;
                    }
                }
            }
        }
        return $cacheData;
    }
}
