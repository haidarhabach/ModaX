<?php
include('db_connect.php');

if($_SERVER['REQUEST_METHOD']=="POST")
{
    $query= "SELECT id FROM users WHERE email='$_POST[name]' AND password_hash='$_POST[password]'";
    $result=mysqli_query($connect,$query);
if(mysqli_num_rows($result) > 0)
    {
        header("Location:HomePage.php");
        exit();
    }
    else {
        header("Location:login.php");
        exit();
    }
}

?>


<form action="#" method="post">
email : <input type="text" name="name"><br><br>
password : <input type="text" name="password"><br><br>

<a href="register.php?register=true">Register</a>
<input type="submit" value="Login">

</form>
