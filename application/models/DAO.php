  <?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DAO extends CI_Model{
	function __construct(){
        parent::__construct();
    }

    function selEntityMany($entity, $whereClause = NULL){
      if($whereClause){
      $this->db->where($whereClause);
         $query = $this->db->get($entity);
      }else{
        $query = $this->db->get($entity);
      }
      return  $query->result();
    }

    function saveOrUpdate($entity,$data,$whereClause = null, $returnKey = FALSE){
      if($whereClause){
        $this->db->where($whereClause);
        $this->db->update($entity,$data);
      }else{
        $this->db->insert($entity,$data);
      }
      if($this->db->error()['message']!=''){
        $response = array(
          "status"=>"error",
          "message"=>$this->db->error()['message'],
          "data"=>null
        );
      }else{
        if($whereClause){
          $msg = "Información actualizada correctamente!";
        }else{
          $msg = "Información registrada correctamente!";
        }
        $response = array(
          "status"=>"success",
          "message"=>$msg,
        );
        if($returnKey){
          $response['data'] = $this->db->insert_id();
        }
      }
      return $response;
    }

   function insertDataStudent($data){
      $this->db->trans_begin();
          foreach ($data as $emp) {
             $Person= array(
          "personName"=>$emp['personName'],
          "personLastName"=>$emp['personLastName'],
          "personGender"=>$emp['personGender'],
          "personCURP"=>$emp['personCURP']


           );
            $this->db->insert('Persons',$Person);

            $fkPerson = $this->db->insert_id();
            $emp['studentEnroll']['fkPerson'] = $fkPerson;
           $this->db->insert('Students',$emp['studentEnroll']);

            $fkPerson = $this->db->insert_id();
            $emp['userEmail']['fkPerson'] = $fkPerson;
           $this->db->insert('Users',$emp['userEmail']);



          }

      if ($this->db->trans_status()== false) {

        $this->db->trans_rollback();
        $responseDB = array(
            "status"=> "error",
            "status_code"=>409,
            "message"=>$this->db->error()['message'],
            "data"=>$fkPerson
        );



      }else{
         $this->db->trans_commit();
        $responseDB = array(
            "status"=> "success",
            "status_code"=>201,
            "message"=>"Person and title create successfully",
            "data"=>null
        );

      }
      return $responseDB;
    }

    function selectEntity($entity, $whereClause = NULL){
    	if($whereClause){
    		$this->db->where($whereClause);

    	}
      $query = $this->db->get($entity);


    	return $whereClause ? $query->row() : $query->result();
    }

    function insertData($entityName,$data){
    	$this->db->insert($entityName,$data);
    	if($this->db->error()['message']!=""){
        $responseDB = array(
            "status"=>"error",
            "status_code"=>409,
            "message"=>$this->db->error()['message']
        );
    	}else{
    		$reponseDB = array(
    			"status"=>"success",
    			"status_code"=>201,
    			"message"=>"Data inserted successful",
    			"validations"=>null,
    			"data"=>null
    		);
    	}
    	return $reponseDB;
    }

  function updateData($entityName,$data,$whereClause){
        $this->db->where($whereClause);
        $this->db->update($entityName,$data);
        if($this->db->error()['message']!=""){
            $reponseDB = array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"Db error: ".$this->db->error()['message'],
                "validations"=>null,
                "data"=>null
            );
        }else{
            $reponseDB = array(
                "status"=>"success",
                "status_code"=>200,
                "message"=>"Data updated successful",
                "validations"=>null,
                "data"=>null
            );
        }
        return $reponseDB;
    }

    function deleteData($entityName,$whereClause){
        $this->db->where($whereClause);
        $this->db->delete($entityName);
         if($this->db->error()['message']!=""){
            $reponseDB = array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"Db error: ".$this->db->error()['message'],
                "validations"=>null,
                "data"=>null
            );
        }else{
            $reponseDB = array(
                "status"=>"success",
                "status_code"=>200,
                "message"=>"Data deleted successful",
                "validations"=>null,
                "data"=>null
            );
        }
        return $reponseDB;

    }

    function updateDataStudent($data,$id){
        $this->db->trans_off();
      $this->db->trans_begin();
            // $this->db->query('UPDATE persons SET personName = "Israelupdate", personLastName ="Martine" WHERE persons.personId = 1');
        foreach ($data as $emp) {
             $Person= array(
          "personName"=>$emp['personName'],
          "personLastName"=>$emp['personLastName'],
          "personGender"=>$emp['personGender'],
          "personCURP"=>$emp['personCURP']


        );
        $IdPerson = $id;
        $this->db->where($IdPerson);
        $this->db->update('Persons',$Person);

        $fkPerson = $id;

             $this->db->where($fkPerson);
           $this->db->update('Students',$emp['studentEnroll']);

           $this->db->where($fkPerson);
           $this->db->insert('Users',$emp['userEmail']);

        }

        if($this->db->trans_status()==FALSE){
        $this->db->trans_rollback();
        $responseDB = array(
            "status"=> "error",
            "status_code"=>409,
            "message"=>$this->db->error()['message'],
            "data"=>$fkPerson
            );
        }else{
         $this->db->trans_commit();
        $responseDB = array(
            "status"=> "success",
            "status_code"=>201,
            "message"=>"updated successfully",
            "data"=>$fkPerson
            );
        }


        return $responseDB;
    }

    function deleteDataStudent($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE Students SET studentStatus = "Inactive" WHERE Students.fkPerson = ? ',array($id));
        // $this->db->query("UPDATE Products SET stockProduct = stockProduct + ? WHERE barcodeProduct = ?",array($quantity,$code));

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }
        else
        {
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"updated successfully",
                "data"=>null
            );
        }

         return $responseDB;
    }

    // Professors

    function insertDataProfessor($data){
      $this->db->trans_begin();
          foreach ($data as $emp) {
             $Person= array(
          "personName"=>$emp['personName'],
          "personLastName"=>$emp['personLastName'],
          "personGender"=>$emp['personGender'],
          "personCURP"=>$emp['personCURP']


           );
            $this->db->insert('Persons',$Person);

            $fkPerson = $this->db->insert_id();
            $emp['professorNum']['fkPerson'] = $fkPerson;
           $this->db->insert('Professors',$emp['professorNum']);

            $fkPerson = $this->db->insert_id();
            $emp['userEmail']['fkPerson'] = $fkPerson;
           $this->db->insert('Users',$emp['userEmail']);



          }

      if ($this->db->trans_status()== false) {

        $this->db->trans_rollback();
        $responseDB = array(
            "status"=> "error",
            "status_code"=>409,
            "message"=>$this->db->error()['message'],
            "data"=>$fkPerson
        );



      }else{
         $this->db->trans_commit();
        $responseDB = array(
            "status"=> "success",
            "status_code"=>201,
            "message"=>"create successfully",
            "data"=>null
        );

      }
      return $responseDB;
    }


    function updateDataProfessor($data,$id){
        $this->db->trans_off();
      $this->db->trans_begin();
            // $this->db->query('UPDATE persons SET personName = "Israelupdate", personLastName ="Martine" WHERE persons.personId = 1');
        foreach ($data as $emp) {
             $Person= array(
          "personName"=>$emp['personName'],
          "personLastName"=>$emp['personLastName'],
          "personGender"=>$emp['personGender'],
          "personCURP"=>$emp['personCURP']


        );
        $IdPerson = $id;
        $this->db->where($IdPerson);
        $this->db->update('Persons',$Person);

        $fkPerson = $id;

             $this->db->where($fkPerson);
           $this->db->update('Students',$emp['professorNum']);

           $this->db->where($fkPerson);
           $this->db->insert('Users',$emp['userEmail']);

        }

        if($this->db->trans_status()==FALSE){
        $this->db->trans_rollback();
        $responseDB = array(
            "status"=> "error",
            "status_code"=>409,
            "message"=>$this->db->error()['message'],
            "data"=>$fkPerson
            );
        }else{
         $this->db->trans_commit();
        $responseDB = array(
            "status"=> "success",
            "status_code"=>201,
            "message"=>"updated successfully",
            "data"=>$fkPerson
            );
        }


        return $responseDB;
    }

     function deleteDataProfessor($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE Professors SET professorStatus = "Inactive" WHERE Professors.fkPerson = ? ',array($id));
        // $this->db->query("UPDATE Products SET stockProduct = stockProduct + ? WHERE barcodeProduct = ?",array($quantity,$code));

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }
        else
        {
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"Person, student and user updated successfully",
                "data"=>null
            );
        }

         return $responseDB;
    }


    //Admin

    function insertDataAdmin($data){
      $this->db->trans_begin();
          foreach ($data as $emp) {
             $Person= array(
          "personName"=>$emp['personName'],
          "personLastName"=>$emp['personLastName'],
          "personGender"=>$emp['personGender'],
          "personCURP"=>$emp['personCURP']


           );
            $this->db->insert('Persons',$Person);

            $fkPerson = $this->db->insert_id();
            $emp['userEmail']['fkPerson'] = $fkPerson;
           $this->db->insert('Users',$emp['userEmail']);



          }

      if ($this->db->trans_status()== false) {

        $this->db->trans_rollback();
        $responseDB = array(
            "status"=> "error",
            "status_code"=>409,
            "message"=>$this->db->error()['message'],
            "data"=>$fkPerson
        );



      }else{
         $this->db->trans_commit();
        $responseDB = array(
            "status"=> "success",
            "status_code"=>201,
            "message"=>"Person and title create successfully",
            "data"=>null
        );

      }
      return $responseDB;
    }

    function deleteDataTecnico($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE Tecnicos SET statusTecnico = "Inactive" WHERE Tecnicos.fkPersona = ? ',array($id));
        // $this->db->query("UPDATE Products SET stockProduct = stockProduct + ? WHERE barcodeProduct = ?",array($quantity,$code));

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }
        else
        {
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"Person, student and user updated successfully",
                "data"=>null
            );
        }

         return $responseDB;
    }

    function deleteDataCliente($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE Clientes SET  statusCliente  = "Inactive" WHERE Clientes.idCliente = ? ',array($id));
        // $this->db->query("UPDATE Products SET stockProduct = stockProduct + ? WHERE barcodeProduct = ?",array($quantity,$code));

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }
        else
        {
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"Person, student and user updated successfully",
                "data"=>null
            );
        }

         return $responseDB;
    }


    function updateDataAdmin($data,$id){
        $this->db->trans_off();
      $this->db->trans_begin();
            // $this->db->query('UPDATE persons SET personName = "Israelupdate", personLastName ="Martine" WHERE persons.personId = 1');
        foreach ($data as $emp) {
             $Person= array(
          "personName"=>$emp['personName'],
          "personLastName"=>$emp['personLastName'],
          "personGender"=>$emp['personGender'],
          "personCURP"=>$emp['personCURP']


        );
        $IdPerson = $id;
        $this->db->where($IdPerson);
        $this->db->update('Persons',$Person);

        $fkPerson = $id;

           $this->db->where($fkPerson);
           $this->db->update('Users',$emp['userEmail']);

        }

        if($this->db->trans_status()==FALSE){
        $this->db->trans_rollback();
        $responseDB = array(
            "status"=> "error",
            "status_code"=>409,
            "message"=>$this->db->error()['message'],
            "data"=>$fkPerson
            );
        }else{
         $this->db->trans_commit();
        $responseDB = array(
            "status"=> "success",
            "status_code"=>201,
            "message"=>"Updated successfully",
            "data"=>$fkPerson
            );
        }


        return $responseDB;
    }

     function deleteDataAdmin($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE Professors SET professorStatus = "Inactive" WHERE Professors.fkPerson = ? ',array($id));
        // $this->db->query("UPDATE Products SET stockProduct = stockProduct + ? WHERE barcodeProduct = ?",array($quantity,$code));

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }
        else
        {
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"updated successfully",
                "data"=>null
            );
        }

         return $responseDB;
    }

 // function updateDataStudent($data,$id){
 //      $this->db->trans_start();
 //          foreach ($data as $emp) {
 //             $Person= array(
 //          "personName"=>$emp['personName'],
 //          "personLastName"=>$emp['personLastName'],
 //          "personGender"=>$emp['personGender'],
 //          "personCURP"=>$emp['personCURP']


 //      );
 //        $this->db->where($id);
 //        $this->db->update('Persons',$Person);

 //            $fkPerson = $this->db->insert_id();
 //            $emp['studentEnroll']['fkPerson'] = $id;
 //           $this->db->insert('Students',$emp['studentEnroll']);

 //            $fkPerson = $this->db->insert_id();
 //            $emp['userEmail']['fkPerson'] = $id;
 //           $this->db->insert('Users',$emp['userEmail']);



 //          }

 //      if ($this->db->trans_status()== false) {

 //        $this->db->trans_rollback();
 //        $responseDB = array(
 //            "status"=> "error",
 //            "status_code"=>409,
 //            "message"=>$this->db->error()['message'],
 //            "data"=>$id
 //        );



 //      }else{
 //         $this->db->trans_commit();
 //        $responseDB = array(
 //            "status"=> "success",
 //            "status_code"=>201,
 //            "message"=>"Person updated successfully",
 //            "data"=>$data
 //        );

 //      }
 //      $this->db->trans_complete();
 //      return $responseDB;
 //    }

