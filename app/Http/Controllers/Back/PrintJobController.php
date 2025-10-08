<?php

namespace App\Http\Controllers\Back;

use App\Models\PrintJob;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PrintJobsResource;

class PrintJobController extends Controller
{
    use ApiTrait;
    public function index()
    {
        $jobs = PrintJob::where('status', 'pending')->get();

        return $this->successCollection(PrintJobsResource::class, $jobs);
    }

    public function markAsDone(Request $request, $jobId)
    {
        $job = PrintJob::findOrFail($jobId);
        $job->status = 'done';
        $job->save();

        return response()->json(['message' => 'Job marked as done.']);
    }

    public function markAsInProgress(Request $request, $jobId)
    {
        $job = PrintJob::findOrFail($jobId);
        $job->status = 'in_progress';
        $job->save();

        return response()->json(['message' => 'Job marked as in progress.']);
    }
}
