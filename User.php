<?php

class User {

	function __construct(){
        
        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli('localhost', 'id2531118_therussianjig', 'Runi5V6Kg1GXUgoQYf1W', 'id2531118_civichackathon');
            /* check connection */
            if ($conn->connect_errno) {
                printf("Connect failed: %s\n", $conn->connect_error);
                exit();
            }
            else
            {
                $this->db = $conn;
                $this->userTbl = "userInfo";
            }
        }
    }
	
	function checkUser($userData = array()){

        if(!empty($userData))
        {

            //Check whether user data already exists in database
            $prevQuery = "SELECT * FROM ".$this->userTbl." WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";

            $prevResult = $this->db->query($prevQuery);
            $row_count = $prevResult->num_rows;
            
            if($row_count > 0)
            {
                //Update user data if already exists
                $query = "UPDATE ".$this->userTbl." SET first_name = '".$userData['first_name']."', last_name = '".$userData['last_name']."', email = '".$userData['email']."', gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', link = '".$userData['link']."', modified = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";

                $update = $this->db->query($query);
            }

            else
            {
                //Insert user data
                $query = " INSERT INTO userInfo "
                         . "  (oauth_provider "
                         . "  ,oauth_uid "
                         . "  ,first_name "
                         . "  ,last_name "
                         . "  ,email "
                         . "  ,gender "
                         . "  ,locale "
                         . "  ,picture "
                         . "  ,link "
                         . "  ,created "
                         . "  ,modified) "
                     . " VALUES "
                          ." ( '" . $userData['oauth_provider'] ."'"
                           . ",'". $userData['oauth_uid'] ."'"
                           . ",'". $userData['first_name'] ."'"
                           . ",'". $userData['last_name'] ."'"
                           . ",'". $userData['email'] ."'"
                           . ",'". $userData['gender'] ."'"
                           . ",'". $userData['locale'] ."'"
                           . ",'". $userData['picture'] ."'"
                           . ",'". $userData['link'] ."'"
                           . ",'". date("Y-m-d H:i:s") ."'"
                           . ",'". date("Y-m-d H:i:s") ."')";
                
                $insert =$this->db->query($query);

            }
            
            //Get user data from the database
            $result = $this->db->query($prevQuery);
        }
        
        //Return user data
        return $userData;
    }
}
?>