<?php

Class Shops_model extends CI_Model{

protected $user_table='shops';
public function shops_reports_delete_shops_id($id){
    $delete = $this->db->where('shop_id',$id);
    $result = $this->db->delete('shops_reports');
    return  $rows=$this->db->affected_rows();
}
public function checkin($entry_time,$id){
    //   $id=  $this->db->select('id')->where('girl_id',$id)->order_by('id','desc')->limit(1)->get('girls_reports')->row('id');
    $abc=$this->db->query("SELECT * FROM `shops_reports` WHERE exit_time = '00:00:00' AND shop_id='$id' ")->result_array();
    if(!empty($abc)){
    return false;
    }else{
        $data = array(
            'shop_id' => $id,
            'entry_time' => $entry_time,
    );
    $this->db->insert('shops_reports', $data);
        return true;
    }
    }
    public function status_check($id){
        $query = $this->db->where('shop_id',$id);
        $result = $this->db->get($this->user_table);
        if($result->num_rows() == 1){
            return $result->row(0)->status;
             }else{
                 return false;
             }
    }
    
    public function exit_time($exit_time,$id){


        //   $id=  $this->db->select('id')->where('girl_id',$id)->order_by('id','desc')->limit(1)->get('girls_reports')->row('id');
  $this->db->set('exit_time', $exit_time);
  $this->db->where('shop_id',$id);
  $this->db->where('exit_time','00:00:00');
  $this->db->update('shops_reports'); // gives UPDATE mytable SET field = field+1 WHERE id = 2
return $this->db->affected_rows();
}
public function shops_record($contact){
    $this->db->where('shop_contact',$contact);
    return $this->db->get($this->user_table)->result_array();
    }
public function update_status($id,$status){
    date_default_timezone_set('Asia/Karachi');
    $abc=$this->db->query("SELECT * FROM `shops_reports` WHERE exit_time = '00:00:00' AND shop_id='$id' ")->result_array();
    $this->db->where('shop_id', $id);
    if($abc){
        $data1 = [
            'status'=>$status
            ];
            $this->db->update($this->user_table, $data1);
            $data2=[
                'exit_time'=>$exit_time=date("H:i:s")
            ];
            $this->db->update('shops_reports',$data1);
}else{
$data = [
    'status'=>$status
 ];
 $this->db->update($this->user_table,$data);

}  
    return $this->db->affected_rows();
}
public function insert_shops(array $data){
    $this->db->insert($this->user_table,$data);
    return $this->db->insert_id();
    }
    public function update_show($id){
        $query = $this->db->where('shop_id',$id);
       return $result= $this->db->get($this->user_table)->result_array();
      }
      public function updateshops($id,array $data){
        $this->db->where('shop_id', $id);
        $this->db->update($this->user_table, $data);
        return $this->db->affected_rows();
    }
public function delete($id){
    $delete = $this->db->where('shop_id',$id);
    $result = $this->db->delete($this->user_table);
    return  $rows=$this->db->affected_rows();
}
  public function delimgcheck($id){
    $query = $this->db->where('shop_id',$id);
    $result = $this->db->get($this->user_table);
    if($result->num_rows() == 1){
        return $result->row(0)->image;
         }else{
             return false;
         }
}
public function namecheck($id){
    $query = $this->db->where('shop_id',$id);
    $result = $this->db->get($this->user_table);
    if($result->num_rows() == 1){
        return $result->row(0)->shop_name;
         }else{
             return false;
         }
}
public function shops_reports_excel_year($year,$id){
    $this->db->select("shops_reports.id,shops.shop_name,shops_reports.purpose,shops.image,shops_reports.entry_time,shops_reports.exit_time,shops_reports.date");
    $this->db->from("shops");
    $this->db->join('shops_reports', 'shops.shop_id = shops_reports.shop_id','inner');
   $this->db->where('shops_reports.shop_id',$id);
   $this->db->where('YEAR(date)',$year);

    return $result= $this->db->get();

}
public function shops_reports_excel_custom($date1,$date2,$id){
    $this->db->select("shops_reports.id,shops.shop_name,shops_reports.purpose,shops.image,shops_reports.entry_time,shops_reports.exit_time,shops_reports.date");
    $this->db->from("shops");
    $this->db->join('shops_reports', 'shops.shop_id = shops_reports.shop_id','inner');
   $this->db->where('shops_reports.girl_id',$id);
   $this->db->where("DATE(date) BETWEEN '$date1' AND '$date2'");

    return $result= $this->db->get();
}
public function shops_reports_excel_date($date,$id){
    $this->db->select("shops_reports.id,shops.shop_name,girls_reports.purpose,shops.image,girls_reports.entry_time,shops_reports.exit_time,shops_reports.date");
    $this->db->from("shops");
    $this->db->join('shops_reports', 'shops.shop_id = shops_reports.shop_id','inner');
   $this->db->where('shops_reports.shop_id',$id);
   $this->db->where('DATE(date)',$date);
    return $result= $this->db->get();
}
public function shops_reports_excel_month($month,$id){
    $this->db->select("shops_reports.id,shops.shop_name,shops_reports.purpose,shops.image,shops_reports.entry_time,shops_reports.exit_time,shops_reports.date");
    $this->db->from("shops");
    $this->db->join('shops_reports', 'shop.shop_id = shops_reports.shop_id','inner');
   $this->db->where('shops_reports.shop_id',$id);
   $this->db->where('month(date)',$month);

    return $result= $this->db->get();
}
public function shops_reports_custom($id,$date1,$date2){
    $this->db->where('shop_id',$id);
    $this->db->where("DATE(date) BETWEEN '$date1' AND '$date2'");
    return  $this->db->get('shops_reports')->result_array();
}
public function shops_reports_month($id,$month){
    $this->db->where('shop_id',$id);
    $this->db->Where('MONTH(date)',$month);
    return  $this->db->get('shops_reports')->result_array();
}
public function shops_reports_year($id,$year){
    $this->db->where('shop_id',$id);
    $this->db->Where('YEAR(date)',$year);
    return  $this->db->get('shops_reports')->result_array();
}
public function shops_reports_date($id,$date){
    $this->db->where('shop_id',$id);
    $this->db->Where('DATE(date)',$date);
    return  $this->db->get('shops_reports')->result_array();
}

public function deletereport($id){
    $delete = $this->db->where('id',$id);
    $result = $this->db->delete('shops_reports');
    return  $rows=$this->db->affected_rows();
}
public function shops_reports_fetch($id,$start_form,$recordsperpage){
    $this->db->where('shop_id',$id);
    $this->db->limit($recordsperpage,$start_form); 
  return  $this->db->get('shops_reports')->result_array();
}
public function pagination_reports($id,$record_per_page){
    $this->db->where('shop_id',$id);
    $this->db->order_by('shop_id', 'DESC');
    $result=$this->db->get('shops_reports');
    $total_records = $result->num_rows();  
    $total_pages=ceil($total_records/$record_per_page);
    return $total_pages;
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
    $this->db->order_by('shop_id', 'DESC');
    // $this->db->limit($recordsperpage,$start_form); 
    return $this->db->get()->result_array();    
}

public function qrcodecheck($id){
    $query = $this->db->where('shop_id',$id);
    $result = $this->db->get($this->user_table);
    if($result->num_rows() == 1){
    return $result->row(0)->qrcode;
        }else{
        return false;
             }
    }
function fetch_data($query,$start_form,$recordsperpage)
	{
		$this->db->select("*");
		$this->db->from($this->user_table);
		if($query != '')
		{
			$this->db->like('shop_name', $query);
			$this->db->or_like('shop_contact', $query);
			$this->db->or_like('prop', $query);
		}
		$this->db->order_by('shop_id', 'DESC');
		$this->db->limit($recordsperpage,$start_form); 
		return $this->db->get()->result_array();
    }
    public function pagination($record_per_page){
		$this->db->order_by('shop_id', 'DESC');
		$result=$this->db->get($this->user_table);
		$total_records = $result->num_rows();  
		$total_pages=ceil($total_records/$record_per_page);
	  return $total_pages;
      }



}