<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Api extends REST_Controller {
    function __construct(){
        parent:: __construct();
        $this->load->model('DAO');
    }


   function login_post(){
     if(count($this->post())>3){
           $response=array(
               "status"=>"error",
               "status_code"=>409,
               "message"=>"Too many data was sent",
               "validations"=>array(

                 "email"=>"required",
                 "password"=>"required"

               ),
               "data"=>null
           );
       }
       else{
               $this->form_validation->set_data($this->post());
               $this->form_validation->set_rules('email','email','required');
               $this->form_validation->set_rules('password','password','required');


           if ($this->form_validation->run()==false) {
               $response=array(
                     "status"=>"error",
                     "status_code"=>409,
                     "message"=>"check the validations",
                     "validations"=>$this->form_validation->error_array(),
                     "data"=>$this->post()
                 );
           }
           else{

             	$userExists = $this->DAO->selectEntity('vw_Admin',array('email'=>$this->post('email')),TRUE);
              if($userExists){
                $response=array(
                      "status"=>"Success",
                      "message"=>"check the validations"
                  );
                  $this->load->library('bcrypt');
      						if($this->bcrypt->check_password($this->post('password'),$userExists->password)){
                    $response=array(
                          "status"=>"success",
                          "message"=>"the data",
                          "data"=>array(
                            'id'=>$userExists->id,
                            'name'=>$userExists->name,
                            'lastname'=>$userExists->lastname,
                            'status'=>$userExists->status,
                            'type'=>$userExists->type,
                            'email'=>$userExists->email
                          )

                      );
                  }else{


                    $response=array(
                          "status"=>"error pass",
                          "message"=>"check the validations",
                          "data"=>$this->post('password')
                      );
                  }


              }else{
                $Doc = $this->DAO->selectEntity('vw_Doctor',array('email'=>$this->post('email')),TRUE);
                if($Doc){
                  $response=array(
                        "status"=>"Success",
                        "message"=>"check the validations"
                    );
                    $this->load->library('bcrypt');
                    if($this->bcrypt->check_password($this->post('password'),$Doc->password)){
                      $response=array(
                            "status"=>"success",
                            "message"=>"the data",
                            "data"=>array(
                              'id'=>$Doc->id,
                              'name'=>$Doc->name,
                              'lastname'=>$Doc->lastname,
                              'status'=>$Doc->status,
                              'type'=>$Doc->type,
                              'email'=>$Doc->email
                            )

                        );
                    }else{
                      $response=array(
                            "status"=>"error pass",
                            "message"=>"check the validations",
                            "data"=>$this->post('password')
                        );
                    }
                }else{
                    $Collec = $this->DAO->selectEntity('vw_Collection',array('email'=>$this->post('email')),TRUE);
                    if($Collec){
                      $response=array(
                            "status"=>"Success",
                            "message"=>"check the validations"
                        );
                        $this->load->library('bcrypt');
                        if($this->bcrypt->check_password($this->post('password'),$Collec->password)){
                          $response=array(
                                "status"=>"success",
                                "message"=>"the data",
                                "data"=>array(
                                  'id'=>$Collec->id,
                                  'name'=>$Collec->name,
                                  'lastname'=>$Collec->lastname,
                                  'status'=>$Collec->status,
                                  'type'=>$Collec->type,
                                  'email'=>$Collec->email
                                )

                            );
                        }else{
                          $response=array(
                                "status"=>"error pass",
                                "message"=>"check the validations",
                                "data"=>$this->post('password')
                            );
                        }
                    }else{
                      $Patient = $this->DAO->selectEntity('vw_Patient',array('email'=>$this->post('email')),TRUE);
                      if($Patient){
                        $response=array(
                              "status"=>"Success",
                              "message"=>"check the validations"
                          );
                          $this->load->library('bcrypt');
                          if($this->bcrypt->check_password($this->post('password'),$Patient->password)){
                            $response=array(
                                  "status"=>"success",
                                  "message"=>"the data",
                                  "data"=>array(
                                    'id'=>$Patient->id,
                                    'name'=>$Patient->name,
                                    'lastname'=>$Patient->lastname,
                                    'status'=>$Patient->status,
                                    'type'=>$Patient->type,
                                    'email'=>$Patient->email
                                  )

                              );
                          }else{
                            $response=array(
                                  "status"=>"error pass",
                                  "message"=>"check the validations",
                                  "data"=>$this->post('password')
                              );
                          }
                      }else{
                        $Reception = $this->DAO->selectEntity('vw_Reception',array('email'=>$this->post('email')),TRUE);
                        if($Reception){
                          $response=array(
                                "status"=>"Success",
                                "message"=>"check the validations"
                            );
                            $this->load->library('bcrypt');
                            if($this->bcrypt->check_password($this->post('password'),$Reception->password)){
                              $response=array(
                                    "status"=>"success",
                                    "message"=>"the data",
                                    "data"=>array(
                                      'id'=>$Reception->id,
                                      'name'=>$Reception->name,
                                      'lastname'=>$Reception->lastname,
                                      'status'=>$Reception->status,
                                      'type'=>$Reception->type,
                                      'email'=>$Reception->email
                                    )

                                );
                            }else{
                              $response=array(
                                    "status"=>"error pass",
                                    "message"=>"check the validations",
                                    "data"=>$this->post('password')
                                );
                            }
                        }else{
                          $response=array(
                                "status"=>"Error",
                                "message"=>"check the validations",
                                "data"=>"Username does not exist"
                            );
                        }
                      }
                    }


                }
              }










           }
       }



        $this->response($response,200);
   }
}
