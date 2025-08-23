<x-main-layout title="Shaghaln1 - Find your dream job">

  <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
    <div class="inline-flex items-center mb-2" x-cloak x-show="show"
      x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-90"
      x-transition:enter-end="opacity-100 scale-100">
      <h4 class="text-sm text-white/60 rounded-full bg-white/10 px-3 py-1 w-fit">Shaghalni</h4>
    </div>
  </div>

  <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
    <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
      x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
      <h1 style="font-size: 10rem;" class=" sm:text-6xl md:text-8xl font-bold mb-6 tracking-tight">
        <span class="text-white">Find your</span><br />
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-rose-500 font-serif italic">Dream
          Job</span>
      </h1>
    </div>
  </div>

  <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
    <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
      x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
      <p class="text-lg sm:text-xl md:text-2xl text-white/60 mb-8">
        Explore the perfect match for your skills and aspirations.
      </p>
    </div>
  </div>
  <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
    <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
      x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
      <a href="{{ route('register') }}" class="rounded-lg bg-white/10 px-4 py-2 text-white">Create an Account</a>
      <a href="{{ route('login') }}"
        class="rounded-lg bg-gradient-to-r from-indigo-500 to-rose-500 ml-2 px-4 py-2 text-white transition-all duration-500 ease-linear hover:bg-gradient-to-r hover:from-indigo-700 hover:to-rose-700">Login</a>
    </div>
  </div>

</x-main-layout>