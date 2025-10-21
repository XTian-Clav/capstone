<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PITBI Portal</title>

    <!-- Load Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnDEw/dK1U/VzUnD/M/Vz5U/q5tT5M5GZJ/s5M5A5A=" crossorigin="anonymous">
    
    <!-- Load Bootstrap 5 JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lAxkQ3o7Sg0L8FhY6G7xG7M5ZJ/s5M5A5A=" crossorigin="anonymous"></script>
    
    <!-- Load Lucide Icons for aesthetic and functionality -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <!-- Poppin Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <style>
        /* Set Inter as the primary font and ensure smooth scrolling */
        body {
            font-family: 'Poppins';
            scroll-behavior: smooth;
            transition: background-color 0.3s, color 0.3s; /* Smooth transition for theme change */
        }
        
        /* Custom styling to ensure the footer has good contrast in both modes */
        [data-bs-theme="light"] .footer-bg {
            background-color: #343a40; /* Darker gray for light mode */
            color: #f8f9fa;
        }
        [data-bs-theme="dark"] .footer-bg {
            background-color: #212529; /* Even darker gray for dark mode */
            color: #dee2e6;
        }

        /* Adjust modal backdrop visibility using custom classes, since we're using custom JS for open/close */
        .modal-custom-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent backdrop */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Custom style for the FAQ icon rotation */
        .faq-icon {
            transition: transform 0.3s;
        }
        .faq-icon.rotate-180 {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-body">

    <!-- Header & Navigation (Bootstrap Navbar) -->
    <header>
        <nav class="navbar navbar-expand-md sticky-top shadow-sm" style="background-color: var(--bs-body-bg);">
            <div class="container-fluid px-3 px-md-5">
                <!-- Logo/Title -->
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <span class="fs-4 fw-bolder text-primary">PITBI Portal</span>
                    <span class="d-none d-sm-inline text-muted ms-2 fs-6">| Tech Incubator</span>
                </a>

                <!-- Mobile Toggler -->
                <button class="navbar-toggler p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Links & CTAs -->
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav me-md-4 mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="#about">Our Mission</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="#incubees">Startups</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="#faqs">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="#contact">Contact</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center">
                        <!-- Theme Toggle Button -->
                        <button id="theme-toggle" class="btn btn-sm me-3 border-0 rounded-circle p-2" title="Toggle Theme">
                            <!-- Icons switch via JS based on the 'data-bs-theme' attribute -->
                            <span data-lucide="sun" class="theme-icon light-icon" style="width: 20px; height: 20px;"></span>
                            <span data-lucide="moon" class="theme-icon dark-icon d-none" style="width: 20px; height: 20px;"></span>
                        </button>

                        <!-- Portal Login Button -->
                        <button onclick="window.location.href='{{ url('/portal/login') }}'" 
                            class="btn btn-primary fw-semibold rounded-3 shadow-sm d-none d-md-block">
                            Login
                        </button>

                    </div>

                    <!-- Mobile Login Button -->
                    <button onclick="window.location.href='{{ url('/portal/login') }}'" 
                        class="btn btn-primary fw-semibold rounded-3 shadow-sm w-100 mt-3 d-md-none">
                        Login
                    </button>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="bg-body-tertiary pt-5 pb-5 pt-md-5 pb-md-5 border-bottom">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8 text-center">
                    <span class="badge rounded-pill bg-primary text-white fs-6 fw-normal py-2 px-3 mb-3">
                        Palawan International Technology Business Incubator
                    </span>
                    <h1 class="display-3 fw-bold text-body-emphasis mb-4">
                        Nurturing the Future of Tech in MIMAROPA
                    </h1>
                    <p class="fs-5 text-muted mx-auto" style="max-width: 600px;">
                        PITBI provides the critical resources, mentorship, and ecosystem needed to transform research and innovative startup ideas into sustainable, commercially viable businesses.
                    </p>

                    <!-- Main CTA Buttons -->
                    <div class="mt-5 d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <button onclick="window.location.href='{{ url('/portal/login') }}'" class="btn btn-primary btn-lg fw-medium rounded-4 shadow-lg flex-fill" style="max-width: 250px;">
                            Join the Program
                        </button>
                        <a href="#about" class="btn btn-outline-secondary btn-lg fw-medium rounded-4 shadow-sm flex-fill" style="max-width: 250px;">
                            Discover Our Mission
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 py-md-5">
        <div class="container py-5">
            <div class="text-center">
                <h2 class="text-primary fs-6 fw-semibold text-uppercase mb-1">Our Core Mission</h2>
                <p class="display-5 fw-bold text-body-emphasis mb-5">
                    Driving Innovation for Inclusive Growth
                </p>
            </div>

            <div class="row g-4 mt-4">
                <!-- Value Proposition 1 -->
                <div class="col-md-4">
                    <div class="card h-100 rounded-4 shadow border-top border-5 border-primary p-3 bg-body-tertiary">
                        <div class="card-body">
                            <span data-lucide="rocket" class="text-primary mb-3 d-block" style="width: 3rem; height: 3rem;"></span>
                            <h3 class="card-title fs-5 fw-bold text-body-emphasis">Technology Transfer</h3>
                            <p class="card-text text-muted mt-2">Efficiently transfer university-produced technology and research into scalable, real-world applications for the public and private sectors.</p>
                        </div>
                    </div>
                </div>

                <!-- Value Proposition 2 -->
                <div class="col-md-4">
                    <div class="card h-100 rounded-4 shadow border-top border-5 border-success p-3 bg-body-tertiary">
                        <div class="card-body">
                            <span data-lucide="users" class="text-success mb-3 d-block" style="width: 3rem; height: 3rem;"></span>
                            <h3 class="card-title fs-5 fw-bold text-body-emphasis">Job Creation & Growth</h3>
                            <p class="card-text text-muted mt-2">Empower local entrepreneurs, fostering job creation and stimulating economic expansion within the MIMAROPA region and beyond.</p>
                        </div>
                    </div>
                </div>

                <!-- Value Proposition 3 -->
                <div class="col-md-4">
                    <div class="card h-100 rounded-4 shadow border-top border-5 border-purple p-3 bg-body-tertiary">
                        <div class="card-body">
                            <span data-lucide="handshake" class="text-purple mb-3 d-block" style="width: 3rem; height: 3rem;"></span>
                            <h3 class="card-title fs-5 fw-bold text-body-emphasis">Collaborative Ecosystem</h3>
                            <p class="card-text text-muted mt-2">Build a strong network of partners, mentors, and investors to support start-ups from ideation to commercialization.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Incubees Section -->
    <section id="incubees" class="py-5 py-md-5 bg-body-tertiary border-top border-bottom">
        <div class="container py-5">
            <div class="text-center">
                <h2 class="text-primary fs-6 fw-semibold text-uppercase mb-1">Success Stories</h2>
                <p class="display-5 fw-bold text-body-emphasis mb-3">
                    Meet Our Latest Cohort
                </p>
                <p class="fs-5 text-muted mx-auto mb-5" style="max-width: 600px;">
                    Showcasing innovative businesses nurtured by PITBI, tackling local challenges with global solutions.
                </p>
            </div>

            <!-- Incubees Grid -->
            <div class="row g-4">

                <!-- Incubee Card 1: Docufy -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 rounded-4 shadow border border-1 p-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary-subtle p-3 rounded-circle me-3">
                                    <span data-lucide="printer" class="text-primary" style="width: 1.5rem; height: 1.5rem;"></span>
                                </div>
                                <h3 class="card-title fs-5 fw-bold text-body-emphasis mb-0">Docufy</h3>
                            </div>
                            <p class="card-text text-muted">Mobile and web application providing 24/7 on-demand printing services with door-to-door delivery. Transforming paper trails into digital ease.</p>
                            <div class="mt-3 text-sm fw-semibold text-primary">Founder: Hyacinth Mae Tabo Tabo</div>
                        </div>
                    </div>
                </div>

                <!-- Incubee Card 2: Triosiklo -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 rounded-4 shadow border border-1 p-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success-subtle p-3 rounded-circle me-3">
                                    <span data-lucide="leaf" class="text-success" style="width: 1.5rem; height: 1.5rem;"></span>
                                </div>
                                <h3 class="card-title fs-5 fw-bold text-body-emphasis mb-0">Triosiklo</h3>
                            </div>
                            <p class="card-text text-muted">Sustainable charcoal briquettes made from wood waste, sawdust, and coconut shell, providing eco-friendly fuel solutions.</p>
                            <div class="mt-3 text-sm fw-semibold text-success">Founder: Dredd Alejo</div>
                        </div>
                    </div>
                </div>

                <!-- Incubee Card 3: Fundr -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 rounded-4 shadow border border-1 p-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-purple-subtle p-3 rounded-circle me-3">
                                    <span data-lucide="wallet" class="text-purple" style="width: 1.5rem; height: 1.5rem;"></span>
                                </div>
                                <h3 class="card-title fs-5 fw-bold text-body-emphasis mb-0">Fundr</h3>
                            </div>
                            <p class="card-text text-muted">An innovative financial technology platform that guides aspiring and small businesses in seeking and securing capitalization.</p>
                            <div class="mt-3 text-sm fw-semibold text-purple">Founder: Sandra M. Zulueta</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Frequently Asked Questions Section (Bootstrap Accordion Styling) -->
    <section id="faqs" class="py-5 py-md-5">
        <div class="container py-5" style="max-width: 800px;">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-primary fs-6 fw-semibold text-uppercase mb-1">Support</h2>
                <p class="display-5 fw-bold text-body-emphasis mb-5">
                    Frequently Asked Questions
                </p>
            </div>

            <!-- Accordion Container -->
            <div class="accordion" id="faqAccordion">

                <!-- FAQ Item 1: Login -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            How do I log in to the PITBI Portal?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Users log in using their assigned credentials, typically an email and password through the secure login page of the PITBI Portal.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2: Forgot Password -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            What should I do if I forget my password?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">You can use the Forgot Password link on the login page to initiate a password reset process, which will send instructions to your registered email.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3: Change Password -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            How do I change my password?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">After logging in, navigate to your account settings or profile page, select the option to change your password, then enter your current password followed by the new password twice to confirm.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4: Register -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            How do I register for an account on the PITBI Portal?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">User registration is typically managed by PITBI administration for startups (incubatees), investors, and staff.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 5: Registration Info -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            What information is required during registration?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Users usually need to provide their full name, email address, contact number, address, organization/company name, account type (incubitees and investors), valid identification document (ID) and create a password.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 6: Approval Time -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            How long does it take to get approved after registration?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Approval times vary but usually take between 1-3 business days, depending on verification processes.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 7: Registration Rejection -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            What if my registration request is not approved?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">The user will be notified with reasons for rejection and any further required actions or clarifications.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 8: Portal Purpose -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            What is the purpose of the PITBI Portal?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">The PITBI Portal is designed to streamline PITBI’s core operations by digitizing incubation management, resource utilization, event planning, and knowledge sharing to foster innovation and entrepreneurship in the region.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 9: Access -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            Who can access the PITBI Portal?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Access is provided to startups (incubatees), Portfolio Managers, investors, and PITBI staff, each with role-specific permissions and content.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 10: Startup Progress -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            How can startups track their progress in the incubation process?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Startups can monitor progress through milestone-based checklists validated by Portfolio Managers, allowing them to see completed and upcoming tasks clearly.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 11: Booking Facilities -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            How do I book PITBI’s facilities or equipment through the Portal?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Users can reserve available facilities or equipment by selecting time slots in the centralized reservation system, which shows current availability and booking options.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 12: Check Availability -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            Is there a way to check the availability of resources before reserving?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Yes, the system displays real-time availability to avoid booking conflicts.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 13: Register for Event -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            How can I register for a PITBI event using the Portal?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Users can view upcoming events and register directly through the portal’s event module.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 14: Tracking Attendance -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            Does the system support tracking real-time participant attendance during events?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Yes, real-time participant tracking helps organizers monitor engagement and attendance.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 15: Learning Materials -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            What type of learning materials and toolkits are accessible to startups?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Startups can access curated learning resources, business toolkits, internal announcements, forms, and operational guides tailored to their incubation phase.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 16: Content Access -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            How is content access tailored based on the startup’s incubation phase?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">The system grants phase-based access, ensuring startups receive only relevant materials that align with their current stage.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 17: Security -->
                <div class="accordion-item rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-body-tertiary" type="button" onclick="toggleFaq(this)" aria-expanded="false">
                            Is the PITBI Portal secure for handling sensitive data?
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="mb-0">Yes, PITBI Portal incorporates security measures including encrypted connections, secure authentication, and role-based access controls to protect user information.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer & Contact (Using custom background class for theme consistency) -->
    <footer id="contact" class="footer-bg py-5">
        <div class="container py-3">
            <div class="row g-4">
                <!-- Column 1: PITBI Identity -->
                <div class="col-md-3">
                    <h3 class="fs-4 fw-bolder text-info">PITBI</h3>
                    <p class="mt-3 text-secondary-subtle small">Palawan International Technology Business Incubator, proudly hosted by Palawan State University.</p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-md-3">
                    <h4 class="fs-6 fw-semibold mb-3 border-bottom border-info pb-2">Quick Links</h4>
                    <ul class="list-unstyled small text-secondary-subtle">
                        <li><a href="#about" class="text-decoration-none text-secondary-subtle d-block mb-1">About PITBI</a></li>
                        <li><a href="#incubees" class="text-decoration-none text-secondary-subtle d-block mb-1">Our Startups</a></li>
                        <li><a href="#faqs" class="text-decoration-none text-secondary-subtle d-block mb-1">FAQ</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary-subtle d-block mb-1">Partnerships</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact (Updated Location) -->
                <div class="col-md-3">
                    <h4 class="fs-6 fw-semibold mb-3 border-bottom border-info pb-2">Contact Us</h4>
                    <ul class="list-unstyled small text-secondary-subtle">
                        <li class="d-flex align-items-start mb-2">
                            <span data-lucide="mail" class="me-2" style="width: 1rem; height: 1rem; margin-top: 3px;"></span>
                            <!-- UPDATED EMAIL ADDRESS -->
                            <span style="word-break: break-all;">palawanitbi@psu.palawan.edu.ph</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <span data-lucide="map-pin" class="me-2 flex-shrink-0" style="width: 1rem; height: 1rem; margin-top: 3px;"></span>
                            <!-- UPDATED LOCATION ADDRESS -->
                            <span>2nd Floor, Student's Innovation Park Building, Palawan State University-Main Campus, Tiniguiban Heights, 5300 Puerto Princesa, Philippines</span>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Social Media -->
                <div class="col-md-3">
                    <h4 class="fs-6 fw-semibold mb-3 border-bottom border-info pb-2">Follow Us</h4>
                    <div class="d-flex gap-3">
                        <!-- Facebook Icon (UPDATED LINK) -->
                        <a href="https://www.facebook.com/share/1CrfC9VCRj/" target="_blank" class="text-secondary-subtle">
                            <svg data-lucide="facebook" class="opacity-75" style="width: 1.5rem; height: 1.5rem;"></svg>
                        </a>
                        <a href="#" class="text-secondary-subtle">
                            <svg data-lucide="twitter" class="opacity-75" style="width: 1.5rem; height: 1.5rem;"></svg>
                        </a>
                        <a href="#" class="text-secondary-subtle">
                            <svg data-lucide="linkedin" class="opacity-75" style="width: 1.5rem; height: 1.5rem;"></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-5 pt-3 border-top border-secondary-subtle text-center small text-secondary-subtle opacity-75">
                &copy; 2025 Palawan International Technology Business Incubator. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- ========================================================== -->
    <!-- MODAL DIALOGS FOR AUTHENTICATION -->
    <!-- We use Bootstrap classes but custom JS to manage 'hidden' state -->
    <!-- ========================================================== -->

    <!-- 1. LOGIN MODAL -->
    <div id="login-modal" class="modal-custom-backdrop d-none" onclick="closeModal('login-modal')">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content rounded-4 shadow-lg p-3 p-sm-4" onclick="event.stopPropagation()">
                <!-- Close Button -->
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Close" onclick="closeModal('login-modal')"></button>

                <div class="modal-body">
                    <h2 class="fs-3 fw-bold text-body-emphasis text-center mb-4 d-flex align-items-center justify-content-center">
                        <span data-lucide="log-in" class="me-2 text-primary" style="width: 1.75rem; height: 1.75rem;"></span>
                        <span>Portal Login</span>
                    </h2>
                    <form onsubmit="event.preventDefault(); showMessage('login-modal', 'login-message', 'Login successful! Redirecting...', 'bg-primary');">
                        <div class="mb-3">
                            <label for="login-email" class="form-label small fw-medium text-muted">Email Address</label>
                            <input type="email" id="login-email" required placeholder="name@startup.ph"
                                   class="form-control rounded-3" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label small fw-medium text-muted">Password</label>
                            <input type="password" id="login-password" required placeholder="••••••••"
                                   class="form-control rounded-3">
                        </div>
                        <p class="text-end small">
                            <a href="#" onclick="switchModal('login-modal', 'forgot-password-modal')" class="text-primary text-decoration-none fw-medium">
                                Forgot Password?
                            </a>
                        </p>
                        <div id="login-message" class="text-center small fw-semibold text-white d-none py-2 rounded-3 mb-3"></div>
                        <button type="submit"
                                class="btn btn-primary w-100 fw-semibold rounded-3 shadow-sm">
                            Sign In
                        </button>
                    </form>
                    <p class="mt-4 text-center small text-muted">
                        Need an account?
                        <a href="#" onclick="switchModal('login-modal', 'register-modal')" class="text-primary text-decoration-none fw-medium">
                            Register here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. REGISTER MODAL -->
    <div id="register-modal" class="modal-custom-backdrop d-none" onclick="closeModal('register-modal')">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content rounded-4 shadow-lg p-3 p-sm-4" onclick="event.stopPropagation()">
                <!-- Close Button -->
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Close" onclick="closeModal('register-modal')"></button>

                <div class="modal-body">
                    <h2 class="fs-3 fw-bold text-body-emphasis text-center mb-4 d-flex align-items-center justify-content-center">
                        <span data-lucide="user-plus" class="me-2 text-success" style="width: 1.75rem; height: 1.75rem;"></span>
                        <span>Register Account</span>
                    </h2>
                    <!-- MODIFIED FORM: Submission now indicates pending approval -->
                    <form onsubmit="event.preventDefault(); showMessage('register-modal', 'register-message', 'Registration submitted! Awaiting administrator approval.', 'bg-warning');">
                        <div class="mb-3">
                            <label for="register-name" class="form-label small fw-medium text-muted">Full Name</label>
                            <input type="text" id="register-name" required placeholder="Juan Dela Cruz"
                                   class="form-control rounded-3">
                        </div>
                        <div class="mb-3">
                            <label for="register-email" class="form-label small fw-medium text-muted">Email Address</label>
                            <input type="email" id="register-email" required placeholder="name@startup.ph"
                                   class="form-control rounded-3">
                        </div>
                        <!-- NEW FIELD: Account Type for realistic registration -->
                        <div class="mb-3">
                            <label for="register-account-type" class="form-label small fw-medium text-muted">Account Type</label>
                            <select id="register-account-type" required class="form-select rounded-3">
                                <option value="" disabled selected>Select Account Type</option>
                                <option value="incubatee">Incubatee / Startup</option>
                                <option value="investor">Investor / Partner</option>
                                <option value="staff">PITBI Staff</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="register-password" class="form-label small fw-medium text-muted">Password</label>
                            <input type="password" id="register-password" required placeholder="Min 8 characters"
                                   class="form-control rounded-3">
                        </div>
                        <div id="register-message" class="text-center small fw-semibold text-white d-none py-2 rounded-3 mb-3"></div>
                        <button type="submit"
                                class="btn btn-success w-100 fw-semibold rounded-3 shadow-sm">
                            Submit for Approval
                        </button>
                    </form>
                    <p class="mt-4 text-center small text-muted">
                        Already have an account?
                        <a href="#" onclick="switchModal('register-modal', 'login-modal')" class="text-primary text-decoration-none fw-medium">
                            Sign In
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. FORGOT PASSWORD MODAL -->
    <div id="forgot-password-modal" class="modal-custom-backdrop d-none" onclick="closeModal('forgot-password-modal')">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content rounded-4 shadow-lg p-3 p-sm-4" onclick="event.stopPropagation()">
                <!-- Close Button -->
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Close" onclick="closeModal('forgot-password-modal')"></button>

                <div class="modal-body">
                    <h2 class="fs-3 fw-bold text-body-emphasis text-center mb-4 d-flex align-items-center justify-content-center">
                        <span data-lucide="key-round" class="me-2 text-warning" style="width: 1.75rem; height: 1.75rem;"></span>
                        <span>Reset Password</span>
                    </h2>
                    <p class="mb-4 text-center text-muted small">
                        Enter the email address associated with your account to receive a reset link.
                    </p>
                    <form onsubmit="event.preventDefault(); showMessage('forgot-password-modal', 'forgot-message', 'Password reset link sent!', 'bg-warning');">
                        <div class="mb-4">
                            <label for="forgot-email" class="form-label small fw-medium text-muted">Email Address</label>
                            <input type="email" id="forgot-email" required placeholder="name@startup.ph"
                                   class="form-control rounded-3">
                        </div>
                        <div id="forgot-message" class="text-center small fw-semibold text-white d-none py-2 rounded-3 mb-3"></div>
                        <button type="submit"
                                class="btn btn-warning w-100 fw-semibold rounded-3 shadow-sm text-white">
                            Send Reset Link
                        </button>
                    </form>
                    <p class="mt-4 text-center small text-muted">
                        Remembered your password?
                        <a href="#" onclick="switchModal('forgot-password-modal', 'login-modal')" class="text-primary text-decoration-none fw-medium">
                            Sign In
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // --- THEME CONTROL FUNCTIONS ---

        const htmlEl = document.documentElement;
        const themeToggle = document.getElementById('theme-toggle');
        const lightIcon = document.querySelector('.theme-icon.light-icon');
        const darkIcon = document.querySelector('.theme-icon.dark-icon');

        /**
         * Initializes the theme based on localStorage or system preference.
         */
        function initializeTheme() {
            const storedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            let theme = 'light';
            if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                theme = 'dark';
            }
            
            htmlEl.setAttribute('data-bs-theme', theme);
            updateThemeIcons(theme);
        }

        /**
         * Updates the visibility of the sun/moon icons.
         * @param {string} theme - 'light' or 'dark'.
         */
        function updateThemeIcons(theme) {
            if (theme === 'dark') {
                lightIcon.classList.add('d-none');
                darkIcon.classList.remove('d-none');
            } else {
                lightIcon.classList.remove('d-none');
                darkIcon.classList.add('d-none');
            }
            lucide.createIcons(); // Re-render icons if needed
        }

        /**
         * Toggles the theme between light and dark mode.
         */
        function toggleTheme() {
            const currentTheme = htmlEl.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            htmlEl.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcons(newTheme);
        }

        // Attach event listener for the toggle button
        if (themeToggle) {
            themeToggle.addEventListener('click', toggleTheme);
        }

        // Initialize theme when the script runs
        initializeTheme();

        // --- MODAL CONTROL FUNCTIONS (Custom JS for simplicity in single file) ---

        /**
         * Opens a specific modal and hides the scrollbar on the body.
         * @param {string} modalId - The ID of the modal to open.
         */
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('d-none');
            document.body.style.overflow = 'hidden'; // Disable scrolling on the body
        }

        /**
         * Closes a specific modal and restores the body scrollbar.
         * @param {string} modalId - The ID of the modal to close.
         */
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('d-none');
            document.body.style.overflow = ''; // Restore scrolling
            // Clear any previous messages
            const messageBox = document.getElementById(modalId.replace('-modal', '-message'));
            if (messageBox) {
                messageBox.classList.add('d-none');
                messageBox.textContent = '';
                messageBox.className = messageBox.className.split(' ').filter(c => !c.startsWith('bg-')).join(' '); // Remove background color class
            }
        }

        /**
         * Closes the current modal and opens a target modal.
         * @param {string} currentModalId - The ID of the modal to close.
         * @param {string} targetModalId - The ID of the modal to open.
         */
        function switchModal(currentModalId, targetModalId) {
            closeModal(currentModalId);
            openModal(targetModalId);
        }

        /**
         * Displays a temporary confirmation message within the form and optionally closes the modal.
         * @param {string} modalId - The ID of the parent modal (e.g., 'login-modal').
         * @param {string} messageId - The ID of the message container element (e.g., 'login-message').
         * @param {string} messageText - The message to display.
         * @param {string} bgClass - The Bootstrap background class for the message (e.g., 'bg-primary').
         */
        function showMessage(modalId, messageId, messageText, bgClass) {
            const messageBox = document.getElementById(messageId);
            
            // Set background color and message
            messageBox.className = `text-center small fw-semibold text-white d-none py-2 rounded-3 mb-3 ${bgClass}`;
            messageBox.textContent = messageText + ' (Demo action)';
            messageBox.classList.remove('d-none');

            const modalElement = document.getElementById(modalId);
            
            // Clear inputs in the modal upon successful "confirmation"
            const form = modalElement.querySelector('form');
            if (form) {
                form.reset();
            }
            
            // Hide after 3 seconds
            setTimeout(() => {
                messageBox.classList.add('d-none');
                closeModal(modalId);
            }, 3000); 
        }

        // --- FAQ ACCORDION CONTROL (Custom JS adapted for Bootstrap classes) ---
        
        /**
         * Toggles the content visibility and icon rotation for an FAQ item.
         * This uses the Bootstrap Accordion structure but custom JS for simple toggling.
         * @param {HTMLElement} button - The button element that was clicked.
         */
        function toggleFaq(button) {
            const item = button.closest('.accordion-item');
            const content = item.querySelector('.accordion-collapse');

            const isCollapsed = content.classList.contains('collapse');

            // Close all other open items
            document.querySelectorAll('.accordion-collapse.show').forEach(c => {
                c.classList.remove('show');
                c.closest('.accordion-item').querySelector('.accordion-button').classList.add('collapsed');
            });

            // Toggle the current item
            if (isCollapsed) {
                // If it was collapsed, open it
                content.classList.add('show');
                button.classList.remove('collapsed');
            } else {
                // If it was open, close it
                content.classList.remove('show');
                button.classList.add('collapsed');
            }
        }

    </script>
</body>
</html>
