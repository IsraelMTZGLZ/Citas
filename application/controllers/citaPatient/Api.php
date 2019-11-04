<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('DAO');
    }
    function citaPatient_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('tb_CitaPatient',array('idCitaPatient'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('tb_CitaPatient'),
            );
        }
        $this->response($response,200);
    }
    function citaPatient_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 12){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(

                  "hour"=>"Time format is requiret",
                  "date"=>"Date format is requiret",
                  "description"=>"The description is required",
                  "status"=>"The status must be Active or Inactive",
                  "doctor"=>"The doctor required",
                  "day"=>"The day required",
                  "patient"=>"The Patient required"


                )
            );
        }else{
          //callback_check_Cname
          //callback_check_Clastname
          //callback_check_Cstatus
          //required|max_length[150]
          //required|max_length[160]|min_length[4]
          //required|exact_length[1]|callback_gender_valid
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('hour','hour','required');
            $this->form_validation->set_rules('date','date','required');
            $this->form_validation->set_rules('status','status','required');
            $this->form_validation->set_rules('description','description','required');
            $this->form_validation->set_rules('doctor','doctor','callback_check_doctor');
            $this->form_validation->set_rules('day','day','callback_check_day');
            $this->form_validation->set_rules('patient','patient','callback_check_patient');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=> 'Too many data received',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "hour_CitaPatient"=>$this->post('hour'),
                   "date_CitaPatient"=>$this->post('date'),
                   "status_CitaPatient"=>$this->post('status'),
                   "description_CitaPatient"=>$this->post('description'),
                   "fk_Doctor"=>$this->post('doctor'),
                   "fk_Day"=>$this->post('day'),
                   "fk_Patient"=>$this->post('patient')
               );
               $response = $this->DAO->insertData('tb_CitaPatient',$data);

             }
        }

        $this->response($response,200);
    }

    function citaPatient_put($id=null){
        $data = $this->put();
        $Eixts = $this->DAO->selectEntity('tb_CitaPatient',array('idCitaPatient'=>$id),TRUE);
        if($Eixts){
          if(count($data) == 0 || count($data) > 19){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                  "data"=>null,
                  "validations"=>array(
                    "hour"=>"Time format is requiret",
                    "date"=>"Date format is requiret",
                    "description"=>"The description is required",
                    "status"=>"The status must be Active or Inactive",
                    "doctor"=>"The doctor required",
                    "day"=>"The day required",
                    "patient"=>"The Patient required"

                  )
              );
          }else{

              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('hour','hour','required');
              $this->form_validation->set_rules('date','date','required');
              $this->form_validation->set_rules('status','status','required');
              $this->form_validation->set_rules('description','description','required');
              $this->form_validation->set_rules('doctor','doctor','callback_check_doctor');
              $this->form_validation->set_rules('day','day','callback_check_day');
              $this->form_validation->set_rules('patient','patient','callback_check_patient');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'Too many data received',
                      "data"=>null,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

               $data = array(
                 "hour_CitaPatient"=>$this->put('hour'),
                 "date_CitaPatient"=>$this->put('date'),
                 "status_CitaPatient"=>$this->put('status'),
                 "description_CitaPatient"=>$this->put('description'),
                 "fk_Doctor"=>$this->put('doctor'),
                 "fk_Day"=>$this->put('day'),
                 "fk_Patient"=>$this->put('patient')

               );

               $response = $this->DAO->updateData('tb_CitaPatient',$data,array('idCitaPatient'=>$id));



               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "no",
            "data"=>null,
            );
        }


        $this->response($response,200);
    }



    public function citaPatient_delete(){
          $id = $this->get('id');
      if ($id) {
        $groupIdExists = $this->DAO->selectEntity('tb_CitaPatient',array('idCitaPatient'=>$id),TRUE);

        if($groupIdExists){
          $response = $this->DAO->deleteData('tb_CitaPatient',array('idCitaPatient'=>$id));
        }else{
          $response = array(
            "status"=>"error",
            "status_code"=>409,
            "message"=>"Id doesn't exists",
            "validations"=>null,
            "data"=>null
          );
        }
      } else {
        $response = array(
          "status"=>"error",
          "status_code"=>409,
          "message"=>"Id wasn't sent",
          "validations"=>array(
            "id"=>"Required (get)",

          ),
          "data"=>null
        );
      }

      $this->response($response,200);
    }



    /**exta validations**/



    function check_doctor($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Doctor',array('idDoctor'=>$str),TRUE);
        if ($empresaExists) {
          return TRUE;
        } else {
        $this->form_validation->set_message('check_doctor','The {field} does not exist.');

          return FALSE;
        }

      } else {
        $this->form_validation->set_message('check_doctor','The {field} must be 10 characters in length');
          return FALSE;
      }
    }

    function check_day($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Day',array('idDay'=>$str),TRUE);
        if ($empresaExists) {
          return TRUE;
        } else {
        $this->form_validation->set_message('check_day','The {field} does not exist.');

          return FALSE;
        }

      } else {
        $this->form_validation->set_message('check_day','The {field} must be 10 characters in length');
          return FALSE;
      }
    }

    function check_patient($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Patient',array('idPatient'=>$str),TRUE);
        if ($empresaExists) {
          return TRUE;
        } else {
        $this->form_validation->set_message('check_patient','The {field} does not exist.');

          return FALSE;
        }

      } else {
        $this->form_validation->set_message('check_patient','The {field} must be 10 characters in length');
          return FALSE;
      }
    }


    function gender_valid($str){
        if($str){
            if($str=="F" || $str == "M"){
                return true;
            }else{
                $this->form_validation->set_message('gender_valid','The {field} must be F or M');
                return false;
            }
        }else{
            $this->form_validation->set_message('gender_valid','The {field} must be F or M');
            return false;
        }
    }

    function check_email($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Users',array('email_User'=>$str),TRUE);
        if (!$empresaExists) {
          return TRUE;
        } else {
        $this->form_validation->set_message('check_email','The {field} already exists.');

          return FALSE;
        }

      } else {
        $this->form_validation->set_message('check_email','The {field} must be 10 characters in length');
          return FALSE;
      }
    }
    function check_emailU($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Users',array('email_User'=>$str),TRUE);
        if (!$empresaExists) {

          return TRUE;
        } else {
          return TRUE;
        }

      } else {
        $this->form_validation->set_message('check_email','The {field} must be 10 characters in length');
          return FALSE;
      }
    }

}
