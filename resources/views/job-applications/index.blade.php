<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-white leading-tight">
      {{ __('Job Applications') }}
    </h2>
  </x-slot>
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class=" shadow-sm sm:rounded-lg">
        <div class="space-y-4 m-6  rounded-lg p-4">
          @forelse ($jobApplications as $jobApplication)
            <div
              class="border-b border-white/10 bg-gray-800 p-4 rounded-lg pb-4 flex justify-between items-center transition-transform hover:scale-[1.02]">
              <div>
                <a href="{{ route('job-vacancies.show', $jobApplication->jobVacancy->id) }}"
                  class="text-lg font-semibold cursor-pointer text-blue-400 hover:underline">{{ $jobApplication->jobVacancy->title }}</a>
                <p class="text-sm text-white">{{ $jobApplication->jobVacancy->company->name }} -
                  {{ $jobApplication->jobVacancy->location }}
                </p>
                @if (is_numeric($jobApplication->jobVacancy->salary))
                  <p class="text-sm text-white">${{ number_format($jobApplication->jobVacancy->salary, 2) }} / Year</p>
                @else
                  <p class="text-sm text-white">Salary Not Specified</p>
                @endif
                <div class="mb-2">
                  <span class="text-white font-semibold">Company:</span>
                  <span class="text-white">{{ $jobApplication->jobVacancy->company->name }}</span>
                </div>
                <div class="mb-2">
                  <span class="text-white font-semibold">Resume:</span>
                  <a href="{{ $jobApplication->resume->fileUrl }}" target="_blank"
                    class="underline text-pink-200 hover:text-white font-bold">{{ $jobApplication->resume->filename }}</a>
                </div>
                <div class="mb-2">
                  <span class="text-white font-semibold">AI Score:</span>
                  <span class="text-white">{{ $jobApplication->aiGeneratedScore ?? 'N/A' }}</span>
                </div>
                <div class="mb-2">
                  <span class="text-white font-semibold">AI Feedback:</span>
                  <span class="text-white">{{ $jobApplication->aiGeneratedFeedback ?? 'N/A' }}</span>
                </div>
              </div>
              <div class="flex flex-col gap-4"
                x-data="{ jobType: '{{ $jobApplication->jobVacancy->type }}', status: '{{ $jobApplication->status }}' }">
                <span class=" text-zinc-800 px-3 py-1 rounded-full text-xs font-semibold flex justify-center"
                  :class="{'bg-yellow-300': status === 'pending', 'bg-green-500': status === 'accepted', 'bg-red-500': status === 'rejected'}">{{ $jobApplication->status }}</span>
                <span class="bg-transparent text-white min-w-20 flex justify-center items-center p-2 ring-2"
                  :class="{ 'ring-red-500': jobType === 'Hybrid', 'ring-yellow-600': jobType === 'Full-time', 'ring-green-500': jobType === 'Remote'}">
                  {{ $jobApplication->jobVacancy->type }}
                </span>
              </div>
            </div>
          @empty
            <p class="text-lg text-white">No App found.</p>
          @endforelse
        </div>
        <div>
          {{ $jobApplications->links() }}
        </div>
      </div>
    </div>
</x-app-layout>