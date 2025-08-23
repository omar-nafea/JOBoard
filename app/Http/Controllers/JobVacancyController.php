<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyJobRequest;
use App\Http\Requests\ResumeRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use Cloudinary\Cloudinary;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;



class JobVacancyController extends Controller
{
  //
  public function show(string $id)
  {
    $jobVacancy = JobVacancy::findOrFail($id);
    return view('job-vacancies.show', compact('jobVacancy'));
  }

  public function apply(string $id)
  {
    $jobVacancy = JobVacancy::findOrFail($id);
    $resumes = Auth::user()->resumes;
    return view('job-vacancies.apply', compact('jobVacancy', 'resumes'));
  }

  public function processApplication(ResumeRequest $request, string $id): RedirectResponse
  {
    $resumeId = null;
    $extractedInfo = null;
    if ($request->input('resume_option') === 'new_resume') {
      $file = $request->file('resume_file');
      $originalFileName = $file->getClientOriginalName();

      // 1. Initialize Cloudinary
      //    (It automatically uses your .env credentials)
      $cloudinary = new Cloudinary();

      // 2. Upload the file to a 'resumes' folder on Cloudinary
      $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
        'folder' => 'resumes',
        'resource_type' => 'raw', // Important for non-image files like PDFs
      ]);

      $extractedInfo = [
        'summary' => '',
        'skills' => '',
        'experience' => '',
        'education' => '',
      ];

      // 3. Create the Resume record in your database
      $resume = Resume::create([
        'filename' => $originalFileName,
        'fileUrl' => $result['secure_url'], // IMPORTANT: Store the full Cloudinary URL
        'user_id' => auth()->id(),
        'contactDetails' => json_encode([
          'name' => auth()->user()->name,
          'email' => auth()->user()->email,
        ]),
        'summary' => $extractedInfo['summary'],
        'skills' => $extractedInfo['skills'],
        'experience' => $extractedInfo['experience'],
        'education' => $extractedInfo['education'],
      ]);

      // 4. Create the JobApplication record (this part stays the same)

      $resumeId = $resume->id;
    } else {
      $resumeId = $request->input('resume_option');
      $resume = Resume::findOrFail($resumeId);

      $extractedInfo = [
        'summary' => $resume->summary,
        'skills' => $resume->skills,
        'experience' => $resume->experience,
        'education' => $resume->education,
      ];

      // 4. Create the JobApplication record (this part stays the same)
    }

    JobApplication::create([
      'status' => 'pending',
      'job_vacancy_id' => $id,
      'resume_id' => $resumeId,
      'user_id' => auth()->id(),
      'aiGeneratedScore' => 0,
      'aiGeneratedFeedback' => '',
    ]);

    return redirect()->route('job-applications.index')
      ->with('success', 'Application submitted successfully');
  }
}
