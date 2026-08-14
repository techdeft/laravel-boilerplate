<?php

use Livewire\Volt\Component;

new class extends Component {
    public function brands()
    {
        return [
            ['name' => 'CeraVe', 'logo' => 'images/brands/ceravee.png'],
            ['name' => 'Dr Teal\'s', 'logo' => 'images/brands/dr-teals.png'],
            ['name' => 'revive active', 'logo' => 'images/brands/revive.png'],
            ['name' => 'NIVEA', 'logo' => 'images/brands/nivea.png'],
            ['name' => 'OMRON', 'logo' => 'images/brands/omron.png'],
            ['name' => 'NEOCELL', 'logo' => 'images/brands/neocel.png'],
            ['name' => 'PANINO', 'logo' => 'images/brands/panino.png'],
            ['name' => 'durex', 'logo' => 'images/brands/durex.png'],
        ];
    }
};
?>

<section class="w-full bg-[#2B1770] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl font-black text-white mb-12 tracking-tight">Top Brands</h2>

        <!-- Brand Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">


            @foreach($this->brands() as $brand)
                <div
                    class="bg-white rounded-xl h-32 md:h-40 flex items-center justify-center px-6 shadow-lg transition-transform hover:scale-105 duration-300 group">
                    @if($brand['logo'])
                        <img src="{{ asset($brand['logo']) }}" alt="{{ $brand['name'] }}"
                            class="max-h-20 w-auto object-contain">
                    @else
                            <!-- Stylized Text-based Logo for Demo -->
                            <span class="text-2xl md:text-3xl font-black {{ 
                                                                $brand['name'] === 'Dr Teal\'s' ? 'font-serif text-gray-800' :
                        ($brand['name'] === 'NIVEA' ? 'font-sans text-blue-900 tracking-tighter' :
                            ($brand['name'] === 'revive active' ? 'text-green-600 font-sans' :
                                ($brand['name'] === 'OMRON' ? 'text-blue-700 font-black' :
                                    ($brand['name'] === 'durex' ? 'text-blue-600 italic border-2 border-blue-600 px-3 py-1 rounded-full' : 'text-gray-900')))) 
                                                            }}">
                                {{ $brand['name'] }}
                            </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>