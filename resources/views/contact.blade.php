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

        .contact-card:hover {
            transform: translateY(-5px);
            border: 1px solid;
            border-color: var(--primary-color);
        }

        .contact-question {
            font-family: var(--header-font);
            font-size: 1.5rem;
            font-weight: 400;
            color: var(--primary-color);
            letter-spacing: 0.8px;
            cursor: pointer;
        }

        .contact-answer {
            margin-top: 0.75rem;
            font-size: 1rem;
            line-height: 1.5rem;
            color: var(--text-dark);
        }

        .contact-answer a {
            text-decoration: none;
            color: #1877f2;
            font-weight: 500;
        }

        .contact-answer a:hover {
            text-decoration: underline;
            opacity: 0.8;
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
            <li><a href="{{ url('/portal/login') }}" class="btn sign__in">Log In</a></li>
        </ul>
        <div class="nav__btns">
            <a href="{{ url('/portal/login') }}" class="btn sign__in">Log In</a>
        </div>
    </nav>

    <body>
        <h2 class="contact-header">Contact</h2>
    
        <div class="contact-card">
            <div class="contact-question"><i class="ri-facebook-box-fill" style="margin-right: 0.5rem;"></i>Facebook</div>
            <div class="contact-answer" style="display: block;">
                <a href="https://facebook.com/PalawanITBI" target="_blank">
                    https://facebook.com/PalawanITBI
                </a>
            </div>
        </div>
        
        <div class="contact-card">
            <div class="contact-question"><i class="ri-mail-fill" style="margin-right: 0.5rem;"></i>Email</div>
            <div class="contact-answer" style="display: block;">
                palawanitbi@psu.palawan.edu.ph
            </div>
        </div>
        
        <div class="contact-card">
            <div class="contact-question"><i class="ri-map-pin-fill" style="margin-right: 0.5rem;"></i>Location</div>
            <div class="contact-answer" style="display: block;">
                1st Floor, Student's Innovation Park Building, Palawan State University-Main Campus, Tiniguiban Heights, 5300 Puerto Princesa, Philippines
            </div>
        </div>        
        <br>

        <script src="https://unpkg.com/scrollreveal"></script>
        <script src="js/darkmode.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>