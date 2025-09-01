<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyJobRequest;
use App\Http\Requests\ResumeRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Services\ResumeAnalysisService;
use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Spatie\PdfToText\Pdf;
use Gemini; //
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

  public function processApplication(ResumeRequest $request, string $id, ResumeAnalysisService $resumeAnalysisService): RedirectResponse
  {
    $jobVacancy = JobVacancy::findOrFail($id);
    $resumeId = null;
    $aiScore = 0;
    $aiFeedback = '';

    if ($request->input('resume_option') === 'new_resume') {
      $file = $request->file('resume_file');
      $originalFileName = $file->getClientOriginalName();

      // --- 1. UPLOAD TO CLOUDINARY ---
      $cloudinary = new Cloudinary();
      $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
        'folder' => 'resumes',
        'resource_type' => 'auto', // Use 'auto' instead of 'raw'
      ]);

      // The upload result includes a 'format' key (e.g., "pdf"). We add it here.
      $fileReference = 'v' . $result['version'] . '/' . $result['public_id'] . '.' . $result['format'];


      // --- 2. CALL THE ANALYSIS SERVICE ---
      $analysisResult = $resumeAnalysisService->analyze($file, $jobVacancy);

      // --- 3. CREATE THE RESUME RECORD ---
      $resume = Resume::create([
        'filename' => $originalFileName,
        'fileUrl' => $fileReference,
        'user_id' => auth()->id(),
        'contactDetails' => json_encode($analysisResult['parsedData']['contactDetails']),
        'summary' => $analysisResult['parsedData']['summary'],
        'skills' => json_encode($analysisResult['parsedData']['skills']),
        'experience' => json_encode($analysisResult['parsedData']['experience']),
        'education' => json_encode($analysisResult['parsedData']['education']),
      ]);

      $resumeId = $resume->id;
      $aiScore = $analysisResult['score'];
      $aiFeedback = $analysisResult['feedback'];

    } else {
      // If using an existing resume, you could optionally re-analyze it here
      // For now, we'll skip re-analysis to save API calls
      $resumeId = $request->input('resume_option');
      $resume = Resume::findOrFail($resumeId);


      // 1. Get the full, public URL of the existing resume
      $fileUrl = $resume->fileUrl;

      // 2. Download the file content from Cloudinary
      $fileContent = Http::get($fileUrl)->body();

      // 3. Create a temporary file on your server to hold the content
      $tempPath = tempnam(sys_get_temp_dir(), 'resume');
      file_put_contents($tempPath, $fileContent);

      // 4. Create an UploadedFile instance from the temporary file
      $file = new UploadedFile(
        $tempPath,
        $resume->filename,
        'application/pdf', // <-- Simply provide the known MIME type
        null,
        true
      );

      // 5. Call the analysis service with the existing resume
      $analysisResult = $resumeAnalysisService->analyze($file, $jobVacancy);
      $aiScore = $analysisResult['score'];
      $aiFeedback = $analysisResult['feedback'];

      // 6. Clean up the temporary file
      unlink($tempPath);
    }

    // --- 4. CREATE THE JOB APPLICATION ---
    JobApplication::create([
      'status' => 'pending',
      'job_vacancy_id' => $id,
      'resume_id' => $resumeId,
      'user_id' => auth()->id(),
      'aiGeneratedScore' => $aiScore,
      'aiGeneratedFeedback' => $aiFeedback,
    ]);

    return redirect()->route('job-applications.index')
      ->with('success', 'Application submitted successfully');
  }
}
