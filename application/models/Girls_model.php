<?php
Class Girls_model extends CI_Model{
protected $user_table='girls';
function fetch_data($query,$start_form,$recordsperpage)
	{
		$this->db->select("*");
		$this->db->from($this->user_table);
		if($query != '')
		{
			$this->db->like('name', $query);
			$this->db->or_like('email', $query);
			$this->db->or_like('contact', $query);
            $this->db->or_like('rollno', $query);
            $this->db->or_like('father_name', $query);
            $this->db->or_like('parent_contact', $query);
		}
		$this->db->order_by('id', 'DESC');
		$this->db->limit($recordsperpage,$start_form); 
		return $this->db->get()->result_array();
    }
    public function pagination($record_per_page){
		$this->db->order_by('id', 'DESC');
		$result=$this->db->get($this->user_table);
		$total_records = $result->num_rows();  
		$total_pages=ceil($total_records/$record_per_page);
	  return $total_pages;
      }
      public function update_show($id){
        $query = $this->db->where('id',$id);
       return $result= $this->db->get($this->user_table)->result_array();
      }
      public function insert_girls(array $data){

        $this->db->insert($this->user_table,$data);
    return $this->db->insert_id();
    }
    public function update_status($id,$status){
        date_default_timezone_set('Asia/Karachi');
        $abc=$this->db->query("SELECT * FROM `girls_reports` WHERE entry_time = '00:00:00' AND girl_id='$id' ")->result_array();
        $this->db->where('id', $id);
        if($abc){
            $data1 = [
                'status'=>$status
                ];
                $this->db->update($this->user_table, $data1);
                $data2=[
                    'entry_time'=>$entry_time=date("H:i:s")
                ];
                $this->db->update('girls_reports',$data1);
}else{
    $data = [
        'status'=>$status
     ];
     $this->db->update($this->user_table,$data);

}  
        return $this->db->affected_rows();
    }
    public function updategirls($id,array $data){
        $this->db->where('id', $id);
        $this->db->update($this->user_table, $data);
        return $this->db->affected_rows();
        
    }
    public function checkin($entry_time,$id){
    //   $id=  $this->db->select('id')->where('girl_id',$id)->order_by('id','desc')->limit(1)->get('girls_reports')->row('id');
      $this->db->set('entry_time', $entry_time);
      $this->db->where('girl_id',$id);
      $this->db->where('entry_time','00:00:00');
      $this->db->update('girls_reports'); // gives UPDATE mytable SET field = field+1 WHERE id = 2
return $this->db->affected_rows();
    }
      public function qrcodecheck($id){
    $query = $this->db->where('id',$id);
    $result = $this->db->get($this->user_table);
    if($result->num_rows() == 1){
    return $result->row(0)->qrcode;
        }else{
        return false;
             }
    }
    public function deletereport($id){
        $delete = $this->db->where('id',$id);
        $result = $this->db->delete('girls_reports');
        return  $rows=$this->db->affected_rows();
    }
    public function delete_girl_reports($id){
        $delete = $this->db->where('girl_id',$id);
        $result = $this->db->delete('girls_reports');
        return  $rows=$this->db->affected_rows();
    }
    public function delete($id){
        $delete = $this->db->where('id',$id);
        $result = $this->db->delete($this->user_table);
        return  $rows=$this->db->affected_rows();
    }
    public function status_check($id){
        $query = $this->db->where('id',$id);
        $result = $this->db->get($this->user_table);
        if($result->num_rows() == 1){
            return $result->row(0)->status;
             }else{
                 return false;
             }
    }
    public function contact_check($id){
        $query = $this->db->where('id',$id);
        $result = $this->db->get($this->user_table);
        if($result->num_rows() == 1){
            return $result->row(0)->contact;
             }else{
                 return false;
             }
    }
      public function delimgcheck($id){
        $query = $this->db->where('id',$id);
        $result = $this->db->get($this->user_table);
        if($result->num_rows() == 1){
            return $result->row(0)->image;
             }else{
                 return false;
             }
    }
    public function namecheck($id){
        $query = $this->db->where('id',$id);
        $result = $this->db->get($this->user_table);
        if($result->num_rows() == 1){
            return $result->row(0)->name;
             }else{
                 return false;
             }
    }
    public function girls_reports_excel_year($year,$id){
        $this->db->select("girls_reports.id,girls.name,girls_reports.purpose,girls.image,girls_reports.entry_time,girls_reports.exit_time,girls_reports.date");
        $this->db->from("girls");
        $this->db->join('girls_reports', 'girls.id = girls_reports.girl_id','inner');
       $this->db->where('girls_reports.girl_id',$id);
       $this->db->where('YEAR(date)',$year);
    
        return $result= $this->db->get();
    
    }
    public function girls_reports_excel_custom($date1,$date2,$id){
        $this->db->select("girls_reports.id,girls.name,girls_reports.purpose,girls.image,girls_reports.entry_time,girls_reports.exit_time,girls_reports.date");
        $this->db->from("girls");
        $this->db->join('girls_reports', 'girls.id = girls_reports.girl_id','inner');
       $this->db->where('girls_reports.girl_id',$id);
       $this->db->where("DATE(date) BETWEEN '$date1' AND '$date2'");
    
        return $result= $this->db->get();
    }
    public function girls_reports_excel_month($month,$id){
        $this->db->select("girls_reports.id,girls.name,girls_reports.purpose,girls.image,girls_reports.entry_time,girls_reports.exit_time,girls_reports.date");
        $this->db->from("girls");
        $this->db->join('girls_reports', 'girls.id = girls_reports.girl_id','inner');
       $this->db->where('girls_reports.girl_id',$id);
       $this->db->where('month(date)',$month);
    
        return $result= $this->db->get();
    }
public function girl_record($rollno){
$this->db->where('rollno',$rollno);
return $this->db->get($this->user_table)->result_array();
}
public function girls_reports_excel_date($date,$id){
    $this->db->select("girls_reports.id,girls.name,girls_reports.purpose,girls.image,girls_reports.entry_time,girls_reports.exit_time,girls_reports.date");
    $this->db->from("girls");
    $this->db->join('girls_reports', 'girls.id = girls_reports.girl_id','inner');
   $this->db->where('girls_reports.girl_id',$id);
   $this->db->where('DATE(date)',$date);
    return $result= $this->db->get();
}
public function smscheck(){
    return $this->db->get('girls')->result_array();
 }
 public function girls_contact($contact){
    $this->db->where('contact',$contact);
    return $result= $this->db->get('girls')->result_array();
    }
public function fetch(){
    $this->db->select("*");
    $this->db->from($this->user_table);
    // if($query != '')
    // {
    //     $this->db->like('name', $query);
    //     $this->db->or_like('email', $query);
    //     $this->db->or_like('contact', $query);
    //     $this->db->or_like('rollno', $query);
    // }
    $this->db->order_by('id', 'DESC');
    // $this->db->limit($recordsperpage,$start_form); 
    return $this->db->get()->result_array();    
}
public function entry_time($entry_time,$id){
    $data = array(
        'girl_id' => $id,
        'entry_time' => $entry_time
);
$this->db->insert('girls_reports', $data);
    return  $rows=$this->db->affected_rows();
}
public function girls_reports_custom($id,$date1,$date2){
    $this->db->where('girl_id',$id);
    $this->db->where("DATE(date) BETWEEN '$date1' AND '$date2'");
    return  $this->db->get('girls_reports')->result_array();
}
public function girls_reports_month($id,$month){
    $this->db->where('girl_id',$id);
    $this->db->Where('MONTH(date)',$month);
    return  $this->db->get('girls_reports')->result_array();
}
public function girls_reports_year($id,$year){
    $this->db->where('girl_id',$id);
    $this->db->Where('YEAR(date)',$year);
    return  $this->db->get('girls_reports')->result_array();
}
public function girls_reports_date($id,$date){
    $this->db->where('girl_id',$id);
    $this->db->Where('DATE(date)',$date);
    return  $this->db->get('girls_reports')->result_array();
}
public function girls_reports_fetch($id,$start_form,$recordsperpage){
    $this->db->where('girl_id',$id);
    $this->db->limit($recordsperpage,$start_form); 
  return  $this->db->get('girls_reports')->result_array();
}
public function pagination_reports($id,$record_per_page){
    $this->db->where('girl_id',$id);
    $this->db->order_by('id', 'DESC');
    $result=$this->db->get('girls_reports');
    $total_records = $result->num_rows();  
    $total_pages=ceil($total_records/$record_per_page);
    return $total_pages;
}
public function exit_time($exit_time,$id,$purpose){
// $ia=  $this->db->query("SELECT * FROM `girls_reports` WHERE entry_time = '00:00:00' AND girl_id = '$id'");
// if($ia){
//     return false;
// }else{
$abc=$this->db->query("SELECT * FROM `girls_reports` WHERE entry_time = '00:00:00' AND girl_id='$id' ")->result_array();
    if(!empty($abc)){
    return false;
    }else{
        $data = array(
            'girl_id' => $id,
            'exit_time' => $exit_time,
            'purpose'=>$purpose
    );
    $this->db->insert('girls_reports', $data);
        return true;
    }
}
}