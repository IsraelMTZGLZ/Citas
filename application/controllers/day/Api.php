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
    function day_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('tb_Day',array('idDay'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('tb_Day'),
            );
        }
        $this->response($response,200);
    }
    function day_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 12){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(

                      "name_Day"=>"The name must have between 4 and 60 characters in length",
                      "status_Day"=>"The lastname must have between 4 and 60 characters in length"


                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('name_Day','name_Day','callback_check_name');
            $this->form_validation->set_rules('status_Day','status_Day','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=> 'Too many data received',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "name_Day "=>$this->post('name_Day'),
                   "status_Day"=>$this->post('status_Day')
               );
               $response = $this->DAO->insertData('tb_Day',$data);

             }
        }

        $this->response($response,200);
    }

    function day_put($id=null){
        $data = $this->put();
        $Eixts = $this->DAO->selectEntity('tb_Day',array('idDay'=>$id),TRUE);
        if($Eixts){
          if(count($data) == 0 || count($data) > 19){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                  "data"=>null,
                  "validations"=>array(
                    "name_Day"=>"The name must have between 4 and 60 characters in length",
                    "status_Day"=>"The lastname must have between 4 and 60 characters in length"

                  )
              );
          }else{

              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('name_Day','name_Day','callback_check_nameU');
              $this->form_validation->set_rules('status_Day','status_Day','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'Too many data received',
                      "data"=>null,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

               $data = array(
                 "name_Day "=>$this->put('name_Day'),
                 "status_Day"=>$this->put('status_Day')

               );

               $response = $this->DAO->updateData('tb_Day',$data,array('idDay'=>$id));



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



    public function day_delete(){
          $id = $this->get('id');
      if ($id) {
        $groupIdExists = $this->DAO->selectEntity('tb_Day',array('idDay'=>$id),TRUE);

        if($groupIdExists){
          $response = $this->DAO->deleteData('tb_Day',array('idDay'=>$id));
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

      $this->response($response,$response['status_code']);
    }



    /**exta validations**/

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

    function check_name($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Day',array('name_Day'=>$str),TRUE);
        if (!$empresaExists) {
          return TRUE;
        } else {
        $this->form_validation->set_message('check_name','The {field} already exists.');

          return FALSE;
        }

      } else {
        $this->form_validation->set_message('check_name','The {field} must be 10 characters in length');
          return FALSE;
      }
    }
    function check_nameU($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Day',array('name_Day'=>$str),TRUE);
        if (!$empresaExists) {

          return TRUE;
        } else {
          return TRUE;
        }

      } else {
        $this->form_validation->set_message('check_nameU','The {field} must be 10 characters in length');
          return FALSE;
      }
    }

}
