<?php 

class User extends CI_Model {
    public function __construct() {
        $this->load->database();

    }

    public function create_user() {
        $data = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password'))
        );
        return $this->db->insert('user', $data);
    }

    public function read_user() {
        $query = $this->db->get('user');
        return $query->result();
    }
    var $order_column = array("id", "username", "email", null, null); 
    
    function make_query()  
      {  
           $this->db->select();  
           $this->db->from('user');  
           if(isset($_POST["search"]["value"]))  
           {  
                $this->db->like("id", $_POST["search"]["value"]);  
                $this->db->or_like("username", $_POST["search"]["value"]);  
                $this->db->or_like("email", $_POST["search"]["value"]);  
           }  
           if(isset($_POST["order"]))  
           {  
                $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
           }
           else  
           {  
                $this->db->order_by('id', 'DESC');  
           }  
      } 

    function make_datatables(){  
        $this->make_query();  
        if($_POST["length"] != -1)  
        {  
                $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
   }

   public function get_total() {
        $query = $this->db->get('user');
        return $query->num_rows();
   }

    public function get_filtered() {
        $this->make_query();  
        $query = $this->db->get();  
        return $query->num_rows();
    }

    public function find_user($id) {
        // $this->db->select();
        // $this->db->from('user');
        // $this->db->where('id',$id);

        // $query = $this->input->get()->result_array();
        // return $query;

        return $this->db->get_where('user', array('id' => $id))->row();
    }

    public function edit_user($id) {
        $data = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password'))
        );
        $this->db->where('id',$id);
        return $this->db->update('user', $data);
    }

    public function get_edit($id) {
        $this->db->select();
        $this->db->from('user');
        $this->db->where('id',$id);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function delete_user($id) {
       return $this->db->delete('user', array('id' => $id));
    }

}