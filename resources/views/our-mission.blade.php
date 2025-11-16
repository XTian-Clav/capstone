<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script type="text/javascript" src="darkmode.js" defer></script>
    <title>PITBI Portal | Our Mission</title>
    <style>
        .mission-header {
            text-align: center;
            font-family: var(--header-font);
            font-size: 3rem;
            color: var(--text-color);
            padding-top: 3rem;
            margin-bottom: 2rem;
        }

        .mission-card {
            max-width: 1000px;
            margin: 1rem auto;
            padding: 1.5rem 2rem;
            background-color: var(--white-bg);
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .mission-card:hover {
            transform: translateY(-5px);
            border: 1px solid;
            border-color: var(--primary-color);
        }

        .mission-question {
            font-family: var(--header-font);
            font-size: 1.5rem;
            font-weight: 400;
            color: var(--primary-color);
            letter-spacing: 0.8px;
            cursor: pointer;
        }

        .mission-answer {
            margin-top: 0.75rem;
            font-size: 1rem;
            line-height: 1.5rem;
            color: var(--text-dark);
        }

        @media (max-width: 768px) {
            .mission-header {
                padding-top: 6rem;
                font-size: 2rem;
            }

            .mission-card {
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
            <li><a href="#">Contact</a></li>
            <li><a href="{{ url('/portal/login') }}" class="btn sign__in">Log In</a></li>
        </ul>
        <div class="nav__btns">
            <a href="{{ url('/portal/login') }}" class="btn sign__in">Log In</a>
        </div>
    </nav>

    <body>
        <h2 class="mission-header">Our Mission</h2>
    
        <div class="mission-card">
            <div class="mission-question"><i class="ri-lightbulb-line" style="margin-right: 0.5rem;"></i>Technology Transfer</div>
            <div class="mission-answer" style="display: block;">
                Efficiently transfer university-produced technology and research into scalable, real-world applications for the public and private sectors.
            </div>
        </div>
        
        <div class="mission-card">
            <div class="mission-question"><i class="ri-briefcase-line" style="margin-right: 0.5rem;"></i>Job Creation & Growth</div>
            <div class="mission-answer" style="display: block;">
                Empower local entrepreneurs, fostering job creation and stimulating economic expansion within the MIMAROPA region and beyond.
            </div>
        </div>
        
        <div class="mission-card">
            <div class="mission-question"><i class="ri-group-line" style="margin-right: 0.5rem;"></i>Collaborative Ecosystem</div>
            <div class="mission-answer" style="display: block;">
                Build a strong network of partners, mentors, and investors to support start-ups from ideation to commercialization.
            </div>
        </div>        
        <br>

        <script src="https://unpkg.com/scrollreveal"></script>
        <script src="js/darkmode.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>