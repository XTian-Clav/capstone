<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
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

        .country-section {
            margin-bottom: 3rem;
        }

        .country-name {
            font-family: var(--header-font);
            font-size: 1.25rem;
            font-weight: 200;
            color: var(--text-white);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px;
            border-radius: 10px;
            background-color: var(--primary-color);
            padding-left: 15px;
        }

        .travel-list-title {
            font-family: var(--header-font);
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 1rem;
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
            width: 90px;
            height: 90px;
            object-fit: contain;
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
            color: var(--text-color);
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

        .evisa-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
        }

        .evisa-card {
            background: var(--white-bg);
            border: 1px solid #eef0f2;
            padding: 1.5rem;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .evisa-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-color);
        }

        .evisa-flag {
            width: 40px;
            height: 30px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .evisa-card:hover .evisa-flag {
            transform: scale(1.15);
        }

        .evisa-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.2rem;
        }

        .evisa-icon {
            width: 40px;
            height: 40px;
            background: #f0f7ff;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1.4rem;
        }

        .evisa-country {
            font-family: var(--header-font);
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-color);
        }

        .btn-apply {
            color: #64748b;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 0;
            transition: color 0.2s ease;
            border-top: 1px solid #f1f5f9;
            margin-top: 4px;
            padding-top: 12px;
        }

        .btn-apply i {
            font-size: 1rem;
            transition: transform 0.2s ease;
        }

        .evisa-card:hover .btn-apply {
            color: var(--primary-color);
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
        <p class="travel-list-title">List of UNIIC members</p>
        <p class="travel-description">
            The following countries, State Universities and Colleges (SUCs), and Higher Education Institutions (HEIs) have a Memorandum of Agreement (MOA) with Palawan State University.
            These countries are authorized for travel under the PITBI (Palawan International Technology Business Incubator) and are subject to the guidelines of
            <em>CHED CMO No. 3, Series of 2025</em>.
        </p>
    
        <div class="country-section">
            <div class="country-name"><i class="ri-map-pin-2-line"></i> Cambodia</div>
            <div class="uni-grid">
                <div class="uni-item">
                    <img src="{{ asset('assets/school/camtech.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Cambodia University of Technology & Science</span>
                        <a href="https://camtech.edu.kh/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/nuck.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">National University of Cheasim Kamchaymear</span>
                        <a href="https://nuck.edu.kh/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="country-section">
            <div class="country-name"><i class="ri-map-pin-2-line"></i> India</div>
            <div class="uni-grid">
                <div class="uni-item">
                    <img src="{{ asset('assets/school/chitkara.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Chitkara University</span>
                        <a href="https://www.chitkara.edu.in/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/cit.png') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Coimbatore Institute of Technology</span>
                        <a href="https://www.cit.edu.in/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="country-section">
            <div class="country-name"><i class="ri-map-pin-2-line"></i> Indonesia</div>
            <div class="uni-grid">
                <div class="uni-item">
                    <img src="{{ asset('assets/school/telkom.png') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Telkom University, Bandung</span>
                        <a href="https://telkomuniversity.ac.id/en/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/umn.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Universitas Multimedia Nusantara</span>
                        <a href="https://www.umn.ac.id/en/home/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/trisakti.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Universitas Trisakti</span>
                        <a href="https://trisakti.ac.id/en/home-2/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="country-section">
            <div class="country-name"><i class="ri-map-pin-2-line"></i> Malaysia</div>
            <div class="uni-grid">
                <div class="uni-item">
                    <img src="{{ asset('assets/school/apu.png') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Asia Pacific University of Technology & Innovation</span>
                        <a href="https://www.apu.edu.my/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="country-section">
            <div class="country-name"><i class="ri-map-pin-2-line"></i> Philippines</div>
            <div class="uni-grid">
                <div class="uni-item">
                    <img src="{{ asset('assets/school/adamson.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Adamson University</span>
                        <a href="https://www.adamson.edu.ph/2018/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/dost.png') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Department of Science & Technology (DOST)</span>
                        <a href="https://www.dost.gov.ph/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/minsu.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Mindoro State University</span>
                        <a href="https://www.minsu.edu.ph/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/omsc.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Occidental Mindoro State College</span>
                        <a href="https://www.omsc.edu.ph/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/psu.png') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Palawan State University</span>
                        <a href="https://psu.palawan.edu.ph/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/uc.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">University of the Cordilleras</span>
                        <a href="https://www.uc-bcf.edu.ph/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/wpu.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Western Philippines University</span>
                        <a href="http://wpu.edu.ph/home/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="country-section">
            <div class="country-name"><i class="ri-map-pin-2-line"></i> South Africa</div>
            <div class="uni-grid">
                <div class="uni-item">
                    <img src="{{ asset('assets/school/mut.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Mangosuthu University of Technology</span>
                        <a href="https://www.mut.ac.za/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/smhsu.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Sefako Makgatho Health Sciences University</span>
                        <a href="https://www.smu.ac.za/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/tut.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Tshwane University of Technology</span>
                        <a href="https://www.tut.ac.za/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/ump.svg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">University of Mpumalanga</span>
                        <a href="https://www.ump.ac.za/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="country-section">
            <div class="country-name"><i class="ri-map-pin-2-line"></i> South Korea</div>
            <div class="uni-grid">
                <div class="uni-item">
                    <img src="{{ asset('assets/school/asip.png') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Asian Society of Innovation & Policy</span>
                        <a href="http://www.innovationandpolicy.asia/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="country-section">
            <div class="country-name"><i class="ri-map-pin-2-line"></i> Taiwan</div>
            <div class="uni-grid">
                <div class="uni-item">
                    <img src="{{ asset('assets/school/nuk.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">National University of Kaohsiung</span>
                        <a href="https://www.nuk.edu.tw/?Lang=en" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="country-section">
            <div class="country-name"><i class="ri-map-pin-2-line"></i> Uzbekistan</div>
            <div class="uni-grid">
                <div class="uni-item">
                    <img src="{{ asset('assets/school/ajou.png') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">AJOU University</span>
                        <a href="https://www.ajou.ac.kr/en/index.do" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/millat.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Millat Umidi University</span>
                        <a href="https://millatumidi.uz/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="country-section">
            <div class="country-name"><i class="ri-map-pin-2-line"></i> Vietnam</div>
            <div class="uni-grid">
                <div class="uni-item">
                    <img src="{{ asset('assets/school/ctu.svg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Can Tho University</span>
                        <a href="https://en.ctu.edu.vn/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/hvu.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Hung Vuong University Ho Chi Minh City</span>
                        <a href="https://dhv.edu.vn/en/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
                <div class="uni-item">
                    <img src="{{ asset('assets/school/tvu.jpg') }}" class="uni-logo" alt="Logo">
                    <div class="uni-info">
                        <span class="uni-name">Tra Vinh University</span>
                        <a href="https://en.tvu.edu.vn/" target="_blank" rel="noopener noreferrer" class="uni-link"><i class="ri-global-line"></i> Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="travel-card">
        <p class="travel-list-title">E-Visa Portals</p>
        <p class="travel-description">Quick access to official electronic visa and entry systems for UNIIC member countries.</p>
    
        <div class="evisa-grid">
            <div class="evisa-card">
                <div class="evisa-info">
                    <span class="fi fi-kh evisa-flag"></span>
                    <span class="evisa-country">Cambodia</span>
                </div>
                <a href="https://www.cambodiaimmigration.org/apply-visa" target="_blank" class="btn-apply">
                    Apply E-Visa <i class="ri-external-link-line"></i>
                </a>
            </div>
    
            <div class="evisa-card">
                <div class="evisa-info">
                    <span class="fi fi-in evisa-flag"></span>
                    <span class="evisa-country">India</span>
                </div>
                <a href="https://www.indianimmigration.org/apply-visa" target="_blank" class="btn-apply">
                    Apply E-Visa <i class="ri-external-link-line"></i>
                </a>
            </div>
    
            <div class="evisa-card">
                <div class="evisa-info">
                    <span class="fi fi-id evisa-flag"></span>
                    <span class="evisa-country">Indonesia</span>
                </div>
                <a href="https://evisa.imigrasi.go.id/" target="_blank" class="btn-apply">
                    Apply E-Visa <i class="ri-external-link-line"></i>
                </a>
            </div>
    
            <div class="evisa-card">
                <div class="evisa-info">
                    <span class="fi fi-my evisa-flag"></span>
                    <span class="evisa-country">Malaysia</span>
                </div>
                <a href="https://malaysiavisa.imi.gov.my/evisa/login" target="_blank" class="btn-apply">
                    Apply E-Visa <i class="ri-external-link-line"></i>
                </a>
            </div>
    
            <div class="evisa-card">
                <div class="evisa-info">
                    <span class="fi fi-ph evisa-flag"></span>
                    <span class="evisa-country">Philippines</span>
                </div>
                <a href="https://etravel.gov.ph/" target="_blank" class="btn-apply">
                    eTravel Portal <i class="ri-external-link-line"></i>
                </a>
            </div>
    
            <div class="evisa-card">
                <div class="evisa-info">
                    <span class="fi fi-za evisa-flag"></span>
                    <span class="evisa-country">South Africa</span>
                </div>
                <a href="https://www.jsdbiz.com/south-africa-visa/apply" target="_blank" class="btn-apply">
                    Apply E-Visa <i class="ri-external-link-line"></i>
                </a>
            </div>
    
            <div class="evisa-card">
                <div class="evisa-info">
                    <span class="fi fi-kr evisa-flag"></span>
                    <span class="evisa-country">South Korea</span>
                </div>
                <a href="https://www.k-eta.go.kr/portal/newapply/index.do" target="_blank" class="btn-apply">
                    Apply K-ETA <i class="ri-external-link-line"></i>
                </a>
            </div>
    
            <div class="evisa-card">
                <div class="evisa-info">
                    <span class="fi fi-tw evisa-flag"></span>
                    <span class="evisa-country">Taiwan</span>
                </div>
                <a href="https://visawebapp.boca.gov.tw/BOCA_EVISA/MRV04FORM.do" target="_blank" class="btn-apply">
                    Apply E-Visa <i class="ri-external-link-line"></i>
                </a>
            </div>
    
            <div class="evisa-card">
                <div class="evisa-info">
                    <span class="fi fi-uz evisa-flag"></span>
                    <span class="evisa-country">Uzbekistan</span>
                </div>
                <a href="https://e-visa.gov.uz/main" target="_blank" class="btn-apply">
                    Apply E-Visa <i class="ri-external-link-line"></i>
                </a>
            </div>
    
            <div class="evisa-card">
                <div class="evisa-info">
                    <span class="fi fi-vn evisa-flag"></span>
                    <span class="evisa-country">Vietnam</span>
                </div>
                <a href="https://vietnamvisaonline.org/emergency-services/" target="_blank" class="btn-apply">
                    Apply E-Visa <i class="ri-external-link-line"></i>
                </a>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="js/darkmode.js"></script>
    <script src="js/main.js"></script>
</body>

</html>