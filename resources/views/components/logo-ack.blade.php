@props(['class' => 'h-12 w-auto object-contain', 'fallbackClass' => 'w-12 h-12'])
@if(file_exists(public_path('images/logo-ack.png')))
    <img src="{{ asset('images/logo-ack.png') }}" alt="Academy Charles Kabore" class="{{ $class }}" width="48" height="48" />
@else
    <div class="{{ $fallbackClass }} bg-gradient-to-br from-red-600 to-orange-500 rounded-lg flex items-center justify-center shrink-0">
        <span class="text-2xl font-black text-white">ACK</span>
    </div>
@endif
