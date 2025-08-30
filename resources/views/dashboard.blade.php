<x-app-layout>
  <div class="py-6">
    <div class="dark:bg-gray-900 shadow-lg rounded-lg p-6 max-w-7xl mx-auto">
      <h3 class="text-white text-2xl font-bold mb-4">
        {{ __('Welcome Back,') }} {{ Auth::user()->name }}!
      </h3>

      <div class="flex items-center justify-between ">
        <form action="{{ route('dashboard') }}" method="get"
          class="flex items-center justify-center w-1/4 focus:outline-none">

          <div class="relative">
            <input type="text" id="searchBar" name="search" value="{{ request('search') }}"
              class="w-full border-0 px-4 p-2 rounded-l-lg bg-gray-800 focus:outline-none text-white"
              placeholder="Search for a job">
            @if(request('search'))
              <a href="{{ route('dashboard', ['search' => null, 'filter' => request('filter')]) }}"
                class="absolute inset-y-0 right-2 flex items-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0,0,256,256"
                  style="fill:#FFFFFF;">
                  <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt"
                    stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0"
                    font-family="none" font-weight="none" font-size="none" text-anchor="none"
                    style="mix-blend-mode: normal">
                    <g transform="scale(10.66667,10.66667)">
                      <path
                        d="M12,2c-5.511,0 -10,4.489 -10,10c0,5.511 4.489,10 10,10c5.511,0 10,-4.489 10,-10c0,-5.511 -4.489,-10 -10,-10zM12,4c4.43012,0 8,3.56988 8,8c0,4.43012 -3.56988,8 -8,8c-4.43012,0 -8,-3.56988 -8,-8c0,-4.43012 3.56988,-8 8,-8zM8.70703,7.29297l-1.41406,1.41406l3.29297,3.29297l-3.29297,3.29297l1.41406,1.41406l3.29297,-3.29297l3.29297,3.29297l1.41406,-1.41406l-3.29297,-3.29297l3.29297,-3.29297l-1.41406,-1.41406l-3.29297,3.29297z">
                      </path>
                    </g>
                  </g>
                </svg>
              </a>
            @endif
          </div>
          <button type="submit"
            class="justify-center items-center rounded-r-lg bg-gradient-to-r from-indigo-500 to-rose-500 px-4 py-2 text-white transition-all duration-500 ease-linear hover:bg-gradient-to-r hover:from-indigo-600 hover:to-rose-600">Search</button>
          @if(request('filter'))
            <input type="hidden" id="filter" name="filter" value="{{ request('filter') }}">
          @endif
        </form>

        <!-- Filters -->
        <div x-data="{ activeFilter: '{{ request('filter', 'All') }}' }" class="flex space-x-2">
          <a href="{{ route('dashboard', ['filter' => 'All', 'search' => request('search')]) }}"
            :class="{ 'ring-2 ring-white': activeFilter === 'All' }"
            class="bg-transparent text-white p-2 min-w-16 flex justify-center items-center cursor-pointer transition-all duration-300">
            All
          </a>
          <a href="{{ route('dashboard', ['filter' => 'Full-time', 'search' => request('search')]) }}"
            :class="{ 'ring-2 ring-white': activeFilter === 'Full-time' }"
            class="bg-transparent text-white p-2 min-w-16 flex justify-center items-center cursor-pointer transition-all duration-300">
            Full-Time
          </a>
          <a href="{{ route('dashboard', ['filter' => 'Remote', 'search' => request('search')]) }}"
            :class="{ 'ring-2 ring-white': activeFilter === 'Remote' }"
            class="bg-transparent text-white p-2 min-w-16 flex justify-center items-center cursor-pointer transition-all duration-300">
            Remote
          </a>
          <a href="{{ route('dashboard', ['filter' => 'Hybrid', 'search' => request('search')]) }}"
            :class="{ 'ring-2 ring-white': activeFilter === 'Hybrid' }"
            class="bg-transparent text-white p-2 min-w-16 flex justify-center items-center cursor-pointer transition-all duration-300">
            Hybrid
          </a>
        </div>
      </div>
      <div class="space-y-4 mt-6">
        @forelse ($jobs as $job)
          <div class="border-b border-white/10 pb-4 flex justify-between items-center">
            <div>
              <a href="{{ route('job-vacancies.show', $job->id) }}"
                class="text-lg font-semibold cursor-pointer text-blue-400 hover:underline">{{ $job->title }}</a>
              <p class="text-sm text-white">{{ $job->company->name }} - {{ $job->location }}</p>
              @if (is_numeric($job->salary))
                <p class="text-sm text-white">${{ number_format($job->salary, 2) }} / Year</p>
              @else
                <p class="text-sm text-white">Salary Not Specified</p>
              @endif
            </div>
            <div x-data="{ jobType: '{{ $job->type }}' }">
              <span class="bg-transparent text-white min-w-20 flex justify-center items-center p-2 ring-2"
                :class="{ 'ring-red-500': jobType === 'Hybrid', 'ring-yellow-600': jobType === 'Full-time', 'ring-green-500': jobType === 'Remote'}">
                {{ $job->type }}
              </span>
            </div>
          </div>
        @empty
          <p class="text-lg text-white">No job found.</p>
        @endforelse
      </div>
      <div class="mt-6">
        {{ $jobs->links() }}
      </div>
    </div>
  </div>

</x-app-layout>