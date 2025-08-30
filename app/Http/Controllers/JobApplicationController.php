<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Resume;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
  public function index()
  {
    $id = auth()->id();
    $jobApplications = JobApplication::where('user_id', $id)->latest()->paginate(5);
    return view('job-applications.index', compact('jobApplications'));
  }
}
