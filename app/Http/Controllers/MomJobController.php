<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;

class MomJobController extends Controller
{

    // filter by: apply, accepted, rejected, pending
    public function index(Request $request)
    {
        $type = $request->query('type', 'all'); // all | applied | non-applied
        $status = $request->query('status'); // accepted | rejected | pending
        $mom_id = $request->input('mom_id');

        $jobs = Job::query()

            ->when($type === 'applied', function ($q) use ($mom_id, $status) {
                $q->whereHas('applications', function ($q2) use ($mom_id, $status) {
                    $q2->where('mom_id', $mom_id);
                    if ($status) {
                        $q2->where('status', $status);
                    }
                });
            })
            ->when($type === 'non-applied', function ($q) use ($mom_id) {
                $q->whereDoesntHave('applications', function ($q2) use ($mom_id) {
                    $q2->where('mom_id', $mom_id);
                });
            })
            ->with([
                'applications' => function ($query) use ($mom_id) {
                    $query->select('id', 'job_id', 'mom_id', 'status')
                        ->where('mom_id', $mom_id);
                }
            ])

            ->withExists([
                'applications as has_applied' => function ($query) use ($mom_id) {
                    $query->where('mom_id', $mom_id);
                }
            ])

            ->where('is_active', true)
            ->orderBy('jobs.created_at', 'desc')
            ->get();

        // return $jobs;
        return $jobs;
    }


    public function show($id)
    {
        return Job::findOrFail($id);
    }


    public function apply(Request $request, $jobId)
    {
        $request->validate([
            'mom_id' => 'required|integer',
            'cover_letter' => 'nullable|string'
        ]);



        $alreadyApplied = Application::where('job_id', $jobId)
            ->where('mom_id', $request->mom_id)
            ->exists();


        if ($alreadyApplied) {
            return response()->json([
                'message' => 'You already applied to this job'
            ], 400);
        }

        $application = Application::create([
            'job_id' => $jobId,
            'mom_id' => $request->mom_id,
            'cover_letter' => $request->cover_letter,
        ]);




        return response()->json($application, 201);
    }


    public function myApplications($momId)
    {
        return Application::with('job')
            ->where('mom_id', $momId)
            ->get();
    }
}
