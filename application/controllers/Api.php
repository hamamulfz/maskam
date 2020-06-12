<?php
// import library dari REST_Controller
require APPPATH . 'libraries/REST_Controller.php';
// extends class dari REST_Controller

class Api extends REST_Controller{
// constructor
    public function __construct(){
        parent::__construct();
    }
    
    public function index_get(){
        $response['status']=200;
        $response['error']=false;
        $response['message']='Hai from response';
        $this->response($response);
    }

    public function empty_response(){
        $response['status']=502;
        $response['error']=true;
        $response['message']='Field tidak boleh kosong';
        return $response;
    }

    public function cek_post(){

        $this->load->model('Scan_model');
        $code = $this->post('code');
        if(empty($this->post('code')) ){
        $this->response($this->empty_response());
        }
        $tgl=date('Y-m-d');
        $jam_klr=date('h:i:s');
        $jam_msk=date('h:i:s');
        $cek_id =  $this->Scan_model->cek_id($code);

        $this->load->model('GenBar_model');
        $car = $this->GenBar_model->getShow_query($code);
        foreach($car->result() as $row){
        $nama = $row->nama_karyawan;
        }

        // $cek_abs_klr = $this->Scan_model->kar_abs_klr($code,$tgl);
        if (!$cek_id){
            $response['status']=404;
            $response['error']=true;
            $response['message']= 'Data tidak ditemukan , harap memasukan QR CODE sesuai NIK masing-masing!';
            $this->response($response);
        } else {
            $result = $this->Scan_model->kar_abs_msk($code);
            $response['status']=200;
            $response['error']=false;
            $response['message']= $nama . " (" . $code .  ") telah masuk";
            $this->response($response);
        }

                
    }

}
?>