@php
    use App\Models\User;
    use Spatie\Permission\Models\Role;

    $startupCount = DB::table('startups')->count();
    $eventCount = DB::table('events')->count();
    $mentorCount = DB::table('mentors')->count();

    // Check if the roles exist first
    $investorRole = Role::where('name', 'investor')->first();
    $incubateeRole = Role::where('name', 'incubatee')->first();

    // Count users safely
    $investorCount = $investorRole ? User::role('investor')->count() : 0;
    $incubateeCount = $incubateeRole ? User::role('incubatee')->count() : 0;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" href="{{ asset('assets/favicon/favicon.ico') }}" type="image/x-icon">
    <script type="text/javascript" src="darkmode.js" defer></script>
    <title>Pitbi Portal | Tech Incubator</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--white-bg);
            color: var(--text-dark);
            padding: 2rem;
            border-radius: 10px;
            max-width: 600px;
            max-height: 400px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .modal-content {
                margin-left: 1.5rem;
                margin-right: 1.5rem;
            }
        }

        .modal-content h3 {
            margin-bottom: 1rem;
        }

        .modal-content h4 {
            font-weight: 500;
            line-height: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: justify;
        }

        .modal-close {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            background-color: var(--secondary-color);
            color: var(--text-white);
            cursor: pointer;
        }

        .modal-contact {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            color: var(--text-dark);
        }

        .modal-contact a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .modal-contact a:hover {
            color: var(--primary-color);
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav__header">
            <div class="nav__logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('assets/logo/light-theme-alt.png') }}" alt="Pitbi Portal" />
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
    <header class="header__container">
        <div class="header__image">
            <div class="header__image__card header__image__card-1">
                <span><i class="ri-building-4-line"></i></span>
                Startups: <span>{{ $startupCount }}</span>
            </div>
            <div class="header__image__card header__image__card-2">
                <span><i class="ri-user-star-line"></i></span>
                Mentors: <span>{{ $mentorCount }}</span>
            </div>
            <div class="header__image__card header__image__card-3">
                <span><i class="ri-calendar-event-line"></i></span>
                Events: <span>{{ $eventCount }}</span>
            </div>
            <div class="header__image__card header__image__card-4">
                <span><i class="ri-team-line"></i></span>
                Incubatees: <span>{{ $incubateeCount }}</span>
            </div>
            <div class="header__image__card header__image__card-5">
                <span><i class="ri-shake-hands-line"></i></span>
                Investors: <span>{{ $investorCount }}</span>
            </div>
            <img class="mobile-hide" src="{{ asset('assets/landing/header.png') }}" alt="header">
        </div>
        <div class="header__content">
            <h1><span class="secondary">PALAWAN INTERNATIONAL</span> <span>TECHONOLOGY BUSINESS INCUBATOR</span> <span
                    class="secondary">| PORTAL</span></h1>
            <p>
              Fostering the future of startups in MIMAROPA by providing essential resources,
              mentorship, and a supportive ecosystem to transform research and innovative ideas into sustainable,
              commercially viable businesses.
            </p>
            <form>
                <button type="button"
                    onclick="document.getElementById('registerModal').classList.toggle('active')">
                    Join The Program
                </button>
            </form>
            <div id="registerModal" class="modal" onclick="if(event.target === this) this.classList.remove('active')">
                <div class="modal-content">
                    <h3>Registration Notice</h3>
                    <h4>
                        To register an account, both incubatees and investors must contact the PITBI administrators.
                        They need to submit their legal documents before an account can be created.
                    </h4>
                    <div class="modal-contact">
                        <a href="https://facebook.com/PalawanITBI" target="_blank">
                            <i class="ri-facebook-box-fill" style="margin-right: 0.5rem;"></i>Facebook
                        </a> |
                        <a href="mailto:contact@pitbi.com">
                            <i class="ri-mail-fill" style="margin-right: 0.5rem;"></i>Email
                        </a>
                    </div>
                    <button type="button" class="modal-close"
                        onclick="document.getElementById('registerModal').classList.remove('active')">
                        Close
                    </button>
                </div>
            </div>            
            <div class="bar">
                Copyright © 2025 Palawan International Technology Business Incubator.<br>All rights reserved.
            </div>
        </div>
    </header>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="js/darkmode.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
