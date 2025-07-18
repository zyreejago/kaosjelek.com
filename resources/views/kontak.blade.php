<x-layouts.app>
    <style>
        .contact-container {
            padding: 2rem 1.5rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 80vh !important;
        }
        .contact-box {
            background-color: #fce7f3 !important;
            padding: 2rem !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
        }
        .test{
            padding-top: 20px !important;
        }
        .content-wrapper {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            align-items: center !important;
            justify-content: center !important;
        }
        @media (min-width: 768px) {
            .content-wrapper {
                flex-direction: row !important;
                align-items: center !important;
                justify-content: center !important;
            }
        }
        .corner-dot {
            background-color: #fce7f3 !important;
            width: 1rem !important;
            height: 1rem !important;
            border-radius: 9999px !important;
            position: absolute !important;
            z-index: 10 !important;
        }
        .phone-icon {
            background-color: #22c55e !important;
            border-radius: 9999px !important;
            padding: 0.5rem !important;
            margin-right: 1rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .phone-icon svg {
            height: 2rem !important;
            width: 2rem !important;
            color: white !important;
        }
        .papyrus-font {
            font-family: 'Papyrus', cursive !important;
        }
        .contact-image-container {
            border: 4px solid #1f2937 !important;
            border-radius: 0.5rem !important;
            padding: 0.25rem !important;
            position: relative !important;
        }
        .location-text {
            margin-top: 2rem !important;
            padding-top: 1.5rem !important;
            text-align: right !important;
            font-size: 1.5rem !important;
        }
        .phone-container {
            padding-top: 40px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
        }
        /* Menghapus style tagline-text yang tidak diperlukan */
        /* Menghapus selector #test yang tidak berfungsi di cPanel */
        @media (min-width: 768px) {
            .contact-container {
                padding: 3rem 2rem !important;
            }
            .contact-box {
                padding: 2.5rem !important;
            }
            .location-text {
                font-size: 2rem !important;
            }
        }
        @media (min-width: 1024px) {
            .contact-container {
                padding: 4rem 3rem !important;
            }
            .contact-box {
                padding: 3rem !important;
            }
        
        }
    </style>
    <div class="container mx-auto contact-container">
        <div class="max-w-5xl mx-auto rounded-lg contact-box" id="test" style="justify-content: center !important; align-items: center !important;">
            @if($contact)
                <div class="content-wrapper gap-8" style="align-items: center !important; justify-content: center !important;">
                    <!-- Bagian Teks (Kiri) -->
                    <div class="w-full md:w-1/2" style="display: flex !important; flex-direction: column !important; align-items: flex-start !important; justify-content: flex-start !important;">
                        <p class="text-5xl mb-6 papyrus-font">{{ $contact->tagline ?? 'hit me up baby!!' }}</p>
                        
                        <div class="flex items-center mb-4 phone-container" style="padding-top: 40px !important; display: flex !important; align-items: center !important; justify-content: center !important; width: 100% !important;">
                            <div class="phone-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-3xl font-bold">{{ $contact->phone ?? '08123111111' }}</p>
                                <a href="{{ $contact->whatsapp_link ?? 'https://wa.me/628123111111' }}" class="text-gray-700 hover:text-green-600">(link whatsapp contact)</a>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Bagian Gambar (Kanan) -->
                    <div class="w-full md:w-1/2">
                        <div class="contact-image-container">
                            @php
                                $media = $contact ? $contact->getFirstMedia('contact_images') : null;
                                $imagePath = $media ? $media->id . '/' . $media->file_name : null;
                            @endphp
                            
                            @if($imagePath)
                                <img src="/storage/{{ $imagePath }}" alt="Contact Image" class="w-full h-64 object-cover">
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-center p-4">
                                    <p>ka tolong dikasi gambar yg bisa aku input sendiri kak (gambar diem juga kak)</p>
                                </div>
                            @endif
                            
                            <!-- Corner dots -->
                            <div class="corner-dot" style="top: -0.5rem !important; left: -0.5rem !important;"></div>
                            <div class="corner-dot" style="top: -0.5rem !important; right: -0.5rem !important;"></div>
                            <div class="corner-dot" style="bottom: -0.5rem !important; left: -0.5rem !important;"></div>
                            <div class="corner-dot" style="bottom: -0.5rem !important; right: -0.5rem !important;"></div>
                        </div>
                        <p class="location-text papyrus-font">{{ $contact->address ?? 'asli buatan pademangan' }}</p>
                    </div>
                </div>
            @else
                <!-- Tampilan fallback jika tidak ada data kontak -->
                <div class="content-wrapper gap-8" style="align-items: center !important; justify-content: center !important;">
                    <!-- Bagian Teks (Kiri) -->
                    <div class="w-full md:w-1/2" style="display: flex !important; flex-direction: column !important; align-items: flex-start !important; justify-content: flex-start !important;">
                        <p class="text-5xl mb-6 papyrus-font">hit me up baby!!</p>
                        
                        <div class="flex items-center mb-4 phone-container" style="padding-top: 40px !important; display: flex !important; align-items: center !important; justify-content: center !important; width: 100% !important;">
                            <div class="phone-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-3xl font-bold">08123111111</p>
                                <a href="https://wa.me/628123111111" class="text-gray-700 hover:text-green-600">(link whatsapp contact)</a>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Bagian Gambar (Kanan) -->
                    <div class="w-full md:w-1/2">
                        <div class="contact-image-container">
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-center p-4">
                                <p>ka tolong dikasi gambar yg bisa aku input sendiri kak (gambar diem juga kak)</p>
                            </div>
                            
                            <!-- Corner dots -->
                            <div class="corner-dot" style="top: -0.5rem !important; left: -0.5rem !important;"></div>
                            <div class="corner-dot" style="top: -0.5rem !important; right: -0.5rem !important;"></div>
                            <div class="corner-dot" style="bottom: -0.5rem !important; left: -0.5rem !important;"></div>
                            <div class="corner-dot" style="bottom: -0.5rem !important; right: -0.5rem !important;"></div>
                        </div>
                        <p class="location-text papyrus-font">asli buatan pademangan</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>