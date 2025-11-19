<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" href="{{ asset('assets/favicon/favicon.ico') }}" type="image/x-icon">
    <script type="text/javascript" src="darkmode.js" defer></script>
    <title>PITBI Portal | FAQs</title>
    <style>
        .faq-header {
            text-align: center;
            font-family: var(--header-font);
            font-size: 3rem;
            color: var(--text-color);
            padding-top: 3rem;
            margin-bottom: 2rem;
        }

        .faq-card {
            max-width: 1000px;
            margin: 1rem auto;
            padding: 1.5rem 2rem;
            background-color: var(--white-bg);
            border-radius: 4px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 5px solid var(--primary-color);
        }

        .faq-card:hover {
            transform: translateY(-5px);
            border: 1px solid;
            border-color: var(--primary-color);
            border-left: 5px solid var(--primary-color);
        }

        .faq-question {
            font-family: var(--header-font);
            font-size: 1.2rem;
            font-weight: 400;
            color: var(--text-light);
            letter-spacing: 0.8px;
            cursor: pointer;
        }

        .faq-answer {
            margin-top: 0.75rem;
            font-size: 1rem;
            line-height: 1.5rem;
            color: var(--text-dark);
            display: none;
        }

        .faq-card.active .faq-answer {
            display: block;
        }

        .faq-card + .faq-card {
            margin-top: 1rem;
        }

        .faq-category {
            font-family: var(--header-font);
            font-size: 1.4rem;
            font-weight: 400;
            margin: 2rem auto 1rem;
            color: var(--text-color);
            max-width: 1000px;
            padding-left: 2rem;
            letter-spacing: 0.8px;
        }

        @media (max-width: 768px) {
            .faq-header {
                padding-top: 6rem;
                font-size: 2rem;
            }

            .faq-card {
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
        <h2 class="faq-header">Frequently Asked Questions</h2>

        <h2 class="faq-category">Account & Login</h2>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">How do I log in to the PITBI Portal?</div>
            <div class="faq-answer">Users log in using their assigned credentials, typically an email and password through the secure login page of the PITBI Portal.</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">What should I do if I forget my password?</div>
            <div class="faq-answer">You can use the Forgot Password link on the login page to initiate a password reset process, which will send instructions to your registered email.</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">How do I change my password?</div>
            <div class="faq-answer">After logging in, navigate to your account settings or profile page, select the option to change your password.</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">How do I register for an account on the PITBI Portal?</div>
            <div class="faq-answer">User registration is typically managed by PITBI administration for startups (incubatees), investors, and staff.</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">What information is required during registration?</div>
            <div class="faq-answer">Users usually need to provide their full name, email address, contact number, address, organization/company name, account type (incubatees and investors), and valid identification document (ID).</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">How long does it take to get approved after registration?</div>
            <div class="faq-answer">Approval times vary but usually take between 1-3 business days, depending on verification processes.</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">What if my registration request is not approved?</div>
            <div class="faq-answer">The user will be notified with reasons for rejection and any further required actions or clarifications.</div>
        </div>

        <h2 class="faq-category">Portal Purpose & Access</h2>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">What is the purpose of the PITBI Portal?</div>
            <div class="faq-answer">The PITBI Portal is designed to streamline PITBI’s core operations by digitizing incubation management, resource utilization, event planning, and knowledge sharing to foster innovation and entrepreneurship in the region.</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">Who can access the PITBI Portal?</div>
            <div class="faq-answer">Access is provided to startups (incubatees), Portfolio Managers, investors, and PITBI staff, each with role-specific permissions and content.</div>
        </div>

        <h2 class="faq-category">Startup Progress & Resources</h2>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">How can startups track their progress in the incubation process?</div>
            <div class="faq-answer">Startups can monitor progress through milestone-based checklists validated by Portfolio Managers, allowing them to see completed and upcoming tasks clearly.</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">How do I book PITBI’s facilities or equipment through the Portal?</div>
            <div class="faq-answer">Users can reserve available facilities or equipment by selecting time slots in the centralized reservation system, which shows current availability and booking options.</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">Is there a way to check the availability of resources before reserving?</div>
            <div class="faq-answer">Yes, the system displays real-time availability to avoid booking conflicts.</div>
        </div>

        <h2 class="faq-category">Events & Participation</h2>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">How can I register for a PITBI event using the Portal?</div>
            <div class="faq-answer">Users can view upcoming events and register directly through the portal’s event module.</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">Does the system support tracking real-time participant attendance during events?</div>
            <div class="faq-answer">Yes, real-time participant tracking helps organizers monitor engagement and attendance.</div>
        </div>

        <h2 class="faq-category">Learning Materials & Content</h2>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">What type of learning materials and toolkits are accessible to startups?</div>
            <div class="faq-answer">Startups can access curated learning resources, business toolkits, internal announcements, forms, and operational guides tailored to their incubation phase.</div>
        </div>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">How is content access tailored based on the startup’s incubation phase?</div>
            <div class="faq-answer">The system grants phase-based access, ensuring startups receive only relevant materials that align with their current stage.</div>
        </div>

        <h2 class="faq-category">Security</h2>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">Is the PITBI Portal secure for handling sensitive data?</div>
            <div class="faq-answer">Yes, PITBI Portal incorporates security measures including encrypted connections, secure authentication, and role-based access controls to protect user information.</div>
        </div>
        
        <h2 class="faq-category">Visa & Travel</h2>
        <div class="faq-card" onclick="this.classList.toggle('active')">
            <div class="faq-question">How do we process the visa when going overseas?</div>
            <div class="faq-answer">Incubatees should contact the PITBI administrators to assist with visa processing. The administrators will provide guidance and handle the necessary steps as required.</div>
        </div><br>

        <script src="https://unpkg.com/scrollreveal"></script>
        <script src="js/darkmode.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>