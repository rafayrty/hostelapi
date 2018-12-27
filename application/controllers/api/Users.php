<?php
require APPPATH.'libraries/REST_Controller.php';
 
class Users extends REST_Controller {

public function __construct(){
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
parent::__construct();
    }
/*
add new user
 */
public function forgot_post(){
$data = $this->security->xss_clean($_POST);
if($this->input->post('email',true)){
$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
if($this->form_validation->run() == FALSE){
    $message =  array(
        'status'=>false,
        'error'=>$this->form_validation->error_array(),
        'message' => validation_errors()
    );
    $this->response($message);
}else{
 $email=   $this->input->post('email',true);
    $check_email=$this->User_model->check_email($email);
    if($check_email){

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
            $mail->addAddress($email);               // Name is optional
            $mail->addReplyTo('abdulrafayrty@gmail.com', 'Abdul Rafay');                          // TCP port to connect to
    
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Forgot Password Request From Sindh Hostel';
            $mail->Body    = 'The Password For Your Account Is <b>'.$check_email.'</b>';
            $mail->AltBody = 'The Password For Your Account Is '.$check_email;
    
            $mail->send();


        $message =  array(
            'status'=>true,
        );  
        $this->response($message);
    }else{
        $message =  array(
            'status'=>false,
            'message' =>'The Email Doesn\'t Exist Please Check For Typos' 
        );  
        $this->response($message);
    }
}
}else{
    $message =  array(
        'status'=>false,
        'message' =>'Please Provide An Email' 
    );  
    $this->response($message);
}
}

public function users_search_post(){

$query = '';
$data = $this->security->xss_clean($_POST);
if($this->input->post('query',true)){
    $query = $this->input->post('query',true);
}
$page = '';  
if($this->input->post('record_per_page',true)){
    $record_per_page = $this->input->post('record_per_page',true);
}else{
    $record_per_page = 3; 
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
$output=$this->User_model->fetchusers($query,$start_from,$record_per_page);
$total_pages=$this->User_model->pagination($record_per_page);
$output =array('output'=>$output,'pages'=>$total_pages);
//print_r($output);
if($output != '' || empty($output)){
   $this->response($output);
}
}
 public function insert_post(){
    $data = $this->security->xss_clean($_POST);
$this->form_validation->set_rules('role', 'Role', 'trim|required');
$this->form_validation->set_rules('password', 'Password', 'trim|required');
$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]',array('is_unique'=>'%s Is Already Registered'));
if ($this->form_validation->run() == FALSE)
{
        $message =  array(
            'status'=>false,
            'error'=>$this->form_validation->error_array(),
            'message' => validation_errors()
        );
        $this->response($message);
}
else
{
$insert_data=[
'email'=>$this->input->post('email',TRUE),
'password'=>$this->input->post('password',TRUE),
'role'=>$this->input->post('role',TRUE)];
$output=$this->User_model->insert_user($insert_data);
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
}
 }

 public function fetch_users_get(){
   $data= $this->User_model->fetchusers();
    $this->response($data);
 }
 public function fetch_user_get($id)
{
    $data= $this->User_model->user($id);
    if(empty($data) || !is_numeric($id) || $data == false){
        $message =  array(
            'status'=>false,
            'message' => 'Invalid Id'
        );
        $this->response($message);
    }else{
$user_data=array(
'status'=>true,
'id'=>$id,
'email'=>$data->email,
'password'=>$data->password,
'role'=>$data->role
);
$this->response($user_data);
    }
}
public function login_post(){
    $data = $this->security->xss_clean($_POST);
    $this->form_validation->set_rules('email', 'Email', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');
    if ($this->form_validation->run() == FALSE)
    {
            $message =  array(
                'status'=>false,
                'error'=>$this->form_validation->error_array(),
                'message' => validation_errors()
            );
            $this->response($message);
    }
    else
    {
$output=$this->User_model->user_login($this->input->post('email'),$this->input->post('password'));
if(!empty($output) AND $output!='FALSE'){
    $user_data=array(
'user_email'=>$output->email,
'user_id'=>$output->id,
'user_role'=>$output->role
    );
    $message =  array(
        'status'=>true,
        'user_data'=>$user_data,
        'message' =>'Logged In Successfully',
    );
    $this->response($message,REST_Controller::HTTP_OK);

}else{
    $message =  array(
        'status'=>false,
        'message' =>'Email or Password Is Incorrect',
    );
    $this->response($message,REST_Controller::HTTP_OK);
}
    }
}
public function deleteuser_delete($id){

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
$output=$this->User_model->deleteuser($id);

if(!empty($output) AND $output!='FALSE'){
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
public function updateuser_post($id){
 $data = $this->security->xss_clean($_POST);
 $this->form_validation->set_rules('role', 'Role', 'trim|required');
$this->form_validation->set_rules('password', 'Password', 'trim|required');
$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
if ($this->form_validation->run() == FALSE)
{
        $message =  array(
            'status'=>false,
            'error'=>$this->form_validation->error_array(),
            'message' => validation_errors()
        );
        $this->response($message);
}
else
{
$update_data=[
'role'=>$this->input->post('role',TRUE),
'email'=>$this->input->post('email',TRUE),
'password'=>$this->input->post('password',TRUE)

];
$output=$this->User_model->updateuser($id,$update_data);
if($output > 0 AND !empty($output)){
    $message =  array(
        'status'=>true,
        'message' =>'User Updated Successfully',
    );
    $this->response($message,REST_Controller::HTTP_OK);

}else{
    $message =  array(
        'status'=>false,
        'message' =>'User Was Not Updated',
    );
    $this->response($message,REST_Controller::HTTP_OK);
}
}
}

}