//===============================================

function saveOrUpdateItem($entityName,$data,$whereClause = NULL,$generateKey =  FALSE){

    if($whereClause){
        $this->db->where($whereClause);
        $this->db->update($entityName,$data);
    }else{
        $this->db->insert($entityName,$data);
    }
    if($this->db->error()['message']!=''){
        $responseDB = array(
            "status"=>"error",
            "status_code"=>409,
            "message"=>$this->db->error()['message']
        );
    }else{
        $responseDB = array(
            "status"=>"success",
            "status_code"=>$whereClause ? 200 : 201,
            "message"=>"Item created Successfully",
            "key"=>$generateKey ? $this->db->insert_id() : null
        );
    }
    return $responseDB;

}


function saveOrUpdateBatchItems($entityName,$data,$whereClause = NULL){
    if($whereClause){

    }else{
        $this->db->insert_batch($entityName,$data);
    }
    if($this->db->error()['message']!=''){
        $responseDB = array(
            "status"=>"error",
            "status_code"=>409,
            "message"=>$this->db->error()['message']
        );
    }else{
        $responseDB = array(
            "status"=>"success",
            "status_code"=>201,
            "message"=>"Item created Successfully"
        );
    }
    return $responseDB;

}


public function updateOrUpdateItem($entityName,$data,$whereClause = NULL,$generateKey =  FALSE){

}


  }
