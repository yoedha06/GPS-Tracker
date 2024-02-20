<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Splash Screen</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }

        #splash-screen {
            position: fixed;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        #content {
            display: none;
        }
    </style>
</head>

<body>
    <div id="splash-screen">
        <img src="https://cdn.dribbble.com/users/1595839/screenshots/12327466/media/76bf93a21483ac790702bd19a20f0be5.gif" alt="Logo">
    </div>

    <script>
        // Remove splash screen after a few seconds (e.g., 5 seconds)
        setTimeout(function () {
            document.getElementById('splash-screen').style.display = 'none';
            document.getElementById('content').style.display = 'block';
            document.body.style.overflow = 'auto';
        }, 2000);
    </script>
</body>

</html>
