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
    function patient_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('vw_Patient',array('id'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('vw_Patient'),
            );
        }
        $this->response($response,200);
    }
    function patient_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 12){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(

                      "name"=>"The name must have between 4 and 60 characters in length",
                      "lastname"=>"The lastname must have between 4 and 60 characters in length",
                      "status"=>"The gender must be F for Female or M for Male",
                      "tel"=>"The curp must have between 4 and 60 characters in length",
                      "gender"=>"The email must be unique and valid Email",
                      "email"=>"The email must have between 4 and 60 characters in length",
                      "address"=>"The email must be unique and valid Email",
                      "enrollment_Patient"=>"The email must be unique and valid Email"


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
            $this->form_validation->set_rules('name','name','required|max_length[160]|min_length[3]');
            $this->form_validation->set_rules('lastname','lastname','required|max_length[160]|min_length[3]');
            $this->form_validation->set_rules('status','status','required');
            $this->form_validation->set_rules('tel','tel','required|max_length[160]|min_length[4]');
            $this->form_validation->set_rules('gender','gender','required|exact_length[1]|callback_gender_valid');
            $this->form_validation->set_rules('email','email','callback_check_email');
            $this->form_validation->set_rules('address','address','required');
            $this->form_validation->set_rules('enrollment_Patient','enrollment_Patient','callback_check_enrol');


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
                    "name_PersonPatient"=>$this->post('name'),
                    "surname_PersonPatient "=>$this->post('lastname'),
                    "gender_PersonPatient"=>$this->post('gender'),
                    "tel_PersonPatient"=>$this->post('tel')
                );

                $personResponse = $this->DAO->saveOrUpdateItem('tb_PersonPatient',$person,null,true);
                if($personResponse['status']=="success"){
                    $user = array(
                        "email_UserPatient"=>$this->post('email'),
                        "type_UserPatient"=>"Patient",
                        "status_UserPatient "=>$this->post("status"),
                        "address_UserPatient "=>$this->post('address'),
                        "fk_PersonPatient"=>$personResponse['key']
                    );
                    $userResponse = $this->DAO->saveOrUpdateItem('tb_UsersPatient',$user,null,true);
                    if($userResponse['status']=="success"){

                      $collect = array(
                          "enrollment_Patient"=>$this->post('enrollment_Patient'),
                          "fk_PersonPatient"=>$personResponse['key']
                      );
                      $collectResponse = $this->DAO->saveOrUpdateItem('tb_Patient',$collect,null,true);
                      if($collectResponse['status']=="success"){
                        $response = array(
                           "status"=>"success",
                           "message"=>"Collect created successfully",
                           "data"=>$collectResponse,
                       );
                      }else{
                        $response = array(
                            "status"=>"errorr",
                            "message"=>$userResponse['message'],
                            "data"=>$collectResponse,
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
                    "message"=>  $personResponse['message'],
                    "data"=>null,
                    );
                }

             }
        }

        $this->response($response,200);
    }

    function patient_put($id=null){
        $data = $this->put();
        $Eixts = $this->DAO->selectEntity('tb_PersonPatient',array('idPersonPatient'=>$id),TRUE);
        if($Eixts){
          if(count($data) == 0 || count($data) > 19){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                  "data"=>null,
                  "validations"=>array(
                    "name"=>"The name must have between 4 and 60 characters in length",
                    "lastname"=>"The lastname must have between 4 and 60 characters in length",
                    "status"=>"The gender must be F for Female or M for Male",
                    "tel"=>"The curp must have between 4 and 60 characters in length",
                    "gender"=>"The email must be unique and valid Email",
                    "email"=>"The email must have between 4 and 60 characters in length",
                    "address"=>"The email must be unique and valid Email",
                    "enrollment_Patient"=>"The email must be unique and valid Email"
                  )
              );
          }else{

              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('name','name','required|max_length[160]|min_length[3]');
              $this->form_validation->set_rules('lastname','lastname','required|max_length[160]|min_length[3]');
              $this->form_validation->set_rules('status','status','required');
              $this->form_validation->set_rules('tel','tel','required|max_length[160]|min_length[4]');
              $this->form_validation->set_rules('gender','gender','required|exact_length[1]|callback_gender_valid');
              $this->form_validation->set_rules('email','email','callback_check_emailU');
              $this->form_validation->set_rules('address','address','required');
              $this->form_validation->set_rules('enrollment_Patient','enrollment_Patient','callback_check_enrolU');


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
                    "name_PersonPatient"=>$this->put('name'),
                    "surname_PersonPatient "=>$this->put('lastname'),
                    "gender_PersonPatient"=>$this->put('gender'),
                    "tel_PersonPatient"=>$this->put('tel')
                  );


                  $personResponse = $this->DAO->saveOrUpdateItem('tb_PersonPatient',$person,array('idPersonPatient'=>$id));

                  if($personResponse['status']=="success"){
                      $user = array(
                        "email_UserPatient"=>$this->put('email'),
                        "type_UserPatient"=>"Patient",
                        "status_UserPatient "=>$this->put("status"),
                        "address_UserPatient"=>$this->put('address'),
                        "fk_PersonPatient"=>$id
                      );
                      $userResponse = $this->DAO->saveOrUpdateItem('tb_UsersPatient',$user,array('fk_PersonPatient'=>$id));
                      if($userResponse['status']=="success"){

                        $collect = array(
                            "enrollment_Patient"=>$this->put('enrollment_Patient'),
                            "fk_PersonPatient"=>$id
                        );
                        $collectResponse = $this->DAO->saveOrUpdateItem('tb_Patient',$collect,array('fk_PersonPatient'=>$id));
                        if($collectResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"Patient updatedd successfully",
                             "data"=>$collectResponse,
                         );
                        }else{
                          $response = array(
                              "status"=>"errorr",
                              "message"=>$userResponse['message'],
                              "data"=>$collectResponse,
                          );
                        }
                         //  $response = array(
                         //     "status"=>"success",
                         //     "message"=>"Admin update successfully",
                         //     "data"=>$userResponse['status']
                         // );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=> $userResponse['message'],
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
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "no",
            "data"=>null,
            );
        }


        $this->response($response,200);
    }



    function patient_delete(){

        $id = $this->get('id');
		if ($id) {
			$Admin = $this->DAO->selectEntity('tb_PersonPatient',array('idPersonPatient'=>$id),TRUE);


			if($Admin){
      $this->db->trans_begin();

      $Admin = $this->DAO->deleteData('tb_PersonPatient',array('idPersonPatient'=>$id));
         if($Admin['status']=="success"){
             $User = $this->DAO->deleteData('tb_UsersPatient',array('idUserPatient'=>$id));
             if($User['status']=="success"){
                $Collect= $this->DAO->deleteData('tb_Patient',array('idPatient'=>$id));
                if($Collect){
                  $response = array(
                     "status"=>"success",
                     "message"=>"User deleted successfully",
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

    function check_email($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_UsersPatient',array('email_UserPatient'=>$str),TRUE);
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

    function check_enrol($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Patient',array('enrollment_Patient'=>$str),TRUE);
        if (!$empresaExists) {
          return TRUE;
        } else {
        $this->form_validation->set_message('check_enrol','The {field} already exists.');

          return FALSE;
        }

      } else {
        $this->form_validation->set_message('check_enrol','The {field} must be 10 characters in length');
          return FALSE;
      }
    }

    function check_enrolU($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Patient',array('enrollment_Patient'=>$str),TRUE);
        if (!$empresaExists) {
          return TRUE;
        } else {
          return TRUE;
        }

      } else {
        $this->form_validation->set_message('check_enrol','The {field} must be 10 characters in length');
          return FALSE;
      }
    }

    function check_emailU($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_UsersPatient',array('email_UserPatient'=>$str),TRUE);
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
