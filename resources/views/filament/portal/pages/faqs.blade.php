<x-filament-panels::page>
    <style>
        .faq-category {
            font-size: 1.2rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 2.5rem 0 1rem;
            color: #fe800d;
            padding-bottom: 0.5rem;
        }

        .faq-card {
            margin-bottom: 0.75rem;
            padding: 1.25rem 1.5rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #fe800d;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .dark .faq-card {
            background-color: #121212;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .faq-card:hover {
            transform: translateX(4px);
        }

        .faq-question {
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #1f2937;
        }

        .dark .faq-question {
            color: #f3f4f6;
        }

        .faq-answer {
            font-size: 0.9rem;
            font-weight: 400;
            margin-top: 1rem;
            line-height: 1.6;
            color: #4b5563;
            border-top: 1px solid #f3f4f6;
            padding-top: 1rem;
        }

        .dark .faq-answer {
            color: #d1d5db;
            border-top: 1px solid #374151;
        }

        .uniic-list {
            list-style-type: disc;
            margin-left: 1.5rem;
            margin-top: 0.75rem;
        }

        .uniic-list li {
            margin-bottom: 0.35rem;
        }
    </style>

    <div class="faq-container pb-12">
        
        {{-- Section: Account & Login --}}
        <h2 class="faq-category">Account & Login</h2>
        @php
            $accountFaqs = [
                ['q' => 'How do I log in to the PITBI Portal?', 'a' => 'Users log in using their assigned credentials, typically an email and password through the secure login page of the PITBI Portal.'],
                ['q' => 'What should I do if I forget my password?', 'a' => 'You can use the Forgot Password link on the login page to initiate a password reset process, which will send instructions to your registered email.'],
                ['q' => 'How do I change my password?', 'a' => 'After logging in, navigate to your account settings or profile page, select the option to change your password.'],
                ['q' => 'How do I register for an account on the PITBI Portal?', 'a' => 'User registration is typically managed by PITBI administration for startups (incubatees), investors, and staff.'],
                ['q' => 'What information is required during registration?', 'a' => 'Users usually need to provide their full name, email address, contact number, address, organization/company name, account type (incubatees and investors), and valid identification document (ID).'],
                ['q' => 'How long does it take to get approved after registration?', 'a' => 'Approval times vary but usually take between 1-3 business days, depending on verification processes.'],
                ['q' => 'What if my registration request is not approved?', 'a' => 'The user will be notified with reasons for rejection and any further required actions or clarifications.']
            ];
        @endphp
        @foreach($accountFaqs as $faq)
            <div x-data="{ open: false }" class="faq-card" @click="open = !open">
                <div class="faq-question">
                    <span>{{ $faq['q'] }}</span>
                    <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
                </div>
                <div x-show="open" x-collapse>
                    <div class="faq-answer">{{ $faq['a'] }}</div>
                </div>
            </div>
        @endforeach

        {{-- Section: Portal Purpose & Access --}}
        <h2 class="faq-category">Portal Purpose & Access</h2>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>What is the purpose of the PITBI Portal?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">The PITBI Portal is designed to streamline PITBI’s core operations by digitizing incubation management, resource utilization, event planning, and knowledge sharing to foster innovation and entrepreneurship in the region.</div>
            </div>
        </div>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>Who can access the PITBI Portal?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">Access is provided to startups (incubatees), Portfolio Managers, investors, and PITBI staff, each with role-specific permissions and content.</div>
            </div>
        </div>

        {{-- Section: Startup Progress & Resources --}}
        <h2 class="faq-category">Startup Progress & Resources</h2>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>How can startups track their progress in the incubation process?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">Startups can monitor progress through milestone-based checklists validated by Portfolio Managers, allowing them to see completed and upcoming tasks clearly.</div>
            </div>
        </div>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>How do I book PITBI’s facilities or equipment through the Portal?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">Users can reserve available facilities or equipment by selecting time slots in the centralized reservation system, which shows current availability and booking options.</div>
            </div>
        </div>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>Is there a way to check the availability of resources before reserving?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">Yes, the system displays real-time availability to avoid booking conflicts.</div>
            </div>
        </div>

        {{-- Section: Events & Participation --}}
        <h2 class="faq-category">Events & Participation</h2>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>How can I register for a PITBI event using the Portal?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">Users can view upcoming events and register directly through the portal’s event module.</div>
            </div>
        </div>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>Does the system support tracking real-time participant attendance during events?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">Yes, real-time participant tracking helps organizers monitor engagement and attendance.</div>
            </div>
        </div>

        {{-- Section: Learning Materials & Content --}}
        <h2 class="faq-category">Learning Materials & Content</h2>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>What type of learning materials and toolkits are accessible to startups?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">Startups can access curated learning resources, business toolkits, internal announcements, forms, and operational guides tailored to their incubation phase.</div>
            </div>
        </div>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>How is content access tailored based on the startup’s incubation phase?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">The system grants phase-based access, ensuring startups receive only relevant materials that align with their current stage.</div>
            </div>
        </div>

        {{-- Section: Security --}}
        <h2 class="faq-category">Security</h2>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>Is the PITBI Portal secure for handling sensitive data?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">Yes, PITBI Portal incorporates security measures including encrypted connections, secure authentication, and role-based access controls to protect user information.</div>
            </div>
        </div>

        {{-- Section: Visa & Travel --}}
        <h2 class="faq-category">Visa & Travel</h2>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>How do I process my visa for overseas travel?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">
                    Incubatees should contact the PITBI administrators for assistance with visa processing. The administrators will provide guidance and handle the necessary steps as required. E-visa links for each country are also available in the International Travel tab.
                </div>
            </div>
        </div>
        <div x-data="{ open: false }" class="faq-card" @click="open = !open">
            <div class="faq-question">
                <span>Which countries and institutions are included in PITBI's International Travel program?</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </div>
            <div x-show="open" x-collapse>
                <div class="faq-answer">
                    <p>The following countries, State Universities and Colleges (SUCs), and Higher Education Institutions (HEIs) have a Memorandum of Agreement (MOA) with Palawan State University. These are authorized for travel under PITBI and subject to <em>CHED CMO No. 3, Series of 2025</em>.</p>
                    
                    <p class="mt-4 font-bold">List of UNIIC members:</p>
                    <ul class="uniic-list">
                        <li>Tra Vinh University (Vietnam)</li>
                        <li>Can Tho University (Vietnam)</li>
                        <li>Hung Vuong University Ho Chi Minh City (Vietnam)</li>
                        <li>Universitas Trisakti (Indonesia)</li>
                        <li>Telkom University, Bandung (Indonesia)</li>
                        <li>Universitas Multimedia Nusantara (Indonesia)</li>
                        <li>Palawan State University (Philippines)</li>
                        <li>Western Philippines University (Philippines)</li>
                        <li>Mindoro State University (Philippines)</li>
                        <li>Adamson University (Philippines)</li>
                        <li>Occidental Mindoro State College (Philippines)</li>
                        <li>University of the Cordilleras (Philippines)</li>
                        <li>Department of Science & Technology (Philippines)</li>
                        <li>National University of Cheasim Kamchaymear (Cambodia)</li>
                        <li>Cambodia University of Technology & Science (Cambodia)</li>
                        <li>Coimbatore Institute of Technology (India)</li>
                        <li>Chitkara University (India)</li>
                        <li>Asia Pacific University of Technology & Innovation (Malaysia)</li>
                        <li>University of Mpumalanga (South Africa)</li>
                        <li>Sefako Makgatho Health Sciences University (South Africa)</li>
                        <li>Mangosuthu University of Technology (South Africa)</li>
                        <li>Tshwane University of Technology (South Africa)</li>
                        <li>National University of Kaohsiung (Taiwan)</li>
                        <li>Millat Umidi University (Uzbekistan)</li>
                        <li>AJOU University (Uzbekistan)</li>
                        <li>Asian Society of Innovation & Policy (South Korea)</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>