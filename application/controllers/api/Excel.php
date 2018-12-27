<?php
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//   require 'vendor/autoload.php';
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;
use SMSGatewayMe\Client\Api\CallbackApi;
use SMSGatewayMe\Client\Model\CreateCallbackRequest;
require APPPATH.'libraries/REST_Controller.php';
class Excel extends REST_Controller {
    public function __construct(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        parent::__construct();
        date_default_timezone_set('Asia/Karachi');
            }
            public function shops_month_get($month,$id)
            {
                $data = $this->Shops_model->shops_reports_excel_month($month,$id)->result_array();
                if(!empty($data)){
                $shop_name=$data[0]['shop_name'];
                // print_r($data);
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Shops');
                $i=3;
                $a=1;
                $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
                $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
                $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
                $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            //    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            //    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
            // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
            $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
            $monthNum  = $month;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');
            $heading = "Report For ".$shop_name. " ". $monthName."/".date("y");;
                $sheet->setCellValue('A1', $heading);
                $sheet->setCellValue('A2', 'S.NO');
                $sheet->setCellValue('B2', 'Entry_Time');
                $sheet->setCellValue('C2', 'Exit_Time');
                $sheet->setCellValue('D2', 'Purpose');
                $sheet->setCellValue('E2', 'Date');
        
                
                // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];
                $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);
        
                $styleArray = [
        
        
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];
        foreach($data as $key => $value) {
          
            
            $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);
        
        
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        
            // $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
                       $sheet->setCellValue('A'.$i, $a);
                    $sheet->setCellValue('B'.$i, $value['entry_time']);
                    $sheet->setCellValue('C'.$i, $value['exit_time']);
                    $sheet->setCellValue('D'.$i, $value['purpose']);
                    $sheet->setCellValue('E'.$i,substr($value['date'],0,10));
        
        
        $a++;
                    $i++;
                  }
                  $name=$shop_name." ".$month ." / ".date('y').".xlsx";
        
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='.$name);
                header('Cache-Control: max-age=0');
                header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Cache-Control: cache, must-revalidate');
                header('Pragma: public');
                $writer->save('php://output');
          
            }else{
             $status=false;
             $this->response($status); 
            }
        }

            public function shops_day_get($date,$id)
            {
                $data = $this->Shops_model->shops_reports_excel_date($date,$id)->result_array();
                if(!empty($data)){
                $shop_name=$data[0]['shop_name'];
                // print_r($data);
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Shop');
                $i=3;
                $a=1;
                $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
                $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
                $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
                $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            //    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            //    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
            // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
            $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
            
            $heading = "Report For ".$shop_name." ".$date;
                $sheet->setCellValue('A1', $heading);
                $sheet->setCellValue('A2', 'S.NO');
                $sheet->setCellValue('B2', 'Entry_Time');
                $sheet->setCellValue('C2', 'Exit_Time');
                $sheet->setCellValue('D2', 'Purpose');
                $sheet->setCellValue('E2', 'Date');
                
                // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];
                $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);
            
            
                $styleArray = [
            
            
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];
            foreach($data as $key => $value) {
            
            
            $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);
            
            
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            
            // $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
                       $sheet->setCellValue('A'.$i, $a);
                    $sheet->setCellValue('B'.$i, $value['entry_time']);
                    $sheet->setCellValue('C'.$i, $value['exit_time']);
                    $sheet->setCellValue('D'.$i, $value['purpose']);
                    $sheet->setCellValue('E'.$i,substr($value['date'],0,10));
            
            
            $a++;
                    $i++;
                  }
                  $name=$shop_name."".date('d').".xlsx";
            
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='.$name);
                header('Cache-Control: max-age=0');
                header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Cache-Control: cache, must-revalidate');
                header('Pragma: public');
                $writer->save('php://output');
                
            }else{
            $status=true;
            $this->response($status);
            }
            }
            public function shops_year_get($year,$id)
            {  
                $data = $this->Shops_model->shops_reports_excel_year($year,$id)->result_array();
                if(!empty($data)){
                   
                   
                $shop_name=$data[0]['shop_name'];
                // print_r($data);
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Staff');
                $i=3;
                $a=1;
                $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
                $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
                $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
                $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            //    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            //    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
            // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
            $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
            $heading = "Report For ".$shop_name." ".date("Y");;
                $sheet->setCellValue('A1', $heading);
                $sheet->setCellValue('A2', 'S.NO');
                $sheet->setCellValue('B2', 'Entry_Time');
                $sheet->setCellValue('C2', 'Exit_Time');
                $sheet->setCellValue('D2', 'Purpose');
                $sheet->setCellValue('E2', 'Date');
                
                // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];
                $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);
            
            
                $styleArray = [
            
            
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];
            foreach($data as $key => $value) {
            
            
            $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);
            
            
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            
            // $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
                       $sheet->setCellValue('A'.$i, $a);
                    $sheet->setCellValue('B'.$i, $value['entry_time']);
                    $sheet->setCellValue('C'.$i, $value['exit_time']);
                    $sheet->setCellValue('D'.$i, $value['purpose']);
                    $sheet->setCellValue('E'.$i,substr($value['date'],0,10));
            
            
            $a++;
                    $i++;
                  }
                  $name=$shop_name."".date('y').".xlsx";
            
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='.$name);
                header('Cache-Control: max-age=0');
                header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Cache-Control: cache, must-revalidate');
                header('Pragma: public');
                $writer->save('php://output');
                
            }else{
                $status=false;
                $this->response($status); 
               }
            }

            public function shops_custom_get($date1,$date2,$id)
            {
                $data = $this->Shops_model->shops_reports_excel_custom($date1,$date2,$id)->result_array();
                 
                if(!empty($data)){
                $shop_name=$data[0]['shop_name'];
                // print_r($data);
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Shops');
                $i=3;
                $a=1;
                $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
                $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
                $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
                $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            //    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            //    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
            // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
            $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
            $heading = "Report For ".$shop_name." From ".$date2;
                $sheet->setCellValue('A1', $heading);
                $sheet->setCellValue('A2', 'S.NO');
                $sheet->setCellValue('B2', 'Entry_Time');
                $sheet->setCellValue('C2', 'Exit_Time');
                $sheet->setCellValue('D2', 'Purpose');
                $sheet->setCellValue('E2', 'Date');
                
                // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];
                $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);
                $styleArray = [
            
            
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];
            foreach($data as $key => $value) {
            
            
            $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);
            
            
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            
            // $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
                       $sheet->setCellValue('A'.$i, $a);
                    $sheet->setCellValue('B'.$i, $value['entry_time']);
                    $sheet->setCellValue('C'.$i, $value['exit_time']);
                    $sheet->setCellValue('D'.$i, $value['purpose']);
                    $sheet->setCellValue('E'.$i,substr($value['date'],0,10));
            
            
            $a++;
                    $i++;
                  }
                  $name=$shop_name." ".$date1.".xlsx";
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='.$name);
                header('Cache-Control: max-age=0');
                header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Cache-Control: cache, must-revalidate');
                header('Pragma: public');
                $writer->save('php://output'); 
            }else{
            $status=false;
            $this->response($status);
            }
            }
            public function staff_custom_get($date1,$date2,$id)
{
    $data = $this->Staff_model->staff_reports_excel_custom($date1,$date2,$id)->result_array();
     
    if(!empty($data)){
    $staff_name=$data[0]['staff_name'];
    // print_r($data);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Staff');
    $i=3;
    $a=1;
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getPageSetup()
->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
//    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
// $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
$spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
$heading = "Report For ".$staff_name." From ".$date2;
    $sheet->setCellValue('A1', $heading);
    $sheet->setCellValue('A2', 'S.NO');
    $sheet->setCellValue('B2', 'Entry_Time');
    $sheet->setCellValue('C2', 'Exit_Time');
    $sheet->setCellValue('D2', 'Purpose');
    $sheet->setCellValue('E2', 'Date');
    
    // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
    $styleArray = [
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
    $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);
    $styleArray = [


        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
foreach($data as $key => $value) {


$spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);


$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

// $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
           $sheet->setCellValue('A'.$i, $a);
        $sheet->setCellValue('B'.$i, $value['entry_time']);
        $sheet->setCellValue('C'.$i, $value['exit_time']);
        $sheet->setCellValue('D'.$i, $value['purpose']);
        $sheet->setCellValue('E'.$i,substr($value['date'],0,10));


$a++;
        $i++;
      }
      $name=$shop_name." ".$date1.".xlsx";
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename='.$name);
    header('Cache-Control: max-age=0');
    header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $writer->save('php://output'); 
}else{
$status=false;
$this->response($status);
}
}
            
            public function staff_year_get($year,$id)
{  
    $data = $this->Staff_model->staff_reports_excel_year($year,$id)->result_array();
    if(!empty($data)){
       
       
    $staff_name=$data[0]['staff_name'];
    // print_r($data);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Staff');
    $i=3;
    $a=1;
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getPageSetup()
->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
//    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
// $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
$spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
$heading = "Report For ".$staff_name." ".date("Y");;
    $sheet->setCellValue('A1', $heading);
    $sheet->setCellValue('A2', 'S.NO');
    $sheet->setCellValue('B2', 'Entry_Time');
    $sheet->setCellValue('C2', 'Exit_Time');
    $sheet->setCellValue('D2', 'Purpose');
    $sheet->setCellValue('E2', 'Date');
    
    // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
    $styleArray = [
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
    $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);


    $styleArray = [


        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
foreach($data as $key => $value) {


$spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);


$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

// $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
           $sheet->setCellValue('A'.$i, $a);
        $sheet->setCellValue('B'.$i, $value['entry_time']);
        $sheet->setCellValue('C'.$i, $value['exit_time']);
        $sheet->setCellValue('D'.$i, $value['purpose']);
        $sheet->setCellValue('E'.$i,substr($value['date'],0,10));


$a++;
        $i++;
      }
      $name=$staff_name."".date('y').".xlsx";

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename='.$name);
    header('Cache-Control: max-age=0');
    header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $writer->save('php://output');
    
}else{
    $status=false;
    $this->response($status); 
   }
}
            public function staff_day_get($date,$id)
{
    $data = $this->Staff_model->staff_reports_excel_date($date,$id)->result_array();
    if(!empty($data)){
    $staff_name=$data[0]['staff_name'];
    // print_r($data);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Staff');
    $i=3;
    $a=1;
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getPageSetup()
->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
//    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
// $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
$spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);

$heading = "Report For ".$staff_name." ".$date;
    $sheet->setCellValue('A1', $heading);
    $sheet->setCellValue('A2', 'S.NO');
    $sheet->setCellValue('B2', 'Entry_Time');
    $sheet->setCellValue('C2', 'Exit_Time');
    $sheet->setCellValue('D2', 'Purpose');
    $sheet->setCellValue('E2', 'Date');
    
    // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
    $styleArray = [
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
    $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);


    $styleArray = [


        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
foreach($data as $key => $value) {


$spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);


$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

// $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
           $sheet->setCellValue('A'.$i, $a);
        $sheet->setCellValue('B'.$i, $value['entry_time']);
        $sheet->setCellValue('C'.$i, $value['exit_time']);
        $sheet->setCellValue('D'.$i, $value['purpose']);
        $sheet->setCellValue('E'.$i,substr($value['date'],0,10));


$a++;
        $i++;
      }
      $name=$staff_name."".date('d').".xlsx";

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename='.$name);
    header('Cache-Control: max-age=0');
    header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $writer->save('php://output');
    
}else{
$status=true;
$this->response($status);
}
}

            public function staff_month_get($month,$id)
            {
                $data = $this->Staff_model->staff_reports_excel_month($month,$id)->result_array();
                if(!empty($data)){
                $staff_name=$data[0]['staff_name'];
                // print_r($data);
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Staff');
                $i=3;
                $a=1;
                $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
                $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
                $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
                $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            //    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            //    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
            // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
            // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
            $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
            $monthNum  = $month;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');
            $heading = "Report For ".$staff_name. " ". $monthName."/".date("y");;
                $sheet->setCellValue('A1', $heading);
                $sheet->setCellValue('A2', 'S.NO');
                $sheet->setCellValue('B2', 'Entry_Time');
                $sheet->setCellValue('C2', 'Exit_Time');
                $sheet->setCellValue('D2', 'Purpose');
                $sheet->setCellValue('E2', 'Date');
        
                
                // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];
                $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);
        
                $styleArray = [
        
        
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];
        foreach($data as $key => $value) {
          
            
            $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);
        
        
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        
            // $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
                       $sheet->setCellValue('A'.$i, $a);
                    $sheet->setCellValue('B'.$i, $value['entry_time']);
                    $sheet->setCellValue('C'.$i, $value['exit_time']);
                    $sheet->setCellValue('D'.$i, $value['purpose']);
                    $sheet->setCellValue('E'.$i,substr($value['date'],0,10));
        
        
        $a++;
                    $i++;
                  }
                  $name=$staff_name." ".$month ." / ".date('y').".xlsx";
        
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='.$name);
                header('Cache-Control: max-age=0');
                header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Cache-Control: cache, must-revalidate');
                header('Pragma: public');
                $writer->save('php://output');
          
            }else{
             $status=false;
             $this->response($status); 
            }
        }

            public function girls_month_get($month,$id)
	{
        $data = $this->Girls_model->girls_reports_excel_month($month,$id)->result_array();
        if(!empty($data)){
        $girl_name=$data[0]['name'];
        // print_r($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Girls');
        $i=3;
        $a=1;
        $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
        $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
        $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
        $spreadsheet->getActiveSheet()->getPageSetup()
    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    //    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    //    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
    // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
    $monthNum  = $month;
$dateObj   = DateTime::createFromFormat('!m', $monthNum);
$monthName = $dateObj->format('F');
    $heading = "Report For ".$girl_name. " ". $monthName."/".date("y");;
        $sheet->setCellValue('A1', $heading);
        $sheet->setCellValue('A2', 'S.NO');
        $sheet->setCellValue('B2', 'Entry_Time');
        $sheet->setCellValue('C2', 'Exit_Time');
        $sheet->setCellValue('D2', 'Purpose');
        $sheet->setCellValue('E2', 'Date');

        
        // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);

        $styleArray = [


            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
foreach($data as $key => $value) {
  
    
    $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);


    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

    // $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
               $sheet->setCellValue('A'.$i, $a);
            $sheet->setCellValue('B'.$i, $value['entry_time']);
            $sheet->setCellValue('C'.$i, $value['exit_time']);
            $sheet->setCellValue('D'.$i, $value['purpose']);
            $sheet->setCellValue('E'.$i,substr($value['date'],0,10));


$a++;
            $i++;
          }
          $name=$girl_name." ".$month ." / ".date('y').".xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename='.$name);
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
  
    }else{
     $status=false;
     $this->response($status); 
    }
}
  public function girls_sms_export_get($id)
	{
        $config = Configuration::getDefaultConfiguration();
        $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUzODgxMzA3OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjYyMjY2LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DyvybaWkAZ8RWdB0j8H8HsLWjK-CT4J867UZocqZlC0');
        $apiClient = new ApiClient($config);
        $messageClient = new MessageApi($apiClient);
        $results = $this->Girls_model->update_show($id);
        foreach ($results as $key => $value) {
            $countryCode = "92";
            $phoneNumber = $value['contact'];
            $newNumber = preg_replace('/^0?/', '+'.$countryCode, $phoneNumber);
          $newNumber;
        
         $messages = $messageClient->searchMessages(
                [
                    'filters' => [
                        [
                            ['field'=> 'phone_number',
                            'operator'=> '=',
                            'value'=> $newNumber
                        ],
                            [
                                'field' => '103150',
                                'operator' => '=',
                                'value' => '1'
                            ],
                            [
                                'field' => 'status',
                                'operator' => '=',
                                'value' => 'received'
                            ]
                        ],
                  
                    ],
                    'order_by' => [
                        [
            
            
                            'field'=> 'created_at',
                            'direction'=> 'desc'
            
            
                            // 'field' => 'status',
                            // 'direction' => 'ASC'
                        ],
                    ],
                   
                ]
            );
        }
    
        $msgs = $messages->getResults();
        $msgout = [];
        foreach ( $msgs as $msg ) {
            $msgout[] = ["msg" => $msg->getMessage(),"date" => $msg->getCreatedAt()->format('Y-m-d h:s:i')];     
        }
        if(!empty($msgout))
{$data = $msgout;
        // print_r($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Girls');
        $i=3;
        $a=1;
        $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
        $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
        $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(20);
        $spreadsheet->getActiveSheet()->getPageSetup()
    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    //    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    //    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
    // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
    $heading =  $results[0]['name']." SMS ".date("M")."/".date("y");;
        $sheet->setCellValue('A1',$heading);
        $sheet->setCellValue('A2', 'S.NO');
        $sheet->setCellValue('B2', 'Message');
        $sheet->setCellValue('C2', 'Date And Time');
        
        // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);


        $styleArray = [


            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
       
foreach($data as $key => $value) {
  
    
    $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);




    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

    // $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
               $sheet->setCellValue('A'.$i, $a);
            $sheet->setCellValue('B'.$i, $value['msg']);
            $sheet->setCellValue('C'.$i, $value['date']);

$a++;


            $i++;
          }
        $writer = new Xlsx($spreadsheet);
        $name = $results[0]['name']. " SMS.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename='.$name);
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        }else{
      $status=false;
      $this->response($status);
        }
    }
