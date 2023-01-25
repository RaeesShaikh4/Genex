<?php
require_once "demo.php";

$email =$password=$password2=$firstname=$lastname=$gender=$country= "";
$email_err =$password_err=$password2_err=$firstname_err=$lastname_err=$gender_err=$country_err= "";

if($_SERVER['REQUEST_METHOD']== "POST"){
    //  CHECK IF EMAIL IS EMPTY

    if(empty(trim($_POST["email"]))){
        $email_err = "email cannot be blank";
    }else{
        $sql = "SELECT email FROM signup WHERE email=?";
        $stmt=mysqli_prepare($conn,$sql);
        if($stmt){
            mysqli_stmt_bind_param($stmt,"s", $param_email);
            
            //  set value of param email
            $param_email= trim($_POST['email']);

            // executing the statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt)==1){
                    $email_err= " this email is already in use";
                }else{
                    $email = trim($_POST['email']);
                }

            }else{
                echo "something wrong";
            }

        }
    }
    mysqli_stmt_close($stmt);


//  check for password

if(empty(trim($_POST['password']))){
    $password_err = "password cannot be blank";
}elseif(strlen(trim($_POST['password'])) < 8 ){
    $password_err = "password cannot be less than 8 characters";
}
else{
    $password=trim($_POST['password']);
}

//  check for confirm password field


if(trim($_POST['password']) != trim($_POST['password2'])){
    $password2_err = "password does not match";
}

//  if no errors ....insertion into database

if(empty($email_err) && empty($password_err) && empty($password2_err) && empty($firstname_err) && empty($lastname_err) && empty($gender_err) && empty($country_err)){
    $sql="  INSERT INTO signup (email,password,password2,firstname,lastname,gender,country) VALUES(?,?,?,?,?,?,?)";
    $stmt= mysqli_prepare($conn,$sql);
    if($stmt){
        mysqli_stmt_bind_param($stmt,"sssssss",$param_email,$param_password,$param_password2,$param_firstname,$param_lastname,$param_gender,$param_country);

        //  set parameters
        $param_email = $email;
        $param_password =password_hash($password,PASSWORD_DEFAULT);
        
        $param_password2 =$password2;
        $param_firstname =$firstname;
        $param_lastname =$lastname;
        $param_gender =$gender;
        $param_country =$country;

        // executing query
        if(mysqli_stmt_execute($stmt)){
            echo "record inserted successfully";
        }else{
            echo "something went wrong...cannot redirect";
        }
        
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);

}
?>