<html>

<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.php?error=email_not_found");
        exit();
    }

    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
if(isset($_POST['client_login'])){
    $stmt = $connect->prepare("SELECT name,id,password_hash FROM users WHERE email = ?");
}
else if(isset($_POST['login_admin']))
{
    $stmt = $connect->prepare("SELECT username,id,password_hash FROM admins WHERE email = ?");

}
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $row = $result->fetch_assoc();
        $hashedPassword = $row["password_hash"];

        if (password_verify($password, $hashedPassword)) {

            if(isset($_POST['client_login']))
            {
              $_SESSION['user_id']=$row['id'];
              $_SESSION['user_name'] = $row['name'];
              if(isset($_SESSION['admin_id']))
              {
                unset($_SESSION['admin_id']); 
                unset($_SESSION['admin_name']);
              }
            }
            if(isset($_POST['login_admin']))
            {
              $_SESSION['admin_id']= $row['id'] ;
              $_SESSION['admin_name']= $row['username'] ;
              if(isset($_SESSION['user_id']))
              {
                unset($_SESSION['user_id']); 
                unset($_SESSION['user_name']);
              }
            }
            if(isset($_SESSION['admin_id']))
            {
              header("Location: ../admin/index.php");
              exit();
            }
            header("Location: index.php");
            exit();

        } else {
            header("Location: login.php?error=wrong_password");
            exit();
        }

    } else {
        header("Location: login.php?error=email_not_found");
        exit();
    }
}
?>

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins-Regular";
            color: #333;
            font-size: 13px;
            margin: 0;
        }

        input,
        button {
            font-family: "Poppins-regular";
        }

        h5 {
            margin: 0;
        }

        img {
            max-width: 100%;
        }

        .wrapper {
            display: flex;
            align-items: center;
            min-height: 100vh;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .inner {
            padding: 20px;
            background: #fff;
            max-width: 850px;
            margin: auto;
            display: flex;
            flex-direction: row-reverse; /* This reverses the order */

            .image-holder {
                width: 50%;
            }

            form {
                width: 50%;
                padding-top: 36px;
                padding-left: 45px;
                padding-right: 45px;
            }

            h3 {
                text-transform: uppercase;
                font-size: 25px;
                font-family: "Poppins-SemiBold";
                text-align: center;
                margin-bottom: 28px;
            }
        }

        .form-group {
	display: flex;
	input {
		width: 50%;
		&:first-child {
			margin-right: 25px;
		}
	}
}
.form-wrapper {
	position: relative;
	i {
		position: absolute;
		bottom: 9px;
		right: 0;
	}
}
.form-control {
	border: 1px solid #333;
	border-top: none;
	border-right: none;
	border-left: none;
	display: block;
	width: 100%;
	height: 30px;
	padding: 0;
	margin-bottom: 25px;
	&::-webkit-input-placeholder { 
		font-size: 13px;
		color: #333;
		font-family: "Poppins-Regular";
	}
	&::-moz-placeholder { 
		font-size: 13px;
		color: #333;
		font-family: "Poppins-Regular";
	}
	&:-ms-input-placeholder { 
		font-size: 13px;
		color: #333;
		font-family: "Poppins-Regular";
	}
	&:-moz-placeholder { 
		font-size: 13px;
		color: #333;
		font-family: "Poppins-Regular";
	}
}

button {
	border: none;
	width: 164px;
	height: 51px;
	margin: auto;
	margin-top: 40px;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 0;
	background: #333;
	font-size: 15px;
	color: #fff;
	vertical-align: middle;
	-webkit-transform: perspective(1px) translateZ(0);
	transform: perspective(1px) translateZ(0);
	-webkit-transition-duration: 0.3s;
	transition-duration: 0.3s;
	i {
		margin-left: 10px;
		-webkit-transform: translateZ(0);
  		transform: translateZ(0);
	}
	&:hover, &:focus, &:active {
		i {
			-webkit-animation-name: hvr-icon-wobble-horizontal;
			animation-name: hvr-icon-wobble-horizontal;
			-webkit-animation-duration: 1s;
			animation-duration: 1s;
			-webkit-animation-timing-function: ease-in-out;
			animation-timing-function: ease-in-out;
			-webkit-animation-iteration-count: 1;
			animation-iteration-count: 1;
		}
	}
}

/* Added register link styling */
.register-link {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #555;
}

.register-link a {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}

.register-link a:hover {
    color: #000;
    text-decoration: underline;
}

/* Error message styling */
.error-message {
    color: #d9534f;
    text-align: center;
    margin-bottom: 15px;
    font-size: 14px;
}

@-webkit-keyframes hvr-icon-wobble-horizontal {
  16.65% {
    -webkit-transform: translateX(6px);
    transform: translateX(6px);
  }
  33.3% {
    -webkit-transform: translateX(-5px);
    transform: translateX(-5px);
  }
  49.95% {
    -webkit-transform: translateX(4px);
    transform: translateX(4px);
  }
  66.6% {
    -webkit-transform: translateX(-2px);
    transform: translateX(-2px);
  }
  83.25% {
    -webkit-transform: translateX(1px);
    transform: translateX(1px);
  }
  100% {
    -webkit-transform: translateX(0);
    transform: translateX(0);
  }
}
@keyframes hvr-icon-wobble-horizontal {
  16.65% {
    -webkit-transform: translateX(6px);
    transform: translateX(6px);
  }
  33.3% {
    -webkit-transform: translateX(-5px);
    transform: translateX(-5px);
  }
  49.95% {
    -webkit-transform: translateX(4px);
    transform: translateX(4px);
  }
  66.6% {
    -webkit-transform: translateX(-2px);
    transform: translateX(-2px);
  }
  83.25% {
    -webkit-transform: translateX(1px);
    transform: translateX(1px);
  }
  100% {
    -webkit-transform: translateX(0);
    transform: translateX(0);
  }
}
@media (max-width: 1199px) { 
	.wrapper {
		background-position: right center;
	}
}
@media (max-width: 991px) {
	.inner form {
		padding-top: 10px;
		padding-left: 30px;
		padding-right: 30px;
	}
}
@media (max-width: 767px) {
	.inner {
		display: block;
		flex-direction: column; /* Reset for mobile */
		.image-holder {
			width: 100%;
		}
		form {
			width: 100%;
			padding: 40px 0 30px;
		}
	}
	button {
		margin-top: 60px;
	}
}
    </style>
</head>

<body>
    <div class="wrapper" style="background-image: url(assets/images/abstract-blur-shopping-mall.jpg);">
        <div class="inner">
            <div class="image-holder">
                <img src="assets/images/registration-form-1.jpg">
            </div>
            <form action="#" method="post">
                <h3>Login Form</h3>
                
                <!-- Error message display -->
                <?php if(isset($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="form-wrapper">
                    <input type="email" name="email" placeholder="Email" required class="form-control"
                    <?php
                    if(isset($_GET["error"]) && $_GET["error"]=="email_not_found")
                    {
                      echo "style=border-bottom-color:'red'> ";
                      echo "<span style='color:red'>Invalid Email</span>";
                    }
                    else {
                      echo ">";
                    }
                    
                    ?>
                    <i class="zmdi zmdi-email"></i>
                </div>

                <div class="form-wrapper">
                    <input type="password" placeholder="Password" class="form-control" name="password" required
                                        <?php
                    if(isset($_GET["error"]) && $_GET["error"]=="wrong_password")
                    {
                      echo "style=border-bottom-color:'red'> ";
                      echo "<span style='color:red'>Invalid Password</span>";
                    }
                    else {
                      echo ">";
                    }
                    
                    ?>
                    <i class="zmdi zmdi-lock"></i>
                </div>
                
                <button type="submit" name="client_login" >Login as Customer <i class="zmdi zmdi-arrow-right"></i></button>
                <button type="submit" name="login_admin" class="btn-admin">
                        <i class="zmdi zmdi-arrow-right"></i> Login as Admin
                    </button>
                
                <!-- Register link -->
                <div class="register-link">
                    Don't have an account? <a href="register.php">Register here</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
