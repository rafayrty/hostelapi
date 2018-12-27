<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();
use Sse\Event;
    use Sse\SSE;
    use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;
use SMSGatewayMe\Client\Api\CallbackApi;
use SMSGatewayMe\Client\Model\CreateCallbackRequest;


Class notifications implements Event {
    public $data;
    public $messages;
    public $hostname;
    public $username;
    public $password;
    public $database;

    public function __construct($data,$messages,$hostname,$username,$password,$database){
$this->data=$data;
$this->messages=$messages;
$this->hostname=$hostname;
$this->username=$username;
$this->password=$password;
$this->database=$database;
}
public function connection(){
    
$conn = mysqli_connect($this->hostname,$this->username,$this->password,$this->database);
return $conn;
}
public function messages_update($key){
    $result = "UPDATE notify SET messages = '$key' WHERE id='1'";
$run = mysqli_query($this->connection(),$result);
if($run){
    return true;
}else{
    return false;
}
}
public function girls_contact($contact){
    $result = "SELECT * FROM `girls` WHERE contact = '$contact'";
    $run = mysqli_query($this->connection(),$result);
//  return  $rows=mysqli_fetch_array($run);
while($rows=mysqli_fetch_assoc($run)){
    $data[]=$rows;
}
return $data;
}
public function messages(){
    $result = "SELECT messages FROM `notify`";
    $run = mysqli_query($this->connection(),$result);
   $rows=mysqli_fetch_array($run);
   return $rows[0];
}

    public function update(){
        
        // $girls=$this->girls_contact('03147938798');
        // echo $girls[0]['name'];
        // var_dump($this->girls_contact('03147938798'));

        // Configure client
        $config = Configuration::getDefaultConfiguration();
        $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUzODgxMzA3OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjYyMjY2LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DyvybaWkAZ8RWdB0j8H8HsLWjK-CT4J867UZocqZlC0');
        $apiClient = new ApiClient($config);
        $messageClient = new MessageApi($apiClient);
        $results = $this->data;

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
        
        // if $message !empty and $newnumber == $value['contact'] then data to be sent to notify
        
        
        //if(!empty($messages)){
        //     $abcd=$this->login_model->girls_contact($value['contact']);
        //     echo $data['name']=$abcd[0]['name'];
        // }
        
        $msgs = $messages->getResults();
        
        // print_r($msgs->getCreatedAt()->format('h:m:i'));
        error_reporting(0);
        foreach ( $msgs as $msg ) {
            
            $msgout[] = array("msg" => $msg->getMessage(),'time'=>$msg->getCreatedAt()->format('h:i:s'),'contact'=>$value['contact']);  // Add other infor here
        }
        //echo count($msgout);
        //print_r($msgout);

        }
        end($msgout); 
        // return $msgout[0]['msg'];
        // return json_encode($msgout);

         $name;       // move the internal pointer to the end of the array
        $key = key($msgout);// fetches the key of the element pointed to by the internal pointer
        //  return $key;

          $messages=$this->messages();
        //  $total = $messages + 1;
//          if($key == $total){
//      $update = $this->messages_update($key);
//         $contact=$msgout[$key]['contact'];
//         $result = $this->girls_contact($contact);
//         // $data['msg']=$msgout[0]['msg'];
//         // $data['name'] = $result[0]['name'];
//         // $data['id']=$result[0]['id'];
//         // $data['image']=$result[0]['image'];

//         $message = array(
//             'msg'=>$msgout[0]['msg'],
//             'name'=>$result[0]['name'],
//             'id'=>$result[0]['id'],
//             'image'=>$result[0]['image']
//         );
//         // return true;
// return json_encode($message);
//         // r$this->response($message);
//     }else
//  echo $key;
if($key > $messages){
        $abc = $key - $messages;
        $key_update=$key;
        $remaining = array_slice($msgout, 0, $abc);
        $i=0;
        // print_r($remaining);
        $resulta=[];
        foreach ($remaining as $key => $value) {
        $contact=$remaining[$i]['contact'];
         $result = $this->girls_contact($contact);
        // $data['msg']=$remaining[$i]['msg'];
        // $data['name'] = $result[0]['name'];
        // $data['id']=$result[0]['id'];
        // $data['image']=$result[0]['image'];
        // print_r($result);
$message = array(
    'msg'=>$remaining[$i]['msg'],
    'name'=>$result[0]['name'],
    'id'=>$result[0]['id'],
    'image'=>$result[0]['image']
);
foreach ($message as $key) {
    $range[] = $key;
}
// $resulta = call_user_func_array("array_merge", $message);
// return json_encode($message[$i]);
// var_dump(array_merge($resulta));

// var_dump($message);
// json_encode($message);
//  var_dump($message);
// mysqli_error($this->connection);
// return $this->response($message);
// return true;
        // $this->load->view('notify',$data);
        sleep(1);
        $i++;
        }
if($range){
        $update = $this->messages_update($key_update);
}
      return json_encode(array_chunk($range, 4));
             }else{
             return json_encode(false);
         }







        //Here's the place to send data
    }
    
    public function check(){
        //Here's the place to check when the data needs update
        return true;
    }
}
//create the event handler
class Emergency_Girls implements Event {
    public $data;
    public $time;
    public $template;
    public function __construct($data,$time,$template){
$this->data=$data;
$this->time=$time;
$this->template=$template;

}
public function update(){
// if($_SESSION['executed']){
// unset($_SESSION['executed']);
// sleep(60);
// }else{
date_default_timezone_set('Asia/Karachi');
$time=strtotime(date("H:i"));
if($time == strtotime($this->time)){
$result=$this->data;
$config = Configuration::getDefaultConfiguration();
$config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUzODgxMzA3OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjYyMjY2LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DyvybaWkAZ8RWdB0j8H8HsLWjK-CT4J867UZocqZlC0');
$apiClient = new ApiClient($config);
$messageClient = new MessageApi($apiClient);
for($i = 0; $i < count($result); $i++) {
    $aname[$i] = new SendMessageRequest([
    'phoneNumber' => $result[$i]['contact'],
    'message' => $this->template." ".$result[$i]['name'],
    'deviceId' => 103150
]);
$sendMessages = $messageClient->sendMessages([
$aname[$i]
]);
if($sendMessages){
sleep(60);
return true;
//$_SESSION['executed']=true;
}else{
return false;
}
//}

}
}
//return json_encode($);
}
    
			
    public function check(){
        //Here's the place to check when the data needs update
        return true;
    }
}
Class Server extends CI_Controller{
    public function __construct(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        parent::__construct();
    }
     public function index(){
         
         $array = array('name'=>'rafay');
         $time=$this->Sms_model->sms_time_girls();
         $data = $this->Sms_model->exit_sms();
         $template = $this->Sms_model->message_template();
        $sse = new SSE(); //create a libSSE instance
        $sse->addEventListener('event_name', new Emergency_Girls($data,$time,$template));//register your event handler
        $sse->start();//start the event loop
    //     $output=$this->Girls_model->fetch();
    //   print_r($output);
        }
        public function notifications(){


            $instance = &get_instance();
            $instance->load->database();
            $hostname= $instance->db->hostname;
            $username=$instance->db->username;
            $password=$instance->db->password;
            $database = $instance->db->database;
            $sse = new SSE(); //create a libSSE instance
            $sse->addEventListener('event_name', new notifications($this->Girls_model->smscheck(),$this->Sms_model->messages(),$hostname,$username,$password,$database));//register your event handler
            $sse->start();//start the event loop
        }
    
    }