public function girls_custom_get($date1,$date2,$id)
{
    $data = $this->Girls_model->girls_reports_excel_custom($date1,$date2,$id)->result_array();
     
    if(!empty($data)){
    $girl_name=$data[0]['name'];
    // print_r($data);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Girls');
    $i=3;
    $a=1;
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getPageSetup()
->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
//    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
// $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
$spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
$heading = "Report For ".$girl_name." From ".$date2;
    $sheet->setCellValue('A1', $heading);
    $sheet->setCellValue('A2', 'S.NO');
    $sheet->setCellValue('B2', 'Entry_Time');
    $sheet->setCellValue('C2', 'Exit_Time');
    $sheet->setCellValue('D2', 'Purpose');
    $sheet->setCellValue('E2', 'Date');
    
    // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
    $styleArray = [
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
    $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);
    $styleArray = [


        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
foreach($data as $key => $value) {


$spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);


$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

// $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
           $sheet->setCellValue('A'.$i, $a);
        $sheet->setCellValue('B'.$i, $value['entry_time']);
        $sheet->setCellValue('C'.$i, $value['exit_time']);
        $sheet->setCellValue('D'.$i, $value['purpose']);
        $sheet->setCellValue('E'.$i,substr($value['date'],0,10));


$a++;
        $i++;
      }
      $name=$girl_name." ".$date1.".xlsx";
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename='.$name);
    header('Cache-Control: max-age=0');
    header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $writer->save('php://output'); 
}else{
$status=false;
$this->response($status);
}
}

