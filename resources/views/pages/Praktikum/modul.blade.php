<x-app-layout>
   <section class="min-h-screen py-24 bg-gradient-to-t from-light-green to-white">
        <div class="container px-12 mx-auto flex items-center mb-4">
            <a class="flex-none text-dark-green" href="{{ route('praktikum.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 flex-none text-dark-green">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-2xl flex-1 font-bold text-center text-dark-green tracking-wide">
                Praktikum {{ $praktikum->name }}
            </h1>
       </div>
       <div class="container mx-auto px-12 flex-wrap flex justify-center items-center gap-8">
           @forelse ($moduls as $modul)    
           <div class="w-52 relative group">
               <div class="h-14 w-full text-black shadow-md bg-light-green hover:bg-gradient-to-r from-gray-950 to-dark-green hover:text-white transition-all duration-300 cursor-pointer flex justify-center items-center rounded-2xl mb-2 modul">
                   <p>Modul {{ $modul->modul_ke }}</p>
               </div>
               <div class="absolute top-full left-0 w-full bg-white p-4 rounded-2xl shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 ease-in-out z-10">
                   <a class="flex justify-center items-center text-white h-10 w-full bg-gradient-to-r from-gray-950 to-dark-green text-sm rounded-lg" href="{{ route('moduls.download', $modul->id) }}">Download Modul</a>
               </div>
           </div>
           @empty
           <div class="w-full text-center text-gray-500 text-lg">
               Belum ada modul yang tersedia.
           </div>
           @endforelse
       </div>
   </section>
</x-app-layout>