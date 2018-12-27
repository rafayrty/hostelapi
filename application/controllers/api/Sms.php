<?php
ob_start();
require APPPATH.'libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;
use SMSGatewayMe\Client\Api\CallbackApi;
use SMSGatewayMe\Client\Model\CreateCallbackRequest;
class Sms extends REST_Controller {
    
public function __construct(){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    parent::__construct();
}
public function settings_get(){
    $girls_time = $this->Sms_model->sms_time_girls();
    $template = $this->Sms_model->message_template();
    $shops = $this->Sms_model->sms_time_shops();
    $data = array(
'status'=>true,
'girls_time'=>$girls_time,
'template'=>$template,
'shops_time'=>$shops,

    );
    $this->response($data);
}
public function settings_update_post(){
$data = $this->security->xss_clean($_POST);
  $girls_sms=  $this->form_validation->set_rules('girls_sms', 'Girls Sms', 'trim|required');
  $shops_sms=  $this->form_validation->set_rules('shops_sms', 'Shops sms', 'trim|required');
  $template=  $this->form_validation->set_rules('template', 'Message Template', 'trim|required');
  if ($this->form_validation->run() == FALSE)
  {
          $message =  array(
              'status'=>false,
              'error'=>$this->form_validation->error_array(),
              'message' => validation_errors()
          );
          $this->response($message);
  }else{

    $girls_sms=$this->input->post('girls_sms',TRUE);
      $shops_sms=$this->input->post('shops_sms',TRUE);
     $template=$this->input->post('template',TRUE);
     $output = 0;
     $output1 = $this->Sms_model->settings_template_update($template);
     if($output1==true){
         $output = $output1;
     }
     $output2 = $this->Sms_model->settings_girls_sms_update($girls_sms);
     if($output2==true){
         $output = $output2;
     }
     $output3 =$this->Sms_model->settings_shops_sms_update($shops_sms);
     if($output3==true){
         $output=$output3;
     }
    
        if($output){
$response=array(
    'status'=>true,
    'message'=>'Updated Successfully',
);
        $this->response($response);
        }else{
            $response=array(
                'status'=>false,
                'message'=>'Settings Were Not Updated',
            );
            $this->response($response);
        }

}
}
public function check_get(){

	$this->load->library("PhpMailerLib");
    $mail = $this->phpmailerlib->load();
    
	$this->load->library("PhpMailerLib");
    $mail = $this->phpmailerlib->load();

        //Server settings
                                     // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'abdulrafayrty@gmail.com';                 // SMTP username
		$mail->Password = '147852369asd';                           // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;                                    // TCP port to connect to
		//Recipients
		$mail->setFrom('abdulrafayrty@gmail.com', 'Admin');
		$mail->addAddress('abdulrafayrty@gmail.com');               // Name is optional
		$mail->addReplyTo('abdulrafayrty@gmail.com', 'Abdul Rafay');                          // TCP port to connect to

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();


}
public function index_get(){
}
public function fetch_girls_sms_post($id){

    $config = Configuration::getDefaultConfiguration();
    $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUzODgxMzA3OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjYyMjY2LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DyvybaWkAZ8RWdB0j8H8HsLWjK-CT4J867UZocqZlC0');
    $apiClient = new ApiClient($config);
    $messageClient = new MessageApi($apiClient);
    $results = $this->Girls_model->contact_check($id);
        $countryCode = "92";
        $phoneNumber = $results;
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


    $msgs = $messages->getResults();
    $msgout = [];
    foreach ( $msgs as $msg ) {
        $msgout[] = ["msg" => $msg->getMessage(),"date" => $msg->getCreatedAt()->format('Y-m-d h:i:s')]; 
    
    }
$a=1;

    array_unshift($msgout, "phoney");
    unset($msgout[0]);
//     function arrayfinding($products, $field, $value)
// {
//    foreach($products as $key => $product)
//    {
//       if ( $product[$field] === $value )
//          return $key;
//    }
//    return false;
// }

$record_per_page = 20;  
$page = '';  
if($this->input->post('page'))
{
	 $page = $this->input->post('page');  
}  
else  
{  
	 $page = 1;  
}
$start_from = ($page - 1)*$record_per_page;  
$total_records= count($msgout);
$total_pages = ceil($total_records/$record_per_page);
$msgout=array_slice($msgout,$start_from,$record_per_page);
$number = 1;
$currentNumber = ($page - 1) * $record_per_page + $number;

if(!empty($msgout)){
    $message =array('status'=>true,'output'=>$msgout,'pages'=>$total_pages);
    $this->response($message);
		}else{

            $message =array('status'=>false);
            $this->response($message);

        }
}

public function sendtogirls_post()
{
  
    $data = $this->security->xss_clean($_POST);
if($this->input->post('message')){
    $msg = $this->input->post('message');
    $result = $this->Sms_model->message_girls();       
    // Configure client
    $config = Configuration::getDefaultConfiguration();
    $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUzODgxMzA3OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjYyMjY2LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DyvybaWkAZ8RWdB0j8H8HsLWjK-CT4J867UZocqZlC0');
    $apiClient = new ApiClient($config);
    $messageClient = new MessageApi($apiClient);
    //this will go insidea  foreach loop
    // Sending a SMS Message
    for ($i = 0; $i < count($result); $i++) {
      
          $aname[$i] = new SendMessageRequest([
        'phoneNumber' => $result[$i]['contact'],
        'message' => $msg,
        'deviceId' => 103150
    ]);
    $sendMessages = $messageClient->sendMessages([
        $aname[$i]
    ]);
    }
    if($sendMessages){
$status=true;
$this->response($status,REST_Controller::HTTP_OK);
}else{
        $status=false;
        $this->response($status,REST_Controller::HTTP_OK);

    }

}
}
public function sendtostaff_post()
{
  
    $data = $this->security->xss_clean($_POST);
if($this->input->post('message')){
    $msg = $this->input->post('message');
    $result = $this->Sms_model->message_staff();       
    // Configure client
    $config = Configuration::getDefaultConfiguration();
    $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUzODgxMzA3OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjYyMjY2LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DyvybaWkAZ8RWdB0j8H8HsLWjK-CT4J867UZocqZlC0');
    $apiClient = new ApiClient($config);
    $messageClient = new MessageApi($apiClient);
    //this will go insidea  foreach loop
    // Sending a SMS Message
    for ($i = 0; $i < count($result); $i++) {
      
          $aname[$i] = new SendMessageRequest([
        'phoneNumber' => $result[$i]['staff_contact'],
        'message' => $msg,
        'deviceId' => 103150
    ]);
    $sendMessages = $messageClient->sendMessages([
        $aname[$i]
    ]);
    }
    if($sendMessages){
$status=true;
$this->response($status,REST_Controller::HTTP_OK);
}else{
        $status=false;
        $this->response($status,REST_Controller::HTTP_OK);

    }

}
}
public function sendtoshops_post()
{
  
    $data = $this->security->xss_clean($_POST);
if($this->input->post('message')){
    $msg = $this->input->post('message');
    $result = $this->Sms_model->message_shops();       
    // Configure client
    $config = Configuration::getDefaultConfiguration();
    $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUzODgxMzA3OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjYyMjY2LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DyvybaWkAZ8RWdB0j8H8HsLWjK-CT4J867UZocqZlC0');
    $apiClient = new ApiClient($config);
    $messageClient = new MessageApi($apiClient);
    //this will go insidea  foreach loop
    // Sending a SMS Message
    for ($i = 0; $i < count($result); $i++) {
          $aname[$i] = new SendMessageRequest([
        'phoneNumber' => $result[$i]['shop_contact'],
        'message' => $msg,
        'deviceId' => 103150
    ]);
    $sendMessages = $messageClient->sendMessages([
        $aname[$i]
    ]);
    }
    if($sendMessages){
$status=true;
$this->response($status,REST_Controller::HTTP_OK);
}else{
        $status=false;
        $this->response($status,REST_Controller::HTTP_OK);

    }

}
}
public function send_message_girl_post($id){
    $data = $this->security->xss_clean($_POST);
    $girls_sms=  $this->form_validation->set_rules('message', 'Message', 'trim|required');
    if ($this->form_validation->run() == FALSE)
    {
            $message =  array(
                'status'=>false,
                'error'=>$this->form_validation->error_array(),
                'message' => validation_errors()
            );
            $this->response($message);
        }else{
    $config = Configuration::getDefaultConfiguration();
    $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUzODgxMzA3OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjYyMjY2LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DyvybaWkAZ8RWdB0j8H8HsLWjK-CT4J867UZocqZlC0');
    $apiClient = new ApiClient($config);
    $messageClient = new MessageApi($apiClient);
    $number= $this->Girls_model->contact_check($id);
    //   echo $number;

// Sending a SMS Message
$sendMessageRequest1 = new SendMessageRequest([
    'phoneNumber' => $number,
    'message' => $this->input->post('message',true),
    'deviceId' => 103150
]);
$sendMessages = $messageClient->sendMessages([
    $sendMessageRequest1,
]);
if($sendMessages){
    $status=true;
    $this->response($status);
}

}


}

}



?>