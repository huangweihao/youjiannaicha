<?php
/**
 * 岗位类
 * @package Controller/Station
 */
namespace app\controller;
use app\model\Volunteer as VolunteerModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet as SpreedSheet;
class Station extends Base {
    protected $volunteerIns = null;
    protected $sword = null;
    protected $spreedSheetIns = null;
    public function __construct(){
        parent::__construct();
        $this->volunteerIns = (new VolunteerModel());
        $this->spreedSheetIns = new SpreedSheet();
    }

    public function list(){
        $pageHtml = '';
        $condition = [];
        $order = ['id'=>'desc'];
        $result = $this->volunteerIns->getListPage('station',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
            foreach ($result['data'] as $key => $item) {
                $result['data'][$key]['experience'] = $this->doEasyRedis('get', ['key' => 'times_'.$item['id']])??0;
            }
            array_multisort(array_column($result['data'], 'experience'), SORT_DESC, $result['data']);
        }
        $assignData = [
            'searchWord'=>$this->sword??'',
            'data'=>$result,
            'pageHtml'=>$pageHtml,
            'searchUrl'=>$this->urlGenerate('search'),
            'insertUrl'=>$this->urlGenerate('insert'),
            'excelUrl'=> $this->urlGenerate('excel'),
            'workBtn'=>getWorkBtn($this->initModule['power'],$this->urlGenerate('doUpdate'),$this->urlGenerate('doDelete'),false),
        ];
        return $this->doRender('list',$assignData);
    }

    /**
     * 导出
     * @return mixed
     */
    public function excel(){
        $worksheet = $this->spreedSheetIns->getActiveSheet();
        $file_name = "统计数据".'.xlsx';
        $k = 1;
        $worksheet->setCellValue('A'.$k, '用户名');
        $worksheet->setCellValue('B'.$k, '手机号');
        $worksheet->setCellValue('C'.$k, '身份证号');
        $worksheet->setCellValue('D'.$k, '服务时长');
        try {
            $condition = [];
            $order = ['id'=>'desc'];
            $result = $this->volunteerIns->getList('station',$condition,$order);
            if(!empty($result)){
                foreach ($result as $key => $item) {
                    $result[$key]['experience'] = $this->doEasyRedis('get', ['key' => 'times_'.$item['id']])??0;
                }
                array_multisort(array_column($result, 'experience'), SORT_DESC, $result);
            }
            if ($result) {
                $k=2;
                foreach ($result as $key => $value) {
                    $worksheet->setCellValue('A'.$k, $value['username']);
                    $worksheet->setCellValue('B'.$k, $value['phone']);
                    $worksheet->setCellValue('C'.$k, $value['identity']);
                    $worksheet->setCellValue('D'.$k, $value['experience']);
                    $k++;
                }
                $this->doExcel($file_name, $this->spreedSheetIns);
            }
        }catch (\Exception $e) {
            return $this->doResponse(408, $e->getMessage());
        }
    }

    /**
     * @param $file_name
     * @param $spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function doExcel($file_name, $spreadsheet)
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file_name.'"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
}