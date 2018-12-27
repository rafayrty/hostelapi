<?php
require APPPATH.'libraries/REST_Controller.php';
 
class Shops extends REST_Controller {

public function __construct(){
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
parent::__construct();
    }
    public function multi_reportsdelete_post(){
        $data = $this->security->xss_clean($_POST);
       
        if(!empty($data) && $data !=''){
            if($this->input->post('records')){
                 $cnt=array();
            $cnt=count($this->input->post('records'));
           
            for($i=0;$i<$cnt;$i++)
             {
                $del_id=$_POST['records'][$i];
                $delete = $this->Shops_model->deletereport($del_id);
             }		
                    if($delete){
                        $message =  array(
                            'status'=>true,
                            'message' =>'Records Were Deleted Successfully',
                        );
                        $this->response($message,REST_Controller::HTTP_OK);
                    }else{
                        $message =  array(
                            'status'=>true,
                            'message' =>'Records Were Not Deleted',
                        );
                        $this->response($message,REST_Controller::HTTP_OK);
    
                    }
                }else{
                    $message =  array(
                        'status'=>false,
                        'message' =>'No Id Provided',
                    );
                    $this->response($message,REST_Controller::HTTP_OK);
                }
        }else{
            $message =  array(
                'status'=>false,
                'message' =>'No Id Provided',
            );
            $this->response($message,REST_Controller::HTTP_OK);
        }
       
    }
    public function deletereports_delete($id){
        $_POST = $this->security->xss_clean($id);
        if (empty($id) AND !is_numeric($id))
        {
                $message =  array(
                    'status'=>false,
                    'message' => 'Invalid Id'
                );
                $this->response($message);
        }
        else
        {
    
            $output = $this->Shops_model->deletereport($id);
    if(!empty($output) AND $output!='FALSE'){
        $message =  array(
            'status'=>true,
            'message' =>'Report Has Been Deleted Successfully',
        );
        $this->response($message,REST_Controller::HTTP_OK);
    
    }else{
        $message =  array(
            'status'=>false,
            'message' =>'Report Was Not Deleted',
        );
        $this->response($message,REST_Controller::HTTP_OK);
    }
        }
    
    }
    public function shops_reports_custom_post($id){
        $data = $this->security->xss_clean($_POST);
        if($this->input->post('date1',true) && $this->input->post('date2',true)){
        $date1 = $this->input->post('date1',true);
        $date2 = $this->input->post('date2',true);
        $output=$this->Shops_model->shops_reports_custom($id,$date1,$date2);
        if($output){
            $message = array(
                'status'=>true,
                'output'=>$output
            );
            $this->response($message);
            }else{
                $message = array(
            'status'=>false,
            'message'=>"Records Doesn't Exist For The Dates",);
            }
            $this->response($message);
        }else{
            $message = array(
                'status'=>false,
                 'message'=>"Please Provide A Valid Date"
            );
            $this->response($message);
        }
        }
    public function shops_reports_month_post($id){
        $data = $this->security->xss_clean($_POST);
        if($this->input->post('month',true)){
        $month = $this->input->post('month',true);
        $output=$this->Shops_model->shops_reports_month($id,$month);
        if($output){
            $message = array(
                'status'=>true,
                'output'=>$output
            );
            $this->response($message);
            }else{
                $message = array(
            'status'=>false,
            'message'=>"Records Doesn't Exist For The Month",);
            }
            $this->response($message);
        }else{
            $message = array(
                'status'=>false,
                 'message'=>"Please Provide A Valid Month"
            );
            $this->response($message);
        }
        }
        public function checkin_post(){
            $data = $this->security->xss_clean($_POST);
        if($this->input->post('id',true)){
            $id=$this->input->post('id');
            $entry_time=date("H:i:s");
            $output=$this->Shops_model->checkin($entry_time,$id);
         if($output){
             $message = array(
        'status'=>true,
        'message'=>'Checked In'
             );
             $this->response($message);
         }else{
            $message = array(
                'status'=>false,
                'message'=>'Checked Out'
                     );
            $this->response($message);
         }   
        }
        }
        public function shops_record_post(){
            $data = $this->security->xss_clean($_POST);
            if($this->input->post('qrid',true))
            {
            $output=$this->Shops_model->shops_record($this->input->post('qrid',true));
            if(!empty($output)){
                $message = array(
                    'status'=>true,
                    'output'=>$output
                );
                $this->response($message,REST_Controller::HTTP_OK);
            }else{
                $message = array(
                    'status'=>false,
                    'message'=>'qrid not found'
                );
                $this->response($message,REST_Controller::HTTP_OK);
            
            }
            }
            }
        public function check_status_get($id){
            $output=$this->Shops_model->status_check($id);
             if($output){
                 $message = array(
            'status'=>true,
            'output'=>$output
                 );
                 $this->response($message);
             }else{
                $message = array(
                    'status'=>false,
                    'message'=>'An Unexpected Error Was Caused'
                         );
                $this->response($message);
             }   
            }
        public function checkout_post(){
            $data = $this->security->xss_clean($_POST);
        if($this->input->post('id',true)){
            $id=$this->input->post('id',true);
            $exit_time=date("H:i:s");
            $output=$this->Shops_model->exit_time($exit_time,$id);
         if($output){
             $message = array(
        'status'=>true,
        'message'=>'Checked Out'
             );
             $this->response($message);
         }else{
            $message = array(
                'status'=>false,
                'message'=>'UnSuccessfull'
                     );
            $this->response($message);
        
         }   
        }
        
        }