public function girls_year_get($year,$id)
{
    
    
    $data = $this->Girls_model->girls_reports_excel_year($year,$id)->result_array();
    if(!empty($data)){
       
       
    $girl_name=$data[0]['name'];
    // print_r($data);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Girls');
    $i=3;
    $a=1;
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getPageSetup()
->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
//    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
// $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
$spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
$heading = "Report For ".$girl_name." ".date("Y");;
    $sheet->setCellValue('A1', $heading);
    $sheet->setCellValue('A2', 'S.NO');
    $sheet->setCellValue('B2', 'Entry_Time');
    $sheet->setCellValue('C2', 'Exit_Time');
    $sheet->setCellValue('D2', 'Purpose');
    $sheet->setCellValue('E2', 'Date');
    
    // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
    $styleArray = [
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
    $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);


    $styleArray = [


        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
foreach($data as $key => $value) {


$spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);


$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

// $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
           $sheet->setCellValue('A'.$i, $a);
        $sheet->setCellValue('B'.$i, $value['entry_time']);
        $sheet->setCellValue('C'.$i, $value['exit_time']);
        $sheet->setCellValue('D'.$i, $value['purpose']);
        $sheet->setCellValue('E'.$i,substr($value['date'],0,10));


$a++;
        $i++;
      }
      $name=$girl_name."".date('y').".xlsx";

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename='.$name);
    header('Cache-Control: max-age=0');
    header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $writer->save('php://output');
    
}else{
    $status=false;
    $this->response($status); 
   }



}



            public function girls_day_get($date,$id)
{
    $data = $this->Girls_model->girls_reports_excel_date($date,$id)->result_array();
    if(!empty($data)){
    $girl_name=$data[0]['name'];
    // print_r($data);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Girls');
    $i=3;
    $a=1;
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getPageSetup()
->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
//    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
// $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
// $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
$spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);

$heading = "Report For ".$girl_name." ".$date;
    $sheet->setCellValue('A1', $heading);
    $sheet->setCellValue('A2', 'S.NO');
    $sheet->setCellValue('B2', 'Entry_Time');
    $sheet->setCellValue('C2', 'Exit_Time');
    $sheet->setCellValue('D2', 'Purpose');
    $sheet->setCellValue('E2', 'Date');
    
    // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
    $styleArray = [
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
    $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);


    $styleArray = [


        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
    ];
foreach($data as $key => $value) {


$spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);


$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

// $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
           $sheet->setCellValue('A'.$i, $a);
        $sheet->setCellValue('B'.$i, $value['entry_time']);
        $sheet->setCellValue('C'.$i, $value['exit_time']);
        $sheet->setCellValue('D'.$i, $value['purpose']);
        $sheet->setCellValue('E'.$i,substr($value['date'],0,10));


$a++;
        $i++;
      }
      $name=$girl_name."".date('d').".xlsx";

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename='.$name);
    header('Cache-Control: max-age=0');
    header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $writer->save('php://output');
    
}else{
$status=true;
$this->response($status);
}
}
     public function girls_get(){
        $data = $this->Girls_model->fetch();
        // print_r($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Girls');
        $i=3;
        $a=1;
        $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
        $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
        $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(20);
        $spreadsheet->getActiveSheet()->getPageSetup()
    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    //    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    //    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
    // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
    $heading = "ALL GIRLS IN HOSTEL ".date("M")."/".date("y");;
        $sheet->setCellValue('A1',$heading);
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Name');
        $sheet->setCellValue('C2', 'Email');
        $sheet->setCellValue('D2', 'RollNo');
        $sheet->setCellValue('E2', 'Contact');

        
        // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);


        $styleArray = [


            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
foreach($data as $key => $value) {
  
    
    $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);



    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

    // $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
               $sheet->setCellValue('A'.$i, $a);
            $sheet->setCellValue('B'.$i, $value['name']);
            $sheet->setCellValue('C'.$i, $value['email']);
            $sheet->setCellValue('D'.$i, $value['rollno']);
            $sheet->setCellValue('E'.$i, $value['contact']);

$a++;


            $i++;
          }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Girls.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
// echo 'rafay';
    } 
    public function staff_get(){
        $data = $this->Staff_model->fetch();
        // print_r($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Staff');
        $i=3;
        $a=1;
        $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
        $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
        $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(20);
        $spreadsheet->getActiveSheet()->getPageSetup()
    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    //    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    //    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
    // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
    $heading = "ALL Staff IN HOSTEL ".date("M")."/".date("y");;
        $sheet->setCellValue('A1',$heading);
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Name');
        $sheet->setCellValue('C2', 'Contact');
        $sheet->setCellValue('D2', 'Designation');

        
        // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);


        $styleArray = [


            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
foreach($data as $key => $value) {
  
    
    $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);



    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

    // $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
            $sheet->setCellValue('A'.$i, $a);
            $sheet->setCellValue('B'.$i, $value['staff_name']);
            $sheet->setCellValue('C'.$i, $value['staff_contact']);
            $sheet->setCellValue('D'.$i, $value['designation']);

$a++;


            $i++;
          }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Girls.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2019 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
// echo 'rafay';
    }


    public function shops_get(){
        $data = $this->Shops_model->fetch();
        // print_r($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Shops');
        $i=3;
        $a=1;
        $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
        $spreadsheet->getActiveSheet()->unmergeCells('A1:B1');
        $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(20);
        $spreadsheet->getActiveSheet()->getPageSetup()
    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()
    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    //    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    //    $spreadsheet->getActiveSheet()->getColumnDimension('')->setAutoSize(true);
    // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22); 
    // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22); 
    $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true);
    $heading = "ALL Shops IN HOSTEL ".date("M")."/".date("y");;
        $sheet->setCellValue('A1',$heading);
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Shop Name');
        $sheet->setCellValue('C2', 'Contact');
        $sheet->setCellValue('D2', 'Proprietor');

        
        // $spreadsheet->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);


        $styleArray = [


            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
foreach($data as $key => $value) {
  
    
    $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);



    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

    // $spreadsheet->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(16);
            $sheet->setCellValue('A'.$i, $a);
            $sheet->setCellValue('B'.$i, $value['shop_name']);
            $sheet->setCellValue('C'.$i, $value['shop_contact']);
            $sheet->setCellValue('D'.$i, $value['Proprietor']);

$a++;


            $i++;
          }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Shops.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2019 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
// echo 'rafay';
    }


}