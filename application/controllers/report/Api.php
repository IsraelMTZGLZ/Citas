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
    function report_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('tb_Report',array('idReport'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('tb_Report'),
            );
        }
        $this->response($response,200);
    }
    function report_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 12){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(

                      "name_Report"=>"The name must have between 4 and 60 characters in length",
                      "description_Report"=>"The lastname must have between 4 and 60 characters in length",
                      "Person"=>"The gender must be F for Female or M for Male"

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
            $this->form_validation->set_rules('name_Report','name_Report','required|max_length[160]|min_length[3]');
            $this->form_validation->set_rules('description_Report','description_Report','required|max_length[160]|min_length[3]');
            $this->form_validation->set_rules('Person','Person','callback_check_person');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=> 'Too many data received',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "name_Report "=>$this->post('name_Report'),
                   "description_Report"=>$this->post('description_Report'),
                   "fk_Person"=>$this->post('Person'),
               );
               $response = $this->DAO->insertData('tb_Report',$data);

             }
        }

        $this->response($response,200);
    }

    function report_put($id=null){
        $data = $this->put();
        $Eixts = $this->DAO->selectEntity('tb_Report',array('idReport'=>$id),TRUE);
        if($Eixts){
          if(count($data) == 0 || count($data) > 19){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                  "data"=>null,
                  "validations"=>array(
                    "name_Report"=>"The name must have between 4 and 60 characters in length",
                    "description_Report"=>"The lastname must have between 4 and 60 characters in length",
                    "Person"=>"The gender must be F for Female or M for Male"

                  )
              );
          }else{

              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('name_Report','name_Report','required|max_length[160]|min_length[3]');
              $this->form_validation->set_rules('description_Report','description_Report','required|max_length[160]|min_length[3]');
              $this->form_validation->set_rules('Person','Person','callback_check_person');



             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'Too many data received',
                      "data"=>null,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

               $data = array(
                 "name_Report "=>$this->put('name_Report'),
                 "description_Report"=>$this->put('description_Report'),
                 "fk_Person"=>$this->put('Person'),

               );

               $response = $this->DAO->updateData('tb_Report',$data,array('idReport'=>$id));



               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>null,
            );
        }


        $this->response($response,200);
    }



    public function report_delete(){
          $id = $this->get('id');
      if ($id) {
        $groupIdExists = $this->DAO->selectEntity('tb_Report',array('idReport'=>$id),TRUE);

        if($groupIdExists){
          $response = $this->DAO->deleteData('tb_Report',array('idReport'=>$id));
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

    function check_person($str){
      if ( strlen($str)>=1) {
        $personExists = $this->DAO->selectEntity('tb_Person',array('idPerson'=>$str),TRUE);
        if ($personExists) {
          return TRUE;
        } else {
        $this->form_validation->set_message('check_person','The {field} does not exist.');

          return FALSE;
        }

      } else {
        $this->form_validation->set_message('check_person','The {field}1 character');
          return FALSE;
      }
    }


}
