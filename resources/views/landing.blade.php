@php
    use App\Models\User;
    use Spatie\Permission\Models\Role;

    $startupCount = DB::table('startups')->count();
    $eventCount = DB::table('events')->count();
    $mentorCount = DB::table('mentors')->count();

    // Check if the roles exist first
    $investorRole = Role::where('name', 'investors')->first();
    $incubateeRole = Role::where('name', 'incubatees')->first();

    // Count users safely
    $investorCount = $investorRole ? User::role('investors')->count() : 0;
    $incubateeCount = $incubateeRole ? User::role('incubatees')->count() : 0;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script type="text/javascript" src="darkmode.js" defer></script>
    <title>Pitbi Portal | Tech Incubator</title>
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
          <li><a href="#">Contact</a></li>
          <li><a href="{{ url('/portal/login') }}" class="btn sign__in">Log In</a></li>
        </ul>
        <div class="nav__btns">
          <button id="theme-switch">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#808080"><path d="M480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z"/></svg>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#808080"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z"/></svg>
          </button>
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
                <span><i class="ri-calendar-event-line"></i></span>
                Incubatees: <span>{{ $incubateeCount }}</span>
            </div>
            <div class="header__image__card header__image__card-5">
                <span><i class="ri-calendar-event-line"></i></span>
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
            <form action="{{ url('/portal/login') }}">
                <button type="submit">Join The Program</button>
            </form>
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
