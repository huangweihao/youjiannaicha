<?php
/**
 * 分页类
 * Class Auth
 * @package app\admin\common
 */
namespace app\common;

class Page{
    private $curPage;//当前被选中的页
    private $pagesShow;//每次显示的页数
    private $pageNum;//总页数
    private $pageArr = array();//用来构造分页的数组
    /**
     * 获取当前页的前面页码
     * @remark 当前分页应该显示的页码数量
     * @return array 页码数组
     */
    private function initArray(){
        for($i = 0;$i < $this->pagesShow;$i++){
            $this->pageArr[$i] = $i;
        }
        return $this->pageArr;
    }
    /**
     * 获取当前分页应该显示的页码
     * @remark 当前分页应该显示的页码数量
     * @return array 页码数组
     */
    private function getPageList(){
        if($this->pageNum < $this->pagesShow){
            $curArr = array();
            for($i = 0;$i < $this->pageNum;$i++){
                $curArr[$i] = $i+1;
            }
        }else{
            $curArr = $this->initArray();
            if($this->curPage <= 3){
                for($i=0;$i<count($curArr);$i++){
                    $curArr[$i] = $i+1;
                }
            }elseif($this->curPage <= $this->pageNum && $this->curPage > $this->pageNum - $this->pagesShow + 1 ){
                for($i=0;$i<count($curArr);$i++){
                    $curArr[$i] = ($this->pageNum)-($this->pagesShow) + 1 + $i;
                }
            }else{
                for($i=0;$i < count($curArr);$i++){
                    $curArr[$i] = $this->curPage-2 + $i;
                }
            }
        }
        return $curArr;
    }
    /**
     * 页码选择器
     * @param int pageSize 每页显示的条数
     * @param array pageSizeInit 所有页码数
     * @return string
     */
    private function pageSelect($pageSize,$pageSizeInit,$pageLink){
        $selectStr = '<select id="selectPageSize" onchange="TF_List._setPagesize(\''.$pageLink.'\')">';
        foreach($pageSizeInit as $key=>$value){
            $selectStr .= '<option value="'.$value.'"';
            if($value == $pageSize){
                $selectStr .= ' selected';
            }
            $selectStr .= '>每页'.$value.'条</option>';
        }
        $selectStr .= '</select>';
        return $selectStr;
    }
    /**
     * 获取分页
     * @param int pageSize 每页显示的条数
     * @param int total 总条数
     * @param int curPage 当前页数
     * @param int pagesShow 每页显示的页数
     * @param string pageLink 分页链接
     * @param string pageType 分页样式 all/simple
     * @return string 分页后的html代码
     */
    public function pageShow($pageSize,$total,$curPage,$pagesShow,$pageLink,$pageType='all',$pageSizeInit,$pageSizeUrl){
        $this->curPage = !empty($curPage) ? intval($curPage) : 1;
        $total = intval($total);
        $this->pagesShow = intval($pagesShow);
        $this->pageNum = ceil($total/$pageSize);
        $this->pageSize = intval($pageSize);
        if($this->curPage > $this->pageNum)$this->curPage = $this->pageNum;
        $pageStr = '';
        switch($pageType){
            case 'simple':
                $pageStr = '<div class="am-u-lg-12 am-cf pagination">';
                $pageStr .= '<div class="am-fr  am-margin-right-sm"><ul class="pagination">';
                if($this->curPage > 1){
                    $pageStr .= '<li><a href="javascript:void(0)" onclick="TB_Common._urlGo(\''.$pageLink.'1\')">首页</a></li><li><a href="javascript:void(0)" onclick="TB_Common._urlGo(\''.$pageLink.($this->curPage-1).'\')">上一页</a></li>';
                }else{
                    $pageStr .= '<li class="disabled"><span>首页</span></li><li class="disabled"><span>上一页</span></li>';
                }
                $pageArr = $this->getPageList();

                for($i=0;$i < count($pageArr);$i++){
                    $page = $pageArr[$i];
                    if($page == $this->curPage){
                        $pageStr .= '<li class="active"><span>'.$page.'</span></li>';
                    }else{
                        $pageStr .= '<li><a href="javascript:void(0)" onclick="TB_Common._urlGo(\''.$pageLink.$page.'\')">'.$page.'</a></li>';
                    }
                }
                if($this->curPage < $this->pageNum){
                    $pageStr .= '<li><a href="javascript:void(0)" onclick="TB_Common._urlGo(\''.$pageLink.($this->curPage+1).'\')">下一页</a></li><li><a href="javascript:void(0)" onclick="TB_Common._urlGo(\''.$pageLink.$this->pageNum.'\')">末页</a></li>';
                }else{
                    $pageStr .= '<li class="disabled"><span>下一页</span></li><li class="disabled"><span class="page-end">末页</span></li>';
                }
                $pageStr .= '</ul></div></div>';
                break;
            case 'all':
                $pageStr = '<div class="am-u-lg-12 am-cf pagination">';
                $pageStr .= '<div class="am-fl">';
                $pageStr .= '<div class="am-form-group pagination-select">';
                $pageStr .= $this->pageSelect($pageSize,$pageSizeInit,$pageSizeUrl);
                $pageStr .= '<span class="am-form-caret"> 共有'.$total.'条数据　当前 '.$curPage.'/'.$this->pageNum.' 页</span>';
                $pageStr .= '</div>';
                $pageStr .= '</div>';
                $pageStr .= '<div class="am-fr pagination-total"><span>';
                $pageStr .= '到第 <input class="pagination-input" type="text" id="inputItem" name="inputItem" value="'.$this->curPage.'" maxlength="3"> 页 ';
                $pageStr .= '<button class="am-btn am-btn-default am-btn-xs" onclick=\'window.location.href="'.$pageLink.'"+$("#inputItem").val();window.event.returnValue =false;\'>确定</button>';
                $pageStr .= '</span></div>';
                $pageStr .= '<div class="am-fr  am-margin-right-sm"><ul class="pagination">';
                if($this->curPage > 1){
                    $pageStr .= '<li><a href="javascript:void(0)" onclick="TB_Common._urlGo(\''.$pageLink.'1\')" class="page-prev">首页</a></li><li><a href="javascript:void(0)" onclick="TB_Common._urlGo(\''.$pageLink.($this->curPage-1).'\')">上一页</a></li>';
                }else{
                    $pageStr .= '<li class="disabled"><span>首页</span></li><li class="disabled"><span>上一页</span></li>';
                }
                $pageArr = $this->getPageList();

                for($i=0;$i < count($pageArr);$i++){
                    $page = $pageArr[$i];
                    if($page == $this->curPage){
                        $pageStr .= '<li class="active"><span>'.$page.'</span></li>';
                    }else{
                        $pageStr .= '<li><a href="javascript:void(0)" onclick="TB_Common._urlGo(\''.$pageLink.$page.'\')">'.$page.'</a></li>';
                    }
                }
                if($this->curPage < $this->pageNum){
                    $pageStr .= '<li><a href="javascript:void(0)" onclick="TB_Common._urlGo(\''.$pageLink.($this->curPage+1).'\')">下一页</a></li><li><a href="javascript:void(0)" onclick="TB_Common._urlGo(\''.$pageLink.$this->pageNum.'\')">末页</a></li>';
                }else{
                    $pageStr .= '<li class="disabled"><span>下一页</span></li><li class="disabled"><span>末页</span></li>';
                }
                $pageStr .= '</ul></div>';
                $pageStr .= '</div>';
                break;
        }
        return $pageStr;
    }

}