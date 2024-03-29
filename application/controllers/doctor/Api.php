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
    function doctor_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('vw_Doctor',array('id'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('vw_Doctor'),
            );
        }
        $this->response($response,200);
    }
    function doctor_post(){
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
                      "speciality"=>"The email must be unique and valid Email",
                      "professionalId"=>"The email must be unique and valid Email"


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
            $this->form_validation->set_rules('speciality','speciality','callback_check_speciality');
            $this->form_validation->set_rules('professionalId','professionalId','callback_check_professional');


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
                    "name_Person"=>$this->post('name'),
                    "surname_Person "=>$this->post('lastname'),
                    "gender_Person"=>$this->post('gender'),
                    "tel_Person"=>$this->post('tel')
                );

                $personResponse = $this->DAO->saveOrUpdateItem('tb_Person',$person,null,true);
                if($personResponse['status']=="success"){
                    $user = array(
                        "email_User"=>$this->post('email'),
                        "type_User"=>"Doctor",
                        "status_User "=>$this->post("status"),
                        "address_User "=>$this->post('address'),
                        "fk_Person"=>$personResponse['key']
                    );
                    $userResponse = $this->DAO->saveOrUpdateItem('tb_Users',$user,null,true);
                    if($userResponse['status']=="success"){

                      $doc = array(
                          "professionalId_Doctor"=>$this->post('professionalId'),
                          "fk_Specialities"=>$this->post('speciality'),
                          "fk_Person"=>$personResponse['key']
                      );
                      $DocResponse = $this->DAO->saveOrUpdateItem('tb_Doctor',$doc,null,true);
                      if($DocResponse['status']=="success"){
                        $response = array(
                           "status"=>"success",
                           "message"=>"Doctor created successfully",
                           "data"=>$DocResponse,
                       );
                      }else{
                        $response = array(
                            "status"=>"errorr",
                            "message"=>$userResponse['message'],
                            "data"=>$DocResponse,
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

    function doctor_put($id=null){
        $data = $this->put();
        $Eixts = $this->DAO->selectEntity('tb_Person',array('idPerson'=>$id),TRUE);
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
                    "speciality"=>"The email must be unique and valid Email",
                    "professionalId"=>"The email must be unique and valid Email"

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
              $this->form_validation->set_rules('speciality','speciality','callback_check_speciality');
              $this->form_validation->set_rules('professionalId','professionalId','callback_check_professionalU');

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
                    "name_Person"=>$this->put('name'),
                    "surname_Person "=>$this->put('lastname'),
                    "gender_Person"=>$this->put('gender'),
                    "tel_Person"=>$this->put('tel')
                  );


                  $personResponse = $this->DAO->saveOrUpdateItem('tb_Person',$person,array('idPerson'=>$id));

                  if($personResponse['status']=="success"){
                      $user = array(
                        "email_User"=>$this->put('email'),
                        "type_User"=>"Doctor",
                        "status_User "=>$this->put("status"),
                        "address_User "=>$this->put('address'),
                        "fk_Person"=>$id
                      );
                      $userResponse = $this->DAO->saveOrUpdateItem('tb_Users',$user,array('fk_Person'=>$id));
                      if($userResponse['status']=="success"){

                        $Doc = array(
                          "professionalId_Doctor"=>$this->put('professionalId'),
                          "fk_Specialities"=>$this->put('speciality'),
                          "fk_Person"=>$id
                        );
                        $docRes = $this->DAO->saveOrUpdateItem('tb_Doctor',$Doc,array('fk_Person'=>$id));
                        if($docRes['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"Doctor created successfully",
                             "data"=>$docRes,
                         );
                        }else{
                          $response = array(
                              "status"=>"errorr",
                              "message"=>$userResponse['message'],
                              "data"=>$docRes,
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



    function doctor_delete(){

        $id = $this->get('id');
		if ($id) {
			$Admin = $this->DAO->selectEntity('tb_Person',array('idPerson'=>$id),TRUE);


			if($Admin){
      $this->db->trans_begin();

      $Admin = $this->DAO->deleteData('tb_Person',array('idPerson'=>$id));
         if($Admin['status']=="success"){
             $User = $this->DAO->deleteData('tb_Users',array('fk_Person'=>$id));
             if($User['status']=="success"){
                $Collect= $this->DAO->deleteData('tb_Doctor',array('fk_Person'=>$id));
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

    function check_professional($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Doctor',array('professionalId_Doctor'=>$str),TRUE);
        if (!$empresaExists) {
          return TRUE;
        } else {
        $this->form_validation->set_message('check_professional','The {field} already exists.');

          return FALSE;
        }

      } else {
        $this->form_validation->set_message('check_professional','The {field} must be 10 characters in length');
          return FALSE;
      }
    }

    function check_professionalU($str){
      if ( strlen($str)>=1) {
        $empresaExists = $this->DAO->selectEntity('tb_Doctor',array('professionalId_Doctor'=>$str),TRUE);
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


        function check_speciality($str){
          if ( strlen($str)>=1) {
            $empresaExists = $this->DAO->selectEntity('tb_Speciality',array('idSpeciality'=>$str),TRUE);
            if ($empresaExists) {
              return TRUE;
            } else {
            $this->form_validation->set_message('check_speciality','The {field} does not exist.');

              return FALSE;
            }

          } else {
            $this->form_validation->set_message('check_speciality','The {field} must be 10 characters in length');
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
