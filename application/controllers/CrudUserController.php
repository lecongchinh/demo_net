<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CrudUserController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User');
        $this->data = new User();
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
    }

    private function validateForm() {
        $rules = array(
            array(
              'field' => 'username',
              'label' => 'User name',
              'rules' => 'required|min_length[4]|max_length[20]|is_unique[user.username]'
            ),
            array(
              'field' => 'email',
              'label' => 'email',
              'rules' => 'required|valid_email|is_unique[user.email]'
            ),

            array(
              'field' => 'password',
              'label' => 'password',
              'rules' => 'required|min_length[4]|max_length[20]'
            )
          );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == false) {
            if(!empty($rules)) foreach ($rules as $item){
                if(!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
            }
            $mess['mess'] = $valid;
            echo json_encode($mess);
            exit();
        }
        
    }

    public function index() {
        $data['data'] = $this->data->read_user();
        $this->load->view('header');
        $this->load->view('readUser', $data);
        $this->load->view('footer');
    }

    public function get_create_user() {
        $this->load->view('createUser');
    }
    public function post_create_user() {
        $data = $this->input->post();
        $status = false;        
        $this->validateForm();
        $reponsive = $this->data->create_user();
        if($reponsive!=false){
            $data_mess = array(
                'status' => true,
                'mess'=> 'Thanh cong'
            );
        }else{
            $data_mess = array(
                'mess'=> 'loi vui long thu lai'
            );
        }
        echo json_encode($data_mess);
    }
    public function delete_user($id) {
        $data = $this->input->post();
        $reponsive = $this->data->delete_user($id);
        $status = true;
        if($reponsive!=false){
            $data_mess = array(
                'status' => $status,
                'mess'=> 'Thanh cong'
            );
        }else{
            $data_mess = array(
                'mess'=> 'loi vui long thu lai'
            );
        }
        echo json_encode($data_mess);
    }

    public function get_edit_user($id)
    {
        $data = $this->input->post();
        $reponsive = $this->data->get_edit($id);
        echo json_encode($reponsive);
    }

    public function post_edit_user($id) {
        $this->validateForm();
        $data = $this->input->post();
        $responsive = $this->data->edit_user($id);
        $status = true;
        if(!empty($responsive)){
            $data_mess = array(
                'status' => $status,
                'mess'=> 'Thanh cong'
            );
        }else{
            $data_mess = array(
                'mess'=> 'loi vui long thu lai'
            );
        }
        echo json_encode($data_mess);
    }

    public function load_list_user() {
        $data['data'] = $this->data->read_user();
        $this->load->view('readUser', $data);        
    }

    public function fetch_user() {
        $length = $this->input->post('length');
        $data_user = $this->data->make_datatables();
        $start = $this->input->post('start');
        $fetch_data = array();
        $param = 0;
        
        foreach($data_user as $row) {
            $sub_array = array();
            $sub_array[] = $row->id;
            $sub_array[] = $row->username;
            $sub_array[] = $row->email;
            $sub_array[] = '<button onclick="edit_user('.$row->id.')" name="update" id="'.$row->id.'" class="btn btn-warning update">Update</button>';  
            $sub_array[] = '<button onclick="delete_user('.$row->id.')" name="delete" id="'.$row->id.'" class="btn btn-danger">Delete</button>';
            $fetch_data[] = $sub_array;
        }
        if(($this->data->get_total() - $start) > $length) {
            $end = $start + 9;
        } else {
            $end = $this->data->get_total();

        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->data->get_total(),
            "recordsFiltered" => $this->data->get_filtered(),
            "data" => $fetch_data
        );
        echo json_encode($output);
    }

    public function load_datatable() {
        $this->load->view('header');
        $this->load->view('welcome_message');
        $this->load->view('footer');
    }

}
?>