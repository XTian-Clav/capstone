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
