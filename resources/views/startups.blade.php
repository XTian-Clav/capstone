<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" href="{{ asset('assets/favicon/favicon.ico') }}" type="image/x-icon">
    <script type="text/javascript" src="darkmode.js" defer></script>
    <title>PITBI Portal | Startups</title>
    <style>
        .startups-header {
            text-align: center;
            font-family: var(--header-font);
            font-size: 3rem;
            color: var(--text-color);
            padding-top: 3rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .startups-header {
                padding-top: 6rem;
                font-size: 2rem;
            }
        }

        .startups-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: var(--max-width);
            margin: auto;
            padding: 0 1rem 4rem 1rem;
        }

        .startup-card {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: 10px;
            border: 2px solid;
            border-color: transparent;
            background-color: var(--white-bg);
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            align-items: center;
            text-align: center;
        }

        .startup-card:hover {
            transform: translateY(-5px);
            border: 2px solid;
            border-color: var(--primary-color);
        }

        .startup-card img {
            width: 100%;
            max-width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .startup-card h3 {
            font-family: var(--header-font);
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            letter-spacing: 1.5px;
            margin-bottom: 0.25rem;
        }

        .startup-card p {
            font-size: 0.95rem;
            color: var(--text-dark);
            line-height: 1.5rem;
            text-align: justify;
            display: -webkit-box;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }

        .pagination .page-link { font-family: 'Poppins', sans-serif; color: #333; }
        .pagination .page-item.active .page-link { background: #FE800D; border-color: #FE800D; color: #fff; }
        .pagination .page-link:focus { box-shadow: none; outline: none; }
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

    <h2 class="startups-header">Ongoing Startups</h2>

    <div class="startups-container">
        @forelse($startups as $startup)
            <div class="startup-card">
                <img src="{{ $startup->logo_url }}" alt="{{ $startup->startup_name }}" class="startup-logo">
                <h3>{{ $startup->startup_name }}</h3>
                <p>{{ Str::limit(strip_tags($startup->description), 200) }}</p>
                <p><strong>Founder:</strong> {{ $startup->founder }}</p>
                @if ($startup->url)
                    <a href="{{ $startup->url }}" target="_blank">Visit Website</a>
                @endif
            </div>
        @empty
            <p style="text-align:center; color: var(--text-dark)">No startups found.</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-center my-3">
        {{ $startups->links('pagination::bootstrap-5') }}
    </div>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="js/darkmode.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
