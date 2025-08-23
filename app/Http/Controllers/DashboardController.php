<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index(Request $request)
  {
    // Check if there is a search query parameter
    $query = JobVacancy::query()
      ->when($request->has('search'), function ($query) use ($request) {
        $searchTerm = '%' . $request->search . '%';
        $query->where(function ($q) use ($searchTerm) {
          $q->where('title', 'like', $searchTerm)
            ->orWhere('location', 'like', $searchTerm)
            ->orWhereHas('company', function ($companyQuery) use ($searchTerm) {
              $companyQuery->where('name', 'like', $searchTerm);
            });
        });
      })
      ->when($request->input('filter') && $request->input('filter') !== 'All', function ($query) use ($request) {
        $query->where('type', $request->filter);
      });
    $jobs = $query->latest()->paginate(10)->withQueryString();
    return view('dashboard', compact('jobs'));
  }
}
/* the withQueryString() method is used primarily with pagination to ensure that 
all existing query string parameters from the current request are preserved in the pagination
 links.
 When a user filters or sorts data using query parameters (e.g., ?sort=name&filter=active),
 and then navigates to another page of the paginated results, 
 these parameters would typically be lost. The withQueryString() method prevents
 this by automatically appending all current query string parameters 
 to the generated pagination links.
*/