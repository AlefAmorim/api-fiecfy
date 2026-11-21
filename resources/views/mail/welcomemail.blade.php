<x-mail::layout>  
  <x-slot:header>  
    <x-mail::header :url="$url">
      <img  src="{{ $message->embed(public_path('images/Spotify_Primary_Logo_RGB_Green.png')) }}" class="logo" alt="Fiecfy Logo">
      {{-- <img style="width:50px;height:50px;" src="{{ asset('images/Spotify_Primary_Logo_RGB_Green.png') }}" alt="logo fiecfy"> --}}
      {{-- <h1 style="margin:auto 0;">Fiecfy</h1> --}}
    </x-mail::header>
  </x-slot:header>

  <x-slot>
  
  ## Welcome to fiecfy, {{ $name }}!
  
  Now you have access to the most popular musics, podcasts  and more.
  We hope you enjoy this new journey with us.
  
  <x-mail::button color="green" :url="$url">
    Explore
  </x-mail::button>
</x-slot>

  <x-slot:footer>
    <x-mail::footer color="green">
      This is an automatic email, don't reply.
    </x-mail::footer>
  </x-slot:footer>
</x-mail::layout>  