        public function update_status_post(){
            $data = $this->security->xss_clean($_POST);
           $id=$this->input->post('id',true);
            $status = $this->input->post('status',true);
            $output=$this->Shops_model->update_status($id,$status);
           // print_r($output);
            if($output){
        $message = array(
            'status'=>true,
            'message'=>'Updated Successfully',
        );
        $this->response($message);
            }else{
                $message = array(
                'status'=>false,
                'message'=>'An Unknown Error Occurred',
                );
                $this->response($message);
            }
        }
    public function shops_reports_year_post($id){
        $data = $this->security->xss_clean($_POST);
        if($this->input->post('year',true)){
        $year = $this->input->post('year',true);
        $output=$this->Shops_model->shops_reports_year($id,$year);
        if($output){
            $message = array(
                'status'=>true,
                'output'=>$output
            );
            $this->response($message);
            }else{
                $message = array(
            'status'=>false,
            'message'=>"Records Doesn't Exist For The Year",);
            }
            $this->response($message);
        }else{
            $message = array(
                'status'=>false,
                 'message'=>"Please Provide A Valid Year"
            );
            $this->response($message);
        }
        }
    public function shops_reports_date_post($id){
        $data = $this->security->xss_clean($_POST);
        if($this->input->post('date',true)){
        $date = $this->input->post('date',true);
        $output=$this->Shops_model->shops_reports_date($id,$date);
        if($output){
            $message = array(
                'status'=>true,
                'output'=>$output
            );
            $this->response($message);
            }else{
                $message = array(
            'status'=>false,
            'message'=>"Records Doesn't Exist For The Date",
                );
            }
            $this->response($message);
        }else{
            $message = array(
                'status'=>false,
                 'message'=>"Please Provide A Valid Date"
            );
            $this->response($message);
        }
        }


public function shops_reports_fetch_post($id){
    $page = '';  
            // if($this->input->post('record_per_page',true)){
            //     $record_per_page = $this->input->post('record_per_page',true);
            // }else{
                $record_per_page = 2; 
            if($this->input->post('page',true))
            {
            $page = $this->input->post('page',true);  
            }  
            else  
            {  
            $page = 1;  
            }
            $start_from = ($page - 1)*$record_per_page;  
            $output=$this->Shops_model->shops_reports_fetch($id,$start_from,$record_per_page);
            // $output=$this->Girls_model->fetch_data($query,$start_from,$record_per_page);
            $total_pages=$this->Shops_model->pagination_reports($id,$record_per_page);
        if($output){
            $message =array('status'=>true,'output'=>$output,'pages'=>$total_pages);
        $this->response($message);
        }else{
            $message = array(
        'status'=>false,
        'message'=>"Records Doesn't Exist",
            );
        }
        $this->response($message);
        }
    public function shops_name_get($id){
        $output=$this->Shops_model->namecheck($id);
        if($output){
        $message = array('status'=>true,'name'=>$output);
        }else{
        $message = array('status'=>false);
        };
        $this->response($message);
        }
    
public function shops_search_post(){

 $query = '';
 $data = $this->security->xss_clean($_POST);
 if($this->input->post('query',true)){
     $query = $this->input->post('query',true);
 }
 $page = '';  
 if($this->input->post('record_per_page',true)){
     $record_per_page = $this->input->post('record_per_page',true);
 }else{
     $record_per_page = 2; 
 }
 if($this->input->post('page',true))
 {
      $page = $this->input->post('page',true);  
 }  
 else  
 {  
      $page = 1;  
 }
 $start_from = ($page - 1)*$record_per_page;  
 $output=$this->Shops_model->fetch_data($query,$start_from,$record_per_page);
 $total_pages=$this->Shops_model->pagination($record_per_page);
 $output =array('output'=>$output,'pages'=>$total_pages);
 //print_r($output);
 if($output != '' || empty($output)){
    $this->response($output);
 }
 }
 public function deleteshops_delete($id){

    $_POST = $this->security->xss_clean($id);
    if (empty($id) AND !is_numeric($id))
    {
            $message =  array(
                'status'=>false,
                'message' => 'Invalid Id'
            );
            $this->response($message);
    }
    else
    {
        $delimg = $this->Shops_model->delimgcheck($id);
        $qrimg=$this->Shops_model->qrcodecheck($id);	
        $output = $this->Shops_model->delete($id);
if(!empty($output) AND $output!='FALSE'){
    $path= $_SERVER['DOCUMENT_ROOT']."/hostelapi/uploads/Shops/".$delimg;
    $pathb= $_SERVER['DOCUMENT_ROOT']."/hostelapi/qrcodes/Shops/".$qrimg;
    unlink($path);
    unlink($pathb);
    $reports_delete = $this->Shops_model->shops_reports_delete_shops_id($id);
    $message =  array(
        'status'=>true,
        'message' =>'User Has Been Deleted Successfully',
    );
    $this->response($message,REST_Controller::HTTP_OK);

}else{
    $message =  array(
        'status'=>false,
        'message' =>'User Was Not Deleted',
    );
    $this->response($message,REST_Controller::HTTP_OK);
}
    }
}
public function multi_delete_post(){
    $data = $this->security->xss_clean($_POST);
   
    if(!empty($data) && $data !=''){
        if($this->input->post('records')){
             $cnt=array();
        $cnt=count($this->input->post('records'));
       
        for($i=0;$i<$cnt;$i++)
         {
            $del_id=$_POST['records'][$i];
            $delimg = $this->Shops_model->delimgcheck($del_id);
            $qrimg=$this->Shops_model->qrcodecheck($del_id);	
                        $path= $_SERVER['DOCUMENT_ROOT']."/hostelapi/uploads/Shops/".$delimg;
                        $pathb= $_SERVER['DOCUMENT_ROOT']."/hostelapi/qrcodes/Shops/".$qrimg;
                        unlink($path);
                        unlink($pathb);
            $delete = $this->Shops_model->delete($del_id);
            $reports_delete = $this->Shops_model->shops_reports_delete_shops_id($del_id);

         }		
                if($delete){
                    $message =  array(
                        'status'=>true,
                        'message' =>'Records Were Deleted Successfully',
                    );
                    $this->response($message,REST_Controller::HTTP_OK);
                }else{
                    $message =  array(
                        'status'=>true,
                        'message' =>'Records Were Not Deleted',
                    );
                    $this->response($message,REST_Controller::HTTP_OK);

                }
            }else{
                $message =  array(
                    'status'=>false,
                    'message' =>'No Id Provided',
                );
                $this->response($message,REST_Controller::HTTP_OK);
            }
    }else{
        $message =  array(
            'status'=>false,
            'message' =>'No Id Provided',
        );
        $this->response($message,REST_Controller::HTTP_OK);
    }
   
}

public function update_post($id){

    $data= $this->security->xss_clean($_POST);
    $this->form_validation->set_rules('shop_name', 'Name', 'trim|required');
    $this->form_validation->set_rules('shop_contact', 'contact', 'trim|required');
    $this->form_validation->set_rules('prop', 'Proprietor', 'trim|required');
if($this->form_validation->run()==FALSE){
    $message =  array(
        'status'=>false,
        'error'=>$this->form_validation->error_array(),
        'message' => validation_errors()
    );
    $this->response($message);
}else{
    if(isset($_FILES['userfile']['name']))
	{
$config['upload_path'] = './uploads/Shops';
$config['allowed_types'] =     'gif|jpg|png|jpeg';
$config['max_size']             = 10000;
$config['max_width']            = 19200;
$config['max_height']           = 10800;//Get the girl name from db and name it
$config['file_name']            = $image_name=$this->input->post('old',TRUE);
$config['overwrite']            = TRUE;
$this->load->library('upload', $config);
if ( ! $this->upload->do_upload('userfile'))
{
//$error = array('error' => $this->upload->display_errors());
    $message =  array(
            'status'=>false,
            'error'=>$this->upload->display_errors(),
            'message' => 'File Not Uploaded'
        );
        $this->response($message,REST_Controller::HTTP_OK);
        //$this->load->view('upload_form', $error);
}else{
    $upload_data=$this->upload->data();
    $image_name=$upload_data['file_name'];
}
    }else{
        $image_name=$this->input->post('old',TRUE);
    } if($this->input->post('shop_contact',TRUE) != $this->input->post('oldcontact')){
        $pathb= FCPATH."qrcodes/shops/".$this->input->post('oldqr',TRUE);
            unlink($pathb);
            $this->load->library('ciqrcode');
            $shop_contact=$this->input->post('shop_contact',TRUE);
            $name=$this->input->post('shop_name',TRUE);
            $params['data'] = $shop_contact;
            $params['level'] = 'H';
            $params['size'] = 10;
            $qrname= str_replace(' ','',$name);
            $params['savename'] = FCPATH.'qrcodes/Shops/'.$qrname.'.png';
            $this->ciqrcode->generate($params);
            $qrcode_db=$qrname.'.png';

        }else{
            $qrcode_db=$this->input->post('oldqr');
        }
        $update_data=[
            'shop_name'=>$this->input->post('shop_name',TRUE),
            'shop_contact'=>$this->input->post('shop_contact',TRUE),
            'prop'=>$this->input->post('prop',TRUE),
            'image'=>$image_name,
            'qrcode'=>$qrcode_db         
            ];

            $output=$this->Shops_model->updateshops($id,$update_data);
            if($output > 0 AND !empty($output) OR isset($_FILES['userfile']['name'])){
                $message =  array(
                    'status'=>true,
                    'output'=>$output,
                    'message' =>'User Updated Successfully',
                );
                $this->response($message,REST_Controller::HTTP_OK);            
            }else{
                $message =  array(
                    'output'=>$output,
                    'status'=>false,
                    'message' =>'User Was Not Updated',
                );
            $this->response($message,REST_Controller::HTTP_OK);
            }
}
}




public function fetch_shops_get($id){
    $output=$this->Shops_model->update_show($id);
    if($output){
        $user_data=array(
            'status'=>true,
            'output'=>$output
        );
    }else{
        $user_data=array(
            'status'=>false,
            'message'=>"Id Doesn't Exists"
        );
    }
    $this->response($user_data);
    }
public function insert_post(){
    $data = $this->security->xss_clean($_POST);
    $this->form_validation->set_rules('shops_name', 'Name', 'trim|required');
$this->form_validation->set_rules('shops_contact', 'contact', 'trim|required|is_unique[shops.shop_contact]');
$this->form_validation->set_rules('prop', 'Properiator', 'trim|required');
$this->load->library('ciqrcode');
if ($this->form_validation->run() == FALSE)
{
        $message =  array(
            'status'=>false,
            'error'=>$this->form_validation->error_array(),
            'message' => validation_errors()
        );
        $this->response($message);
}else{
    $contact=$this->input->post('shops_contact',TRUE);
    $shops_name=$this->input->post('shops_name',TRUE);
    $params['data'] = $contact;
    $params['level'] = 'H';
    $params['size'] = 10;
    $qrname= str_replace(' ','',$shops_name);
    $params['savename'] = FCPATH.'qrcodes/Shops/'.$qrname.'.png';
    $this->ciqrcode->generate($params);
    $qrdb = $qrname.".png";
    $config['upload_path']          = './uploads/Shops';
    $config['overwrite']            = TRUE;
     $config['allowed_types']        = 'gif|jpg|png|jpeg';
    $config['max_size']             = 10000;
    $config['max_width']            = 19200;
    $config['max_height']           = 10800;//Get the girl name from db and name it
    $config['file_name']            =str_replace(' ','',$shops_name);
    $this->load->library('upload', $config);

    if ( ! $this->upload->do_upload('userfile'))
    {
            //$error = array('error' => $this->upload->display_errors());
            $message =  array(
                'status'=>false,
                'error'=>$this->upload->display_errors(),
                'message' => 'File Not Uploaded'
            );
            $this->response($message,REST_Controller::HTTP_OK);
            //$this->load->view('upload_form', $error);
    }
    else
    {
            $data = array('upload_data' => $this->upload->data());
            $image=$this->upload->data('file_name');
            $insert_data=[
                'shop_name'=>$this->input->post('shops_name',TRUE),
                'shop_contact'=>$this->input->post('shops_contact',TRUE),
                'prop'=>$this->input->post('prop',TRUE),
                'qrcode'=>$qrdb,
                 'image'=>$image                
                ];
                $output=$this->Shops_model->insert_shops($insert_data);
                if($output > 0 AND !empty($output)){
                    $message =  array(
                        'status'=>true,
                        'message' =>'User Registration Successfully',
                    );
                    $this->response($message,REST_Controller::HTTP_OK);
                
                }else{
                    $message =  array(
                        'status'=>false,
                        'message' =>'User Registration Was Unsuccessful',
                    );
                    $this->response($message,REST_Controller::HTTP_OK);
                }
// $message=array(
// 'status'=>true,
// 'message'=>'File Was Uploaded'
// );
    }
}
// echo '<img src="'.base_url().'tes.png" />';
}
 public function downqr_get($qrid){
    $file = 'qrcodes/Shops/'.$qrid;
    if (file_exists($file)) { 
header('Content-Description: File Transfer');  
header('Content-Type: application/octet-stream');  
header('Content-Disposition: attachment; filename="'.basename($file).'"'); 
header('Expires: 0');    
header('Cache-Control: must-revalidate');
header('Pragma: public'); 
header('Content-Length: ' . filesize($file));
readfile($file); 
exit;           
}else{
$status = false;
$this->response($status); 
             }
            
}
}
 