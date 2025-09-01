<?php

namespace App\Services;

use App\Models\JobVacancy;
use Gemini;
use Spatie\PdfToText\Pdf;
use Illuminate\Http\UploadedFile;

class ResumeAnalysisService
{
  /**
   * Analyzes a resume against a job description.
   *
   * @param UploadedFile $file The resume PDF file.
   * @param JobVacancy $jobVacancy The job vacancy to compare against.
   * @return array An array containing parsed data, score, and feedback.
   */
  public function analyze(UploadedFile $file, JobVacancy $jobVacancy): array
  {
    // 1. Extract raw text from the PDF file
    try {
      $resumeText = (new Pdf())->setPdf($file->getRealPath())->text();
    } catch (\Exception $e) {
      // In a real app, you might log the error
      // For now, return a default structure indicating failure
      return $this->getDefaultAnalysis();
    }

    // 2. Prepare the prompt for Gemini
    $client = Gemini::client(env('GEMINI_API_KEY'));

    $prompt = $this->buildPrompt($resumeText, $jobVacancy->description);

    // 3. Call the Gemini API
    $response = $client->generativeModel('gemini-1.5-flash-latest') // Use the new model name
      ->generateContent($prompt);
    $jsonString = $response->text();

    // Sanitize response to ensure it's valid JSON
    $jsonString = trim(str_replace(['```json', '```'], '', $jsonString));
    $analysisResult = json_decode($jsonString, true);

    // Return the structured result, with fallbacks if parsing fails
    return [
      'parsedData' => $analysisResult['parsedData'] ?? $this->getDefaultAnalysis()['parsedData'],
      'score' => $analysisResult['aiGeneratedScore'] ?? 0,
      'feedback' => $analysisResult['aiGeneratedFeedback'] ?? 'Could not generate feedback.',
    ];
  }

  /**
   * Builds the detailed prompt for the Gemini API.
   */
  private function buildPrompt(string $resumeText, string $jobDescription): string
  {
    return "You are an expert HR recruitment assistant responsible for analyzing resumes.
        You will be given the full text of a candidate's resume and the description of a job they are applying for.

        Your tasks are:
        1.  Parse the resume text into a structured JSON object. The keys must be: 'contactDetails' (with 'name' and 'email'), 'summary', 'skills' (as an array of strings), 'experience' (as an array of strings), and 'education' (as an array of strings). If a section is not found, return an empty string or an empty array.
        2.  Carefully compare the parsed resume against the job description.
        3.  Provide an 'aiGeneratedScore' from 1.0 to 10.0, with one decimal place., where 1.0 is a very poor match and 10.0 is a perfect match.
        4.  Provide 'aiGeneratedFeedback' as a brief, 2-3 sentence explanation for your score, highlighting strengths and weaknesses.

        Your final output MUST be a single, valid JSON object with no other text before or after it.
        The JSON object must have three top-level keys: 'parsedData', 'aiGeneratedScore', and 'aiGeneratedFeedback'.

        ---
        JOB DESCRIPTION:
        {$jobDescription}
        ---
        RESUME TEXT:
        {$resumeText}
        ---";
  }

  /**
   * Provides a default structure in case of an error.
   */
  private function getDefaultAnalysis(): array
  {
    return [
      'parsedData' => [
        'contactDetails' => [],
        'summary' => '',
        'skills' => [],
        'experience' => [],
        'education' => []
      ],
      'score' => 0,
      'feedback' => 'Automated analysis failed. Could not read resume text.',
    ];
  }
}