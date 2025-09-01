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
      <!-- change submit button text content primary-button "Submit Application" to spinner-->
      <form x-data="{ submitting: false }" x-on:submit="submitting = true"
        action="{{ route('job-vacancies.process-application', $jobVacancy->id) }}" method="POST"
        enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($errors->any())
          <div class="bg-red-500 font-bold text-white p-4 rounded">
            <p>The File must be pdf and less than 5MB</p>
          </div>
        @endif
        <div>
          <h3 class="text-xl font-semibold text-white mb-4">Choose Your Resume</h3>
          <div class="mb-6">
            <x-input-label for="resume" class="mb-2" value="Select from your existing resumes:" />
            @forelse ($resumes as $resume)
              <div class="flex items-center gap-2 mb-2">
                <input type="radio" name="resume_option" id="{{ $resume->id }}" value="{{ $resume->id }}" />
                <label for="{{ $resume->id }}" class=" cursor-pointer">

                  {{ $resume->filename }} <span class="text-gray-400 text-sm">(Uploaded on
                    {{ $resume->created_at->format('M d, Y') }})</span>
                </label>
              </div>
            @empty
              <p class="text-gray-400">No resumes found. Please upload a new resume
            @endforelse
          </div>
        </div>
        <div x-data="{ 
            fileName: '',
            hasError: {{ $errors->has('resume_file') ? 'true' : 'false' }},
            fileError: '',
            validateFile(event) {
                const file = event.target.files[0];
                if (!file) {
                    this.fileName = '';
                    this.fileError = '';
                    return;
                }
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    this.fileName = file.name;
                    this.fileError = 'File is too large. Maximum size is 5MB.';
                    event.target.value = ''; // Clear the file input
                } else {
                    this.fileName = file.name;
                    this.fileError = '';
                    $refs.newResumeRadio.checked = true;
                }
            }
        }">
          <div class="flex gap-2 ">
            <input x-ref="newResumeRadio" type="radio" name="resume_option" id="new_resume" value="new_resume" />
            <x-input-label for="new_resume" class="mb-2" value="Upload a new resume:" />
          </div>
          <div class="flex items-center">
            <div class="flex-1">
              <label for="new_resume_file" class="block text-white cursor-pointer">
                <div class="border-2 border-dashed rounded-lg p-4 transition"
                  :class="{ 'hover:border-blue-500': !fileName, 'border-red-500': hasError || fileError }">
                  <input @change="validateFile($event)" type="file" name="resume_file" id="new_resume_file"
                    class="hidden" accept=".pdf" />
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
                    <template x-if="fileError">
                      <p x-text="fileError" class="text-red-500 text-sm mt-2"></p>
                    </template>
                  </div>
                </div>
              </label>
            </div>
          </div>
        </div>
        <x-primary-button class="w-full font-black text-xl">
          <span x-show="!submitting">Submit Application</span>
          <span x-show="submitting" class="inline-flex gap-2 items-center" role="status">
            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
              viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                fill="currentColor" />
              <path
                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                fill="currentFill" />
            </svg>
            We are processing your application...
          </span>
        </x-primary-button>

      </form>
    </div>
  </div>
</x-app-layout>