<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class resumeRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      //
      'resume_file' => 'required|file|mimes:pdf|max:5120',
    ];
  }
  public function messages(): array
  {
    return [
      'resume_file.required' => 'Please upload your resume.',
      'resume_file.file' => 'The resume must be a file.',
      'resume_file.mimes' => 'The resume must be a PDF file.',
      'resume_file.max' => 'The resume must not be greater than 5MB.',
    ];
  }
}
