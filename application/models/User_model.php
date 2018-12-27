<?php

Class User_model extends CI_Model{

protected $user_table='users';
    public function insert_user(array $data){

        $this->db->insert($this->user_table,$data);
    return $this->db->insert_id();
    }
    public function user_login($email,$password){
$this->db->where('email',$email);
$this->db->where('password',$password);
$result=$this->db->get($this->user_table);
if($result->num_rows()==1){
    return $result->row();
}else{
    return false;
}

    }
    public function check_email($email){
        $this->db->where('email',$email);
        $result=$this->db->get($this->user_table);
        if($result->num_rows()==1){
            return $result->row(0)->password;
        }else{
            return false;
        }
        
            }
    public function check_role($id){
        $query =$this->db->where('id',$id);
        $result = $this->db->get($this->user_table);
        if($result->num_rows() == 1){
            return $result->row(0)->role;
             }else{
                 return false;
             }

    }
    
    public function updateuser($id,array $data){
    $this->db->where('id', $id);
    $this->db->update($this->user_table, $data);
    return $this->db->affected_rows();
    
}

public function fetchusers(){  
    return $this->db->get($this->user_table)->result_array();
}
public function pagination($record_per_page){
    $this->db->order_by('id', 'DESC');
    $result=$this->db->get($this->user_table);
    $total_records = $result->num_rows();  
    $total_pages=ceil($total_records/$record_per_page);
  return $total_pages;
  }
    public function deleteuser($id){
        $this->db->where('id', $id);
        $this->db->delete($this->user_table);
return  $this->db->affected_rows();
    }
    public function user($id){
        $this->db->where('id',$id);
        $result = $this->db->get($this->user_table);
        if($result->num_rows()==1){
        return $result->row();
        }else{
            return false;
        }
        }
    public function fetch_all_users(){
        $result = $this->db->get('users');
        return $result->result_array();    }
}