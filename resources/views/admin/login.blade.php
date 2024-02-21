<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            margin-top: 100px;
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(150deg, #1B394D 60%, #2D9DA7 40%, #2D9DA7 30%, #EC5F20 100%);
            color: #fff;
            text-align: center;
            padding: 30px 20px 50px;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
            max-width: 400px;
            margin: 0 auto;
        }

        .form-container .title {
            font-size: 23px;
            text-transform: capitalize;
            letter-spacing: 1px;
            margin: 0 0 40px;
        }

        .form-icon {
            color: #fff;
            background-color: #1B394D;
            font-size: 75px;
            line-height: 75px;
            height: 75px;
            width: 75px;
            margin: -55px auto 20px;
            border-radius: 50%;
        }

        .form-group {
            margin: 0 0 15px;
            position: relative;
        }

        .form-group:nth-child(3) {
            margin-bottom: 30px;
        }

        .input-icon {
            color: #e7e7e7;
            font-size: 23px;
            position: absolute;
            left: 0;
            top: 12px;
        }

        .form-control {
            color: #000;
            font-size: 16px;
            font-weight: 600;
            height: 40px; /* Adjusted height */
            padding: 5px 10px 5px 40px; /* Adjusted padding */
            margin: 0 0 5px;
            border: none;
            border-bottom: 2px solid #e7e7e7;
            border-radius: 0px;
            box-shadow: none;
            width: 100%;
            box-sizing: border-box;
        }

        .form-control:focus {
            box-shadow: none;
            border-bottom-color: #EC5F20;
        }

        .form-control::placeholder {
            color: #000;
            font-size: 16px;
            font-weight: 600;
        }

        .forgot {
            font-size: 13px;
            font-weight: 600;
            text-align: right;
            display: block;
            color: #777;
            margin-top: 10px;
        }

        .forgot a {
            color: #777;
            transition: all 0.3s ease 0s;
        }

        .forgot a:hover {
            color: #777;
            text-decoration: underline;
        }

        .signin {
            color: #fff;
            background-color: #EC5F20;
            font-size: 17px;
            text-transform: capitalize;
            letter-spacing: 2px;
            width: 100%;
            padding: 10px; /* Adjusted padding */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            transition: all 0.4s ease 0s;
            cursor: pointer;
        }

        .signin:hover,
        .signin:focus {
            font-weight: 600;
            letter-spacing: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3) inset;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h3 class="title">My Account</h3>
        <form class="form-horizontal">
            <div class="form-icon">
                <i class="fa fa-user-circle"></i>
            </div>
            <div class="form-group">
                <span class="input-icon"><i class="fa fa-user"></i></span>
                <input type="email" class="form-control" placeholder="Username">
            </div>
            <div class="form-group">
                <span class="input-icon"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control" placeholder="Password">
                <span class="forgot"><a href="#">Forgot Password?</a></span>
            </div>
            <button class="btn signin">Login</button>
        </form>
    </div>
</body>

</html>