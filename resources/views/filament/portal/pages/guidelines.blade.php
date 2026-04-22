<x-filament-panels::page>
    <style>
        .guidelines-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            color: #1f2937;
        }

        .guidelines-card h2 {
            font-size: 12pt;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-transform: uppercase;
            color: #333 !important; /* Default Black */
        }

        /* Dark Mode: Strict Black & White */
        .dark .guidelines-card {
            background: #121212;
            border-color: #374151;
            color: #ffffff;
        }

        .dark .guidelines-card h2 {
            color: #ffffff !important; /* Force White */
            border-bottom-color: #374151;
        }
        
        .dark .guidelines-card border-top {
            border-top-color: #374151;
        }
    </style>

    <div class="guidelines-card">
        @include('pdf-template.pdf-template-guidelines')
    </div>
</x-filament-panels::page>