@props(['icon'])

@php
    $svgs = [
        'diabetes' => '<svg class="size-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 10h2v2H7zM11 10h2v2h-2zM15 10h2v2h-2zM7 14h2v2H7zM11 14h2v2h-2zM15 14h2v2h-2z" fill="#F97316"/>
                <path d="M19 4H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V6h14v14z" fill="#4B5563"/>
                <path d="M11 8h2v2h-2z" fill="#F97316"/>
            </svg>',

        'cardiac' => '<svg class="size-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="#4B5563" fill-opacity="0.05" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M2 8.5c0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5z" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M7 9l2 2l4-4" stroke="#F97316" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>',

        'stomach' => '<svg class="size-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C7.03 2 3 6.03 3 11c0 4.42 3.58 8 8 8v3h2v-3c4.42 0 8-3.58 8-8 0-4.97-4.03-9-9-9zm0 15c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z" fill="#4B5563" fill-opacity="0.05" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M12 7v4l3 2" stroke="#F97316" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>',

        'pain' => '<svg class="size-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z" fill="#4B5563" stroke="#4B5563" stroke-width="0.5"/>
                <path d="M15 12l2 2M15 16l2-2" stroke="#F97316" stroke-width="1.5" stroke-linecap="round"/>
            </svg>',

        'liver' => '<svg class="size-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.2 13c0 4.4 3.6 8 8 8s8-3.6 8-8-3.6-8-8-8-8 3.6-8 8z" fill="#4B5563" fill-opacity="0.05" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M11.2 5c-4.4 0-8 3.6-8 8s3.6 8 8 8c4.4 0 8-3.6 8-8" stroke="#4B5563" stroke-width="1.5" stroke-linecap="round"/>
                <circle cx="15" cy="11" r="3" stroke="#F97316" stroke-width="1.5"/>
            </svg>',

        'oral' => '<svg class="size-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="#4B5563" fill-opacity="0.05" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M12 7v4" stroke="#F97316" stroke-width="1.5" stroke-linecap="round"/>
            </svg>',

        'respiratory' => '<svg class="size-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z" fill="#4B5563" fill-opacity="0.05" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M12 18l-8 3 8-19 8 19-8-3z" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M10 12h4" stroke="#F97316" stroke-width="1.5"/>
            </svg>',

        'sexual' => '<svg class="size-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="9" cy="15" r="5" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M13 11l4-4m0 0h-3m3 0v3" stroke="#4B5563" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="15" cy="9" r="5" stroke="#F97316" stroke-width="1.5"/>
            </svg>',

        'elderly' => '<svg class="size-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zM4 7v2c0 1.1.9 2 2 2h2v11h2v-6h2v6h2V11h2c1.1 0 2-.9 2-2V7H4z" fill="#4B5563" fill-opacity="0.05" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M16 12l2 2M16 16l2-2" stroke="#F97316" stroke-width="1.5" stroke-linecap="round"/>
            </svg>',

        'immunity' => '<svg class="size-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" fill="#4B5563" fill-opacity="0.05" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" stroke="#4B5563" stroke-width="1.5"/>
                <path d="M9 12l2 2 4-4" stroke="#F97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>',
    ];
@endphp

{!! $svgs[$icon] ?? '' !!}