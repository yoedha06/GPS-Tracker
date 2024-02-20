@extends('layout.load')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage GPS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            margin: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* CSS for header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f5f5f5;
            padding: 10px 80px;
        }

        .header .logo-container {
            display: flex;
            align-items: center;
        }

        .header .logo {
            font-size: 25px;
            font-family: 'Sriracha', cursive;
            color: #000;
            text-decoration: none;
            margin-left: 10px;
        }

        .header .logo img {
            width: 150px;
            height: 70px;
        }

        .header .explorer {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-left: 10px;
        }

        .header .explorer span {
            font-size: 18px;
            color: #333333;
        }

        .nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
            background-color: #f5f5f5;
            margin-right: 20px;
        }

        .nav-items a {
            text-decoration: none;
            color: #000;
            padding: 35px 20px;
        }

        /* CSS for main element */
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .intro {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 520px;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.5) 100%),
                url('{{ asset('images/BG.webp') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin-bottom: 20px;
        }

        .intro h1 {
            font-family: sans-serif;
            font-size: 60px;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .intro p {
            font-size: 20px;
            color: #d1d1d1;
            text-transform: uppercase;
            margin: 20px 0;
            text-align: center;
        }

        .intro a {
            text-decoration: none;
        }

        .intro button {
            background-color: #5edaf0;
            color: #000;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.4);
        }

        .achievements {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            padding: 40px 80px;
            margin-bottom: 20px;
        }

        .achievements .work {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 40px;
            margin-bottom: 20px;
            cursor: pointer;
            /* Add cursor pointer for clickable effect */
            transition: transform 0.3s ease;
            /* Add transition for smooth effect */
        }

        .achievements .work:hover {
            transform: scale(1.1);
            /* Add scaling effect on hover */
        }

        .achievements .work i {
            width: fit-content;
            font-size: 50px;
            color: #333333;
            border-radius: 50%;
            border: 2px solid #333333;
            padding: 12px;
        }

        .achievements .work .work-heading {
            font-size: 20px;
            color: #333333;
            text-transform: uppercase;
            margin: 10px 0;
        }

        .achievements .work .work-text {
            font-size: 15px;
            color: #585858;
            margin: 10px 0;
            text-align: center;
        }

        .achievements table {
            width: 100%;
            border-collapse: collapse;
        }

        .achievements td {
            border: 2px solid #333333;
            padding: 20px;
            text-align: center;
        }


        @media screen and (max-width: 768px) {

            /* Adjust styles for smaller screens here */
            .achievements table {
                width: auto;
            }

            .achievements td {
                padding: 10px;
            }

            /* Add more responsive styling as needed */
        }

        .about-me {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 40px 80px;
            border-top: 2px solid #eeeeee;
            margin-bottom: 20px;
        }

        .about-me img {
            width: 500px;
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .about-me-text {
            text-align: center;
        }

        .about-me-text h2 {
            font-size: 30px;
            color: #333333;
            text-transform: uppercase;
            margin: 0;
        }

        .about-me-text p {
            font-size: 15px;
            color: #585858;
            margin: 10px 0;
            text-align: center;
        }

        /* CSS for footer */
        .footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #302f49;
            padding: 40px 80px;
        }

        .footer .copy {
            color: #fff;
            margin-bottom: 20px;
        }

        .bottom-links {
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
        }

        .bottom-links .links {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .bottom-links .links span {
            font-size: 20px;
            color: #fff;
            text-transform: uppercase;
            margin: 10px 0;
        }

        .bottom-links .links a {
            text-decoration: none;
            color: #a1a1a1;
            padding: 10px 20px;
        }

        body {
            margin: 0;
            overflow: hidden;
        }

        #splash-screen {
            position: fixed;
            width: 100%;
            height: 100%;
            background: #ffffff;
            /* You can customize the background color */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* Set a high z-index value to make it appear on top */
        }

        #splash-screen img {
            width: 150px;
            /* Adjust the width as needed */
            height: 70px;
            /* Adjust the height as needed */
        }

            {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            color: #000;
            font-family: 'Nunito', sans-serif;
        }

        .testimonial {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding-bottom: 5rem;
        }

        h1 {
            margin: 20px 0;
        }

        .line {
            height: 2px;
            width: 6rem;
            background-color: #e26c4f;
            margin-bottom: calc(3rem + 2vmin);
        }

        .arrow-wrapper {
            position: relative;
            width: 70%;
            border-radius: 2rem;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
            overflow: hidden;
            place-items: center;
        }

        .review-wrap {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding-top: calc(2rem + 1vmin);
            width: 100%;
        }

        #imgBox {
            border-radius: 50%;
            width: calc(6rem + 4vmin);
            height: calc(6rem + 4vmin);
            position: relative;
            box-shadow: 5px -3px #e26c4f;
            background-size: cover;
            margin-bottom: calc(0.7rem + 0.5vmin);
        }

        #name {
            margin-bottom: calc(0.7rem + 0.5vmin);
            font-size: calc(1rem + 0.5vmin);
            letter-spacing: calc(0.1rem + 0.1vmin);
            font-weight: bold;
        }

        #profession {
            font-size: calc(0.8rem + 0.3vmin);
            margin-bottom: calc(0.7rem + 0.5vmin);
            color: #e26c4f;
        }

        #description {
            font-size: calc(0.8rem + 0.3vmin);
            width: 70%;
            max-width: 40rem;
            text-align: center;
            margin-bottom: calc(1.4rem + 1vmin);
            color: rgb(92, 92, 92);
            line-height: 2rem;
        }

        .arrow {
            width: calc(1.4rem + 0.6vmin);
            height: calc(1.4rem + 0.6vmin);
            border: solid #e26c4f;
            border-width: 0 calc(0.5rem + 0.2vmin) calc(0.5rem + 0.2vmin) 0;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .arrow:hover {
            transition: 0.3s;
            transform: scale(1.15);
        }

        .left-arrow-wrap {
            position: absolute;
            top: 50%;
            left: 5%;
            transform: rotate(135deg);
        }

        .right-arrow-wrap {
            position: absolute;
            top: 50%;
            right: 5%;
            transform: rotate(-45deg);
        }

        @media screen and (max-width: 900px) {
            .testimonial {
                width: 100%;
            }
        }
    </style>

    </style>
</head>

<body>
    <div id="splash-screen">
        <img src="https://cdn.dribbble.com/users/1595839/screenshots/12327466/media/76bf93a21483ac790702bd19a20f0be5.gif"
            alt="Logo" style="width: 300px; height: 300px;">
    </div>

    <header class="header">
        <div class="logo-container">
            <a href="" class="logo">
                <img src="/images/gpslogo.png" alt="Logo" style="width: 150px; height: 70px;">
            </a>
            <div class="explorer">
                <span>GPS EXPLORER</span>
            </div>
        </div>
        <nav class="nav-items">
            <a href="#home">Home</a>
            <a href="#achievements">About</a>
            <a href="#about">Contact</a>
        </nav>
    </header>

    <main>
        <div id="home" class="intro">
            <h1>GPS EXPLORER</h1>
            <p>Welcome to GPS Explorer, your compass to thrilling adventures. Embark on a journey to explore the wonders
                of the world with us!</p>
            <a href="/">
                <button>Learn More</button>
            </a>
        </div>

        <div id="achievements" class="achievements">
            <table>
                <tr>
                    <td class="work">
                        <i class="fas fa-atom"></i>
                        <p class="work-heading">GPS Projects</p>
                        <p class="work-text">Embarked on numerous GPS-related projects, showcasing expertise in
                            navigation and exploration. Always eager to take on new challenges in the realm of GPS
                            technology.</p>
                    </td>
                    <td class="work">
                        <i class="fas fa-skiing"></i>
                        <p class="work-heading">GPS Skills</p>
                        <p class="work-text">Possess a diverse set of skills in the GPS domain. Proficient in developing
                            and enhancing GPS technologies, ensuring precise and efficient navigation experiences for
                            users.</p>
                    </td>
                    <td class="work">
                        <i class="fas fa-ethernet"></i>
                        <p class="work-heading">GPS Network</p>
                        <p class="work-text">Extensive knowledge in GPS network systems. Excelling in the intricacies of
                            GPS communication and connectivity, committed to staying at the forefront of advancements in
                            GPS networking.</p>
                    </td>
                </tr>
            </table>
        </div>

        <div id="about" class="about-me">
            <img src="https://ak.picdn.net/shutterstock/videos/13255625/thumb/1.jpg" alt="GPS Explorer">
            <div class="about-me-text">
                <h2>About GPS Explorer</h2>
                <p>Welcome to GPS Explorer! We are passionate about exploring the world and providing innovative GPS
                    solutions. Our mission is to make navigation seamless and enjoyable for every adventurer.</p>
            </div>
        </div>


        <div class="testimonial">
            <h1>My Team</h1>
            <div class="line"></div>
            <!-- arrow wrapper contains the review and the arrows -->
            <div class="arrow-wrapper">
                <!-- review section -->
                <div id="reviewWrap" class="review-wrap">
                    <div id="imgBox"></div>
                    <div id="name"></div>
                    <div id="profession"></div>
                    <div id="description"></div>
                </div>
                <!-- left arrow -->
                <div class="left-arrow-wrap">
                    <div class="arrow"></div>
                </div>
                <!-- right arrow -->
                <div class="right-arrow-wrap">
                    <div class="arrow"></div>
                </div>
            </div>
        </div>

    </main>
    <footer class="footer">
        <div class="copy">&copy; 2024 BARUDAK CIGS</div>
        <div class="bottom-links">
            <div class="links">
                <span>More Info</span>
                <a href="#home">Home</a>
                <a href="#achievements">About</a>
                <a href="#about">Contact</a>
            </div>
            <div class="links">
                <span>Social Links</span>
                <a href="#"><i class="fab fa-facebook"> Facebook</i></a>
                <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Remove splash screen after a few seconds (e.g., 3 seconds)
            setTimeout(function() {
                document.getElementById('splash-screen').style.display = 'none';
                document.body.style.overflow = 'auto';
            }, 2000);
        });
        const reviewWrap = document.getElementById("reviewWrap");
        const leftArrow = document.querySelector(".left-arrow-wrap .arrow");
        const rightArrow = document.querySelector(".right-arrow-wrap .arrow");
        const imgBox = document.getElementById("imgBox");
        const name = document.getElementById("name");
        const profession = document.getElementById("profession");
        const description = document.getElementById("description");

        let people = [{
                photo: 'url("https://thumbs.dreamstime.com/b/avatar-man-shirt-avatar-face-single-icon-cartoon-style-rater-bitmap-symbol-stock-illustration-web-91847976.jpg")',
                name: "Dzaki Ahmad Fauzan",
                profession: "CIGS",
                description: "Belajarlah Dengan Sungguh-Sungguh,Kejarlah Gelar Sampai Dapat Dan Jangan Lupakan Gelar Sejadah"
            },
            {
                photo: "url('https://thumbs.dreamstime.com/z/avatar-men-white-hair-avatar-face-single-icon-cartoon-style-rater-bitmap-symbol-stock-illustration-web-91847888.jpg')",
                name: "Yuda Hidayat",
                profession: "CIGS",
                description: "Sabar adalah kunci kesuksesan. Bersabarlah dalam menghadapi cobaan, karena Allah selalu bersama orang yang sabar."
            },
            {
                photo: "url('https://image.freepik.com/free-vector/businessman-profile-cartoon_18591-58479.jpg')",
                name: "Ryan Rahma Bakti",
                profession: "CIGS",
                description: "Doa adalah senjata seorang mukmin. Teruslah berdoa, karena Allah mendengar setiap doa dari hati yang tulus."
            },
            {
                photo: "url('https://as2.ftcdn.net/v2/jpg/01/40/33/03/1000_F_140330375_e7tnFRYyvlcL7TwX5e0uo1zWARI1RmOw.jpg')",
                name: "Chepi Syaehbudien Basil",
                profession: "CIGS",
                description: "Keberhasilan sejati adalah ketika kita meraih keridhaan Allah, bukan sekadar pujian dari manusia."
            }
        ];

        // set the first person
        imgBox.style.backgroundImage = people[0].photo;
        name.innerText = people[0].name;
        profession.innerText = people[0].profession;
        description.innerText = people[0].description;
        let currentPerson = 0;

        //Select the side where you want to slide
        function slide(side, personNumber) {
            let reviewWrapWidth = reviewWrap.offsetWidth + "px";
            let descriptionHeight = description.offsetHeight + "px";
            //(+ or -)
            let side1symbol = side === "left" ? "" : "-";
            let side2symbol = side === "left" ? "-" : "";

            setTimeout(() => {
                imgBox.style.backgroundImage = people[personNumber].photo;
            }, 0);
            setTimeout(() => {
                description.style.height = descriptionHeight;
            }, 100);
            setTimeout(() => {
                name.innerText = people[personNumber].name;
            }, 200);
            setTimeout(() => {
                profession.innerText = people[personNumber].profession;
            }, 300);
            setTimeout(() => {
                description.innerText = people[personNumber].description;
            }, 400);
        }

        function setNextCardLeft() {
            if (currentPerson === 3) {
                currentPerson = 0;
                slide("left", currentPerson);
            } else {
                currentPerson++;
            }

            slide("left", currentPerson);
        }

        function setNextCardRight() {
            if (currentPerson === 0) {
                currentPerson = 3;
                slide("right", currentPerson);
            } else {
                currentPerson--;
            }

            slide("right", currentPerson);
        }

        leftArrow.addEventListener("click", setNextCardLeft);
        rightArrow.addEventListener("click", setNextCardRight);
    </script>
</body>

</html>
