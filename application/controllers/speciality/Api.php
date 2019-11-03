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
    function speciality_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('tb_Speciality',array('idSpeciality'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('tb_Speciality'),
            );
        }
        $this->response($response,200);
    }
    function speciality_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 12){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(

                      "name_Speciality"=>"The name must have between 4 and 60 characters in length",
                      "description_Speciality"=>"The lastname must have between 4 and 60 characters in length",
                      "status_Speciality"=>"The gender must be F for Female or M for Male"


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
            $this->form_validation->set_rules('name_Speciality','name_Speciality','callback_check_name');
            $this->form_validation->set_rules('description_Speciality','description_Speciality','required|max_length[160]|min_length[3]');
            $this->form_validation->set_rules('status_Speciality','status_Speciality','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=> 'Too many data received',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "name_Speciality "=>$this->post('name_Speciality'),
                   "description_Speciality"=>$this->post('description_Speciality'),
                   "status_Speciality"=>$this->post('status_Speciality')
               );
               $response = $this->DAO->insertData('tb_Speciality',$data);

             }
        }

        $this->response($response,200);
    }

    function speciality_put($id=null){
        $data = $this->put();
        $Eixts = $this->DAO->selectEntity('tb_Speciality',array('idSpeciality'=>$id),TRUE);
        if($Eixts){
          if(count($data) == 0 || count($data) > 19){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                  "data"=>null,
                  "validations"=>array(
                    "name_Speciality"=>"The name must have between 4 and 60 characters in length",
                    "description_Speciality"=>"The description must have between 4 and 60 characters in length",
                    "status_Speciality"=>"The gender must be F for Female or M for Male"


                  )
              );
          }else{

              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('name_Speciality','name_Speciality','callback_check_nameU');
              $this->form_validation->set_rules('description_Speciality','description_Speciality','required|max_length[160]|min_length[3]');
              $this->form_validation->set_rules('status_Speciality','status_Speciality','required');


             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'Too many data received',
                      "data"=>null,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{
                 $data = array(
                   "name_Speciality "=>$this->put('name_Speciality'),
                   "description_Speciality"=>$this->put('description_Speciality'),
                   "status_Speciality"=>$this->put('status_Speciality')

                 );

                 $response = $this->DAO->updateData('tb_Speciality',$data,array('idSpeciality'=>$id));


               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "Any does not exist  ",
            "data"=>null,
            );
        }


        $this->response($response,200);
    }



    public function speciality_delete(){
          $id = $this->get('id');
      if ($id) {
        $groupIdExists = $this->DAO->selectEntity('tb_Speciality',array('idSpeciality'=>$id),TRUE);

        if($groupIdExists){
          $response = $this->DAO->deleteData('tb_Speciality',array('idSpeciality'=>$id));
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
        $empresaExists = $this->DAO->selectEntity('tb_Speciality',array('name_Speciality'=>$str),TRUE);
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
        $empresaExists = $this->DAO->selectEntity('tb_Speciality',array('name_Speciality'=>$str),TRUE);
        if (!$empresaExists) {

          return TRUE;
        } else {
          return TRUE;
        }

      } else {
        $this->form_validation->set_message('check_nameU','The {field} is required');
          return FALSE;
      }
    }

}
