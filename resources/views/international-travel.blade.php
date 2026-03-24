<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" href="{{ asset('assets/favicon/favicon.ico') }}" type="image/x-icon">
    <script type="text/javascript" src="darkmode.js" defer></script>
    <title>PITBI Portal | International Travel</title>
    <style>
        .travel-header {
            text-align: center;
            font-family: var(--header-font);
            font-size: 3rem;
            color: var(--text-color);
            padding-top: 3rem;
            margin-bottom: 2rem;
        }

        .travel-card {
            max-width: 1100px;
            margin: 1rem auto;
            padding: 2rem;
            background-color: var(--white-bg);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .travel-description {
            line-height: 1.6;
            margin-bottom: 2rem;
            color: var(--text-color);
        }

        .travel-list-title {
            font-family: var(--header-font);
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #eee;
            padding-bottom: 0.5rem;
        }

        .uni-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        @media (min-width: 768px) {
            .uni-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .uni-item {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            padding: 1rem;
            border: 1px solid #eef0f2;
            border-radius: 10px;
            background: #fff;
            transition: all 0.3s ease;
        }

        .uni-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-color);
        }

        .uni-logo {
            width: 65px;
            height: 65px;
            object-fit: contain;
            border-radius: 8px;
            background-color: #f8f9fa;
            flex-shrink: 0;
            padding: 5px;
        }

        .uni-info {
            display: flex;
            flex-direction: column;
        }

        .uni-name {
            font-family: var(--header-font);
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--primary-color);
            margin-bottom: 4px;
        }

        .uni-link {
            font-size: 0.85rem;
            color: #6c757d;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .uni-link i {
            font-size: 0.8rem;
        }

        .uni-link:hover {
            text-decoration: none;
            color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .travel-header {
                padding-top: 6rem;
                font-size: 2rem;
            }
            .travel-card {
                margin-inline: 1rem;
                padding: 1.5rem;
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

    <h2 class="travel-header">International Travel</h2>

    <div class="travel-card">
        <p class="travel-description">
            The following countries, State Universities and Colleges (SUCs), and Higher Education Institutions (HEIs) have a Memorandum of Agreement (MOA) with Palawan State University.
            These countries are authorized for travel under the PITBI (Palawan International Technology Business Incubator) and are subject to the guidelines of
            <em>CHED CMO No. 3, Series of 2025</em>.
        </p>

        <p class="travel-list-title">List of UNIIC members:</p>

        <div class="uni-grid">
            <div class="uni-item">
                <img src="{{ asset('assets/school/tvu.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=CTU'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Tra Vinh University (Vietnam)</span>
                    <a href="https://en.tvu.edu.vn/" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/ctu.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=CTU'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Can Tho University (Vietnam)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/hvu.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=HVU'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Hung Vuong University (Vietnam)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/trisakti.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=USK'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Universitas Trisakti (Indonesia)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/telkom.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=TEL'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Telkom University (Indonesia)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/umn.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=UMN'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Universitas Multimedia Nusantara (Indonesia)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/psu.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=PSU'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Palawan State University (Philippines)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/wpu.jpg') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=WPU'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Western Philippines University (Philippines)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/minsu.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=MIN'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Mindoro State University (Philippines)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/adamson.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=AdU'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Adamson University (Philippines)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/omsc.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=OMS'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Occidental Mindoro State College (Philippines)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/uc.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=UC'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">University of the Cordilleras (Philippines)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/dost.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=DOS'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">DOST (Philippines)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/nuck.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=NUC'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">NU Cheasim Kamchaymear (Cambodia)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/camtech.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=CAM'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">CamTech University (Cambodia)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/cit.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=CIT'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Coimbatore Institute of Tech (India)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/chitkara.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=CHI'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Chitkara University (India)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/apu.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=APU'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Asia Pacific University (Malaysia)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/ump.svg') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=UMP'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">University of Mpumalanga (SA)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/tut.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=TUT'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">Tshwane University of Tech (SA)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/nuk.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=NUK'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">National University of Kaohsiung (TW)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>

            <div class="uni-item">
                <img src="{{ asset('assets/school/ajou.png') }}" class="uni-logo" onerror="this.src='https://via.placeholder.com/65?text=AJU'" alt="Logo">
                <div class="uni-info">
                    <span class="uni-name">AJOU University (Uzbekistan)</span>
                    <a href="#" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="js/darkmode.js"></script>
    <script src="js/main.js"></script>
</body>

</html>