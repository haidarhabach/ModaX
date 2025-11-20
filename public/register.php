<?php
include('db_connect.php');
if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $reslt=mysqli_query($connect,"select id from users");
        $id=mt_rand(1,10000000);
        while($row=mysqli_fetch_assoc($reslt))
        {
            if($row["id"]!=$id)
            {
                break;
            }
            else {
                    $id=mt_rand(1,10000000);
            }
        }
        
        $name=$_POST["name"];
        $pass=$_POST["password"];
        $email=$_POST["email"];
        $phone=$_POST["phone"];
        $query= "INSERT INTO users values($id,'$name','$email','$pass','$phone',now())";
        $valid=mysqli_query($connect,$query);
        if($valid)
        {
            header("Location:login.php");
        }
        else {
            header("Location:register.php");
        }
    }


?>



<form action="#" method="post">
name : <input type="text" name="name" required><br><br>
email : <input type="text" name="email" required><br><br>
phone : <input type="text" name="phone" required><br><br>
password : <input type="text" name="password" required><br><br>
<input type="submit" value="Register">
</form>
