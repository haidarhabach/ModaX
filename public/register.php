<html>

<?php
include('C:\xampp\htdocs\ModaX\includes\db.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $reslt = mysqli_query($connect, "select id from users");
    $id = mt_rand(1, 10000000);
    while ($row = mysqli_fetch_assoc($reslt)) {
        if ($row["id"] != $id) {
            break;
        } else {
            $id = mt_rand(1, 10000000);
        }
    }

    $name = $_POST["name"];
    $pass = $_POST["password"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $query = "INSERT INTO users values($id,'$name','$email','$pass','$phone',now())";
    $valid = mysqli_query($connect, $query);
    if ($valid) {
        header("Location:login.php");
    } else {
        header("Location:register.php");
    }
}


?>

<head>
    <title>Register</title>
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

.login-link {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #555;
}

.login-link a {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}

.login-link a:hover {
    color: #000;
    text-decoration: underline;
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
                <h3>Registration Form</h3>
                <div class="form-wrapper">
                    <input type="text" name="name" placeholder="Name" required class="form-control">
                    <i class="zmdi zmdi-account"></i>
                </div>
                <div class="form-wrapper">
                    <input type="tel" name="phone" placeholder="phone number" required class="form-control">
                    <i class="zmdi zmdi-account"></i>
                </div>
                <div class="form-wrapper">
                    <input type="email" name="email" placeholder="email" required class="form-control">
                    <i class="zmdi zmdi-email"></i>
                </div>

                <div class="form-wrapper">
                    <input type="password" placeholder="Password" class="form-control" name="password" required>
                    <i class="zmdi zmdi-lock"></i>
                </div>
                <div class="form-wrapper">
                    <input type="password" placeholder="Confirm Password" class="form-control" name="password" required>
                    <i class="zmdi zmdi-lock"></i>
                </div>
                <button type="submit">Register <i class="zmdi zmdi-arrow-right"></i></button>
                
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
