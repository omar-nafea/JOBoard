<x-app-layout>
  <div class="py-2 max-w-7xl mx-auto">
    <div class="inline-flex items-center mb-4 md:mb-0">
      <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}"
        class="inline-flex items-center px-4 py-2 bg-zinc-900 text-white rounded-l-lg shadow-md hover:translate-x-[-4px] hover:bg-gray-700 transition-all duration-200 ease-out">
        ← Back
      </a>
    </div>
    <div class="dark:bg-gray-900 p-6 ">
      <div class="flex flex-col md:flex-row justify-between items-center rounded-xl  p-8 ">
        <div class="flex flex-col space-y-2 text-left w-full">
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
      </div>
      <form action="{{ route('job-vacancies.process-application', $jobVacancy->id) }}" method="POST" class="space-y-6">
        @csrf

        <div>
          <h3 class="text-xl font-semibold text-white mb-4">Choose Your Resume</h3>
          <div class="mb-6">
            <x-input-label for="resume" value="Select from your existing resumes:" />
          </div>
        </div>

        <div x-data="{ fileName: '', hasError: {{ $errors->has('resume_file') ? 'true' : 'false' }} }">
          <x-input-label for="resume" class="mb-2" value="Or upload a new resume:" />
          <div class="flex items-center">
            <div class="flex-1">
              <label for="new_resume_file" class="block text-white cursor-pointer">
                <div class="border-2 border-dashed border-gray-600 rounded-lg p-4 hover:border-blue-500 transition"
                  :class="{ 'border-blue-500': fileName, 'border-red-500': hasError }">
                  <input @change="fileName = $event.target.files[0].name" type="file" name="resume_file"
                    id="new_resume_file" class="hidden" accept=".pdf" />
                  <div class="text-center">
                    <template x-if="!fileName">
                      <p class="text-gray-400">Click to upload PDF (Max 5MB)</p>
                    </template>

                    <template x-if="fileName">
                      <div>
                        <p x-text="fileName" class="mt-2 text-blue-400"></p>
                        <p class="text-gray-400 text-sm mt-1">Click to change file</p>
                      </div>
                    </template>
                  </div>
                </div>
              </label>
            </div>
          </div>
        </div>
        <x-primary-button class="w-full font-black text-xl">Submit Application</x-primary-button>
      </form>
    </div>
  </div>
</x-app-layout>