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
    function name_Service_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('tb_Name',array('id_Table'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('tb_Name'),
            );
        }
        $this->response($response,200);
    }
    function name_Service_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 12){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(

                    "field_1"=>"The name must have between 4 and 60 characters in length",
                    "field_2"=>"The lastname must have between 4 and 60 characters in length",
                    "field_3"=>"The gender must be F for Female or M for Male",
                    "field_4"=>"The curp must have between 4 and 60 characters in length",
                    "field_5"=>"The email must be unique and valid Email",
                    "field_6"=>"The email must have between 4 and 60 characters in length",
                    "field_7"=>"The email must be unique and valid Email",
                    "field_8"=>"The email must have between 4 and 60 characters in length",
                    "field_9"=>"The email must be unique and valid Email",
                    "field_10"=>"The email must have between 4 and 60 characters in length"
                    "field_11"=>"The email must have between 4 and 60 characters in length"


                )
            );
        }else{
          //callback_check_Cfield_1
          //callback_check_Cfield_2
          //callback_check_Cfield_3
          //required|max_length[150]
          //required|max_length[160]|min_length[4]
          //required|exact_length[1]|callback_gender_valid
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('field_1','field_1','');
            $this->form_validation->set_rules('field_2','field_2','');
            $this->form_validation->set_rules('field_3','field_3','');
            $this->form_validation->set_rules('field_4','field_4','');
            $this->form_validation->set_rules('field_5','field_5','');
            $this->form_validation->set_rules('field_6','field_6','');
            $this->form_validation->set_rules('field_7','field_7','');
            $this->form_validation->set_rules('field_8','field_8','');
            $this->form_validation->set_rules('field_9','field_10','');
            $this->form_validation->set_rules('field_11','field_11','');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=> 'Too many data received',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

                $this->load->library('bcrypt');
                $this->db->trans_begin();

                $person = array(
                    "nombrePersona"=>$this->post('name'),
                    "apellidosPersona "=>$this->post('lastname'),
                    "generoPersona"=>$this->post('gender'),
                    "telPersona"=>$this->post('tel')
                );


                $personResponse = $this->DAO->saveOrUpdateItem('Personas',$person,null,true);
                if($personResponse['status']=="success"){
                    $user = array(
                        "emailUser"=>$this->post('email'),
                        "passwordUser"=>"123",
                        "typeUser "=>"Administrador",
                        "addressUser "=>$this->post('address'),
                        "fkPersona"=>$personResponse['key']
                    );
                    $userResponse = $this->DAO->saveOrUpdateItem('Users',$user,null,true);
                    if($userResponse['status']=="success"){
                        $response = array(
                           "status"=>"success",
                           "message"=>"Professor update successfully",
                           "data"=>null,
                       );

                    }else{
                        $response = array(
                            "status"=>"error",
                            "message"=>  $userResponse['message'],
                            "data"=>null,
                        );
                    }
                    if($this->db->trans_status()==FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
                    }

                }else{
                  $response = array(
                    "status"=>"error",
                    "message"=>  $personResponse['message'],
                    "data"=>null,
                    );
                }

             }
        }

        $this->response($response,200);
    }

    function name_Service_put($id=null){
        $data = $this->put();

        if(count($data) == 0 || count($data) > 19){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                  "field_1"=>"The name must have between 4 and 60 characters in length",
                  "field_2"=>"The lastname must have between 4 and 60 characters in length",
                  "field_3"=>"The gender must be F for Female or M for Male",
                  "field_4"=>"The curp must have between 4 and 60 characters in length",
                  "field_5"=>"The email must be unique and valid Email",
                  "field_6"=>"The email must have between 4 and 60 characters in length",
                  "field_7"=>"The email must be unique and valid Email",
                  "field_8"=>"The email must have between 4 and 60 characters in length",
                  "field_9"=>"The email must be unique and valid Email",
                  "field_10"=>"The email must have between 4 and 60 characters in length"
                  "field_11"=>"The email must have between 4 and 60 characters in length"
                )
            );
        }else{

            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('field_1','field_1','');
            $this->form_validation->set_rules('field_2','field_2','');
            $this->form_validation->set_rules('field_3','field_3','');
            $this->form_validation->set_rules('field_4','field_4','');
            $this->form_validation->set_rules('field_5','field_5','');
            $this->form_validation->set_rules('field_6','field_6','');
            $this->form_validation->set_rules('field_7','field_7','');
            $this->form_validation->set_rules('field_8','field_8','');
            $this->form_validation->set_rules('field_9','field_10','');
            $this->form_validation->set_rules('field_11','field_11','');

           if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=> 'Too many data received',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

                $this->load->library('bcrypt');
                $this->db->trans_begin();

                $person = array(
                    "nombrePersona"=>$this->put('name'),
                    "apellidosPersona "=>$this->put('lastname'),
                    "generoPersona"=>$this->put('gender'),
                    "telPersona"=>$this->put('tel')
                );


                $personResponse = $this->DAO->saveOrUpdateItem('Personas',$person,array('idPersona'=>$id));

                if($personResponse['status']=="success"){
                    $user = array(
                      "emailUser"=>$this->put('email'),
                      "passwordUser"=>"123",
                      "typeUser "=>"Administrador",
                      "addressUser "=>$this->put('address'),
                      "fkPersona"=>$id
                    );
                    $userResponse = $this->DAO->saveOrUpdateItem('Users',$user,array('fkPersona'=>$id));
                    if($userResponse['status']=="success"){
                        $response = array(
                           "status"=>"success",
                           "message"=>"Admin update successfully",
                           "data"=>null,
                       );

                    }else{
                        $response = array(
                            "status"=>"error",
                            "message"=>  $userResponse['message'],
                            "data"=>null,
                        );
                    }
                    if($this->db->trans_status()==FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
                    }

                }else{
                  $response = array(
                    "status"=>"error",
                    "message"=>  $personResponse['message'],
                    "data"=>null,
                    );
                }

             }
        }

        $this->response($response,200);
    }



    function name_Service_delete(){

        $id = $this->get('id');
		if ($id) {
			$clienteExists = $this->DAO->selectEntity('Personas',array('idPersona'=>$id),TRUE);


			if($clienteExists){
      $this->db->trans_begin();

      $Admin = $this->DAO->deleteData('Personas',array('idPersona'=>$id));
         if($Admin['status']=="success"){
             $User = $this->DAO->deleteData('Users',array('idUser'=>$id));
             if($User['status']=="success"){
               $response = array(
                  "status"=>"success",
                  "message"=>"Admin update successfully",
                  "data"=>null,
              );
            }else{
               $response = array(
                   "status"=>"error",
                   "message"=>  $userResponse['message'],
                   "data"=>null,
               );

             }

         }else{
           $response = array(
               "status"=>"error",
               "message"=>  $userResponse['message'],
               "data"=>null,
           );


         }
      if($this->db->trans_status()==FALSE){
          $this->db->trans_rollback();
      }else{
          $this->db->trans_commit();
      }
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
					"id"=>"Required (Id)",

				),
				"data"=>null
			);
		}
		$this->response($response,200);
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

    function check_Cfield1($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('Users',array('emailUser'=>$str),TRUE);
        if (!$empresaExists) {
          return TRUE;
        } else {
        $this->form_validation->set_message('check_Cfield1','The {field} already exists.');

          return FALSE;
        }

      } else {
        $this->form_validation->set_message('check_Cfield1','The {field} must be 10 characters in length');
          return FALSE;
      }
    }

}
