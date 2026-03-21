<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" href="{{ asset('assets/favicon/favicon.ico') }}" type="image/x-icon">
    <script type="text/javascript" src="darkmode.js" defer></script>
    <title>PITBI Portal | Contact</title>
    <style>
        .contact-header {
            text-align: center;
            font-family: var(--header-font);
            font-size: 3rem;
            color: var(--text-color);
            padding-top: 3rem;
            margin-bottom: 2rem;
        }

        .contact-card {
            max-width: 1000px;
            margin: 1rem auto;
            padding: 1.5rem 2rem;
            background-color: var(--white-bg);
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .contact-question {
            font-family: var(--header-font);
            font-size: 1.5rem;
            font-weight: 400;
            color: var(--primary-color);
            letter-spacing: 0.8px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .contact-header {
                padding-top: 6rem;
                font-size: 2rem;
            }

            .contact-card {
                margin-inline: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav__header">
            <div class="nav__logo">
                <a href="/">
                    <img src="assets/logo/light-theme-alt.png" alt="Pitbi Portal" />
                </a>
            </div>
            <div class="nav__menu__btn" id="menu-btn">
                <span><i class="ri-menu-line"></i></span>
            </div>
        </div>
        <ul class="nav__links" id="nav-links">
            <li><a href="{{ url('/startups') }}">Startups</a></li>
            <li><a href="{{ url('/our-mission') }}">Our Mission</a></li>
            <li><a href="{{ url('/faqs') }}">FAQ</a></li>
            <li><a href="{{ url('/contact') }}">Contact</a></li>
            <li><a href="{{ url('/international-travel') }}">International Travel</a></li>
            <li><a href="{{ url('/portal/login') }}" class="btn sign__in">Log In</a></li>
        </ul>
        <div class="nav__btns">
            <a href="{{ url('/portal/login') }}" class="btn sign__in">Log In</a>
        </div>
    </nav>

    <body>
        <h2 class="contact-header">International Travel</h2>
    
        <div class="contact-card">
            <p>
                The following countries, State Universities and Colleges (SUCs), and Higher Education Institutions (HEIs) have a Memorandum of Agreement (MOA) with Palawan State University. 
                These countries are authorized for travel under the PITBI (Palawan International Technology Business Incubator) and are subject to the guidelines of
                <em>CHED CMO No. 3, Series of 2025</em>.
            </p>
            
            <br>
            
            <p class="contact-question">List of UNIIC members:</p>
            <ul style="list-style-type: disc; margin-left: 20px; margin-top: 10px;">
                <li>Tra Vinh University (Vietnam)</li>
                <li>Can Tho University (Vietnam)</li>
                <li>Hung Vuong University Ho Chi Minh City (Vietnam)</li>
                <li>Universitas Trisakti (Indonesia)</li>
                <li>Telkom University, Bandung (Indonesia)</li>
                <li>Universitas Multimedia Nusantara (Indonesia)</li>
                <li>Palawan State University (Philippines)</li>
                <li>Western Philippines University (Philippines)</li>
                <li>Mindoro State University (Philippines)</li>
                <li>Adamson University (Philippines)</li>
                <li>Occidental Mindoro State College (Philippines)</li>
                <li>University of the Cordilleras (Philippines)</li>
                <li>Department of Science & Technology (Philippines)</li>
                <li>National University of Cheasim Kamchaymear (Cambodia)</li>
                <li>Cambodia University of Technology & Science (Cambodia)</li>
                <li>Coimbatore Institute of Technology (India)</li>
                <li>Chitkara University (India)</li>
                <li>Asia Pacific University of Technology & Innovation (Malaysia)</li>
                <li>University of Mpumalanga (South Africa)</li>
                <li>Sefako Makgatho Health Sciences University (South Africa)</li>
                <li>Mangosuthu University of Technology (South Africa)</li>
                <li>Tshwane University of Technology (South Africa)</li>
                <li>National University of Kaohsiung (Taiwan)</li>
                <li>Millat Umidi University (Uzbekistan)</li>
                <li>AJOU University (Uzbekistan)</li>
                <li>Asian Society of Innovation & Policy (South Korea)</li>
            </ul>
        </div>

        <script src="https://unpkg.com/scrollreveal"></script>
        <script src="js/darkmode.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>