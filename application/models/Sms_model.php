<?php
Class Sms_model extends CI_Model{

        public function message_girls(){
            $result = $this->db->get('girls');
           return $result->result_array();
          
        }
        public function message_staff(){
            $result = $this->db->get('staff');
           return $result->result_array();
        }
        public function message_shops(){
        $result = $this->db->get('shops');
        return $result->result_array();
    }
        public function message_template(){
            $result = $this->db->get('settings');
         if($result->num_rows() >= 1){
        return $result->row(2)->value;
         }else{
             return false;
         }
        }
        public function settings_girls_sms_update($girls){

            $result = $this->db->query("UPDATE settings SET value = '$girls' WHERE id = '1'");
                return $this->db->affected_rows();     
        }
        public function settings_shops_sms_update($shops){
            $result = $this->db->query("UPDATE settings SET value = '$shops' WHERE id = '2'");

                return $this->db->affected_rows();
            
        }
        public function settings_template_update($template){
                $result = $this->db->query("UPDATE settings SET value = '$template' WHERE id = '3'");
   
                return $this->db->affected_rows();
            
            }
        public function sms_time_shops(){
            $result = $this->db->get('settings');
            if($result->num_rows() >= 1){
           return $result->row(1)->value;
            }else{
                return false;
            }
        }
        public function exit_sms(){
            //  SELECT * FROM `girls_reports` WHERE exit_time = "00:00:00" AND date(date) = 
            $this->db->distinct("girls_reports.id,girls.status,girls.contact,girls.name,girls_reports.purpose,girls.image,girls_reports.entry_time,girls_reports.exit_time,girls_reports.date");
            $this->db->from("girls");
            $this->db->join('girls_reports', 'girls.id = girls_reports.girl_id','inner');
           $query = $this->db->where('girls_reports.entry_time','00:00:00');
        //    $date = date('Y-m-d');
        //    $query = $this->db->where('DATE(date)',$date);
          return $this->db->get()->result_array();
          //   return $result= $this->db->get();
          //     $query = $this->db->where('exit_time','00:00:00');
          //     $date = date('Y-m-d');
          //     $query = $this->db->where('DATE(date)',$date);
          //     $result = $this->db->get('girls_reports');
          //     return $result->result_array();
          }
          public function sms_time_girls(){

            $result = $this->db->get('settings');
        
         if($result->num_rows() >= 1){
        return $result->row(0)->value;
         }else{
             return false;
         }
        }
        public function messages(){
            $result = $this->db->get('notify');
         if($result->num_rows() >= 1){
        return $result->row(0)->messages;
         }else{
             return false;
         }
        }
        public function messages_update($messages){
            $result = $this->db->query("UPDATE notify SET messages = '$messages' WHERE id='1'");
            return  $rows=$this->db->affected_rows();
        }



}

?>