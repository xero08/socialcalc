<?php
     $dbConErr='Failed to connect to database';
     define('DBUSERNAME','root');
     define('DBPASSWORD','t1ger');
     define('DBHOST','localhost');
     define('DBNAME','calc'); 

     //Multiple unused methods are included in this file.Ignore except those necessary.    

     function genHandle(){
          $dbUsername=DBUSERNAME;
          $dbPassword=DBPASSWORD;
          $dbHost=DBHOST;
          $dbName=DBNAME;
          try{
               $handle=new PDO("mysql:host=$dbHost;dbname=$dbName",$dbUsername,$dbPassword);
               $handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
               return $handle;
          }
          catch(PDOException $pdoex){
               return null;
          }
     }

     function completeRegn($user,$pName){
          $actData=getData("SELECT COUNT(*) AS n FROM `users` WHERE `user`='".$user."'",0);
          if($actData['n']!=0){
               return "RET_USER";
          }
          $handle=genHandle();
          $completeAcQ="INSERT INTO `users`(user,pName) VALUES(?,?)";
          $prepared=$handle->prepare($completeAcQ);
          $prepared->execute(array($user,$pName));
          //$prepared->execute(array('VOILA','VERSUS'));
          //return $user.' '.$pName;
          return 'NEW_USER';
     }

     function insertValues($inputs,$table){
        $handle=genHandle();
        $query="INSERT INTO ".$table."(";
        foreach ($inputs as $key => $value) {
                $query.=$key.",";
        }
        $query=substr($query,0,-1).") values(";
        for($i=0;$i<sizeof($inputs);$i++)
          $query.='?,';
        $query=substr($query,0,-1).")";
        $prepared=$handle->prepare($query);        
        $prepared->execute(array_values($inputs));
        return $handle->errorInfo();
     }

     function updateValues($clauseCol,$clauseVal,$inputs,$table){
        $handle=genHandle();
        $query="UPDATE ".$table." set ";
        foreach ($inputs as $key => $value) {
                $query.=$key."=?,";
        }
        $query=substr($query,0,-1)." WHERE ".$clauseCol."=?";        
        $prepared=$handle->prepare($query);        
        array_push($inputs,$clauseVal);
        $prepared->execute(array_values($inputs));
        return $handle->errorInfo();
     }

     function deleteRow($clauseCol,$clauseVal,$table){
        $handle=genHandle();
        $query="DELETE FROM ".$table." WHERE ".$clauseCol."=?";
        $prepared=$handle->prepare($query);
        $prepared->execute(array($clauseVal));
        return $handle->errorInfo();
     }
     
     function randomKeyGen(){
          $chars = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
          shuffle($chars);
          return implode(array_slice($chars,0,8));
     }
     
     function authLogin($username,$password,$table){
          $data=getData("SELECT COUNT(*) AS numRetr FROM `".$table."` WHERE `username` = '".$username."' AND `password` = '".md5($password)."'",0);
          return $data[0];
     }
     
     function getDetails($email){
          $real=getData("SELECT * FROM `users` WHERE `email` = '".$email."'",0);
          return Array("name"=>$real['name'],"insti"=>$real['insti'],"mob"=>$real['mob'],"regns"=>$real['regns']);
     }

     function updatePassword($user,$oldpass,$newpass,$cnfpass){
          $row=getData("SELECT COUNT(*) FROM `users` WHERE `email` = '".$user."' AND `password` = '".md5($oldpass)."'",0);
          if($row[0]==0)
               return -1;
          if(strlen($newpass)<6)
               return 2;
          if($newpass!=$cnfpass)
               return 0;
          $qUpdt="UPDATE `users` SET `password`=? WHERE `email`=?";
          $handle=genHandle();
          $prep=$handle->prepare($qUpdt);
          $prep->execute(array(md5($newpass),$user));
          return 1;
     }
     
     function checkRegStatus($evId,$regnList){
          if(in_array($evId,$regnList))
               return 1;
          else
               return 0;
     }

     function getBrowser(){
          if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false){
              return 'CHROME';
          }
          else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false){
              return 'FIREFOX';
          }
          else if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false){
              return 'MSIELE10';
          }
          else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false){
              return 'MSIE11';
          }
     }
     
     function getData($query,$multiRow){
          //echo $query;          
          $handle=genHandle();
          $data=$handle->query($query);
          $data->setFetchMode(PDO::FETCH_NUM);
          if($multiRow===0)
               return $data->fetch();
          else
               return $data;
          close($handle);
     }        

     function checkSet(){
        $numargs = func_num_args();
        $arg_list = func_get_args();
        for($i=0;$i<$numargs;$i++){
          if(!isset($_POST[$arg_list[$i]]))
            return false;
        }
        return true;
     }

     function checkNotEmpty(){
        $numargs = func_num_args();
        $arg_list = func_get_args();
        for($i=0;$i<$numargs;$i++){
          if(trim($_POST[$arg_list[$i]])==='')
            return false;
        }
        return true;
     }     

     function trimVars($vType){
        if($vType=='GET'){
          foreach ($_GET as $key => $value) {
            $_GET[$key] = trim($_GET[$key]);
          }
        }          
        else if($vType=='POST'){
          foreach ($_POST as $key => $value) {
            $_POST[$key] = trim($_POST[$key]);
          }
        }          
     }

     function cleanVars($vType){
        if($vType=='GET'){
          foreach ($_GET as $key => $value) {
            $_GET[$key] = htmlspecialchars(mysql_real_escape_string($_GET[$key]));
          }
        }          
        else if($vType=='POST'){
          foreach ($_POST as $key => $value) {
            $_POST[$key] = htmlspecialchars(mysql_real_escape_string($_POST[$key]));
          }
        }          
     }

     function getErrors($inputs){
        $errorCode = '';
        foreach ($inputs as $key => $value) {
          if($value=='usernamePK')
            $errorCode.=getData("SELECT count(*) FROM `login` WHERE `username`='".substr($_POST[$key],0,64)."'",0)[0]==0?'1':'0';
          if($value=='name')
            $errorCode.=preg_match('#[\d]#',substr($_POST[$key],0,128))?'0':'1';
          else if($value=='email')
            $errorCode.=filter_var(substr($_POST[$key],0,64),FILTER_VALIDATE_EMAIL)?'1':'0';            
          else if($value=='password')
            $errorCode.=strlen(substr($_POST[$key],0,64))<7?'0':'1';
        }
        return $errorCode;
     }     

     function checkSessionRedir($var){
        if(!isset($_SESSION[$var])){
          header('location: login.php?errorCode=2');
        }
     }
?>