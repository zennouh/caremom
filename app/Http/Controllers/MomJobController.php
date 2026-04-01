<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;

class MomJobController extends Controller
{

    public function index(Request $request)
    {
        $mom_id = $request->input('mom_id');

        $jobs = Job::with(["applications" => function ($query) use ($mom_id) {
            $query->select('id', 'job_id', 'mom_id')
                ->where('mom_id', '!=', $mom_id);
        }])
            ->where('is_active', true)
            ->orderBy('jobs.created_at', 'desc')
            ->get();
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
