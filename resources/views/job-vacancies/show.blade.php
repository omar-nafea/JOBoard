<x-app-layout>
  <div class="py-2 max-w-7xl mx-auto">
    <div class="inline-flex items-center mb-4 md:mb-0">
      <a href="{{ route('dashboard') }}"
        class="inline-flex items-center px-4 py-2 bg-zinc-900 text-white rounded-l-lg shadow-md hover:translate-x-[-4px] hover:bg-gray-700 transition-all duration-200 ease-out">
        ← Back
      </a>
    </div>
    <div class="dark:bg-gray-900 shadow-lg rounded-lg p-6 max-w-7xl mx-auto">
      <!-- First Row -->
      <div class="flex flex-col md:flex-row justify-between items-center rounded-xl shadow-lg p-8 mb-10">
        <!-- create back button -->
        <div class="flex flex-col space-y-2 text-left w-full md:w-2/3">
          <h1 class="text-4xl font-extrabold text-white mb-2">{{ $jobVacancy->title }}</h1>
          <div class="text-xl text-indigo-400 mb-2 font-semibold">{{ $jobVacancy->company->name }}
          </div>
          <div class="flex flex-wrap gap-4 mt-2">
            <span class="inline-flex items-center px-3 py-1 bg-gray-700 text-gray-300 rounded-full text-sm">
              {{ $jobVacancy->location }}
            </span>
            <span class="inline-flex items-center px-3 py-1 bg-gray-700 text-gray-300 rounded-full text-sm">
              ${{ number_format($jobVacancy->salary) }}
            </span>
            <span class="inline-flex items-center px-3 py-1 bg-gray-700 text-gray-300 rounded-full text-sm">
              {{ $jobVacancy->type }}
            </span>
          </div>
        </div>
        <div class="mt-6 md:mt-0 w-full md:w-auto flex justify-end">
          <a href="{{ route('job-vacancies.apply', $jobVacancy->id) }}"
            class="bg-gradient-to-r from-indigo-500 to-rose-500 hover:from-indigo-600 hover:to-rose-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg transition duration-200 text-xl">Apply
            Now</a>
        </div>
      </div>

      <!-- Second Row -->
      <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Description Section (First 2 columns) -->
        <div class="md:col-span-8  rounded-xl shadow-lg p-8 bg-gray-800 text-gray-200">
          <h2 class="text-2xl font-bold mb-4 text-indigo-400">Job Description</h2>
          <p class="text-lg leading-relaxed">{{ $jobVacancy->description }}</p>
        </div>
        <!-- Card Section (Third column) -->
        <div class="md:col-span-4">
          <div class="bg-gray-800 rounded-xl shadow-lg p-8 flex flex-col space-y-4">
            <div class="space-y-4">
              <div>
                <p class="text-sm text-gray-400">Published Date</p>
                <p class="text-lg">{{ $jobVacancy->created_at->format('M d, Y') }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-400">Company</p>
                <p class="text-lg">{{ $jobVacancy->company->name }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-400">Location</p>
                <p class="text-lg">{{ $jobVacancy->location }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-400">Salary</p>
                <p class="text-lg">${{ number_format($jobVacancy->salary) }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-400">Type</p>
                <p class="text-lg">{{ $jobVacancy->type }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-400">Category</p>
                <p class="text-lg">{{ $jobVacancy->jobCategory->name}}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>