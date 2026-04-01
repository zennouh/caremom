<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;

class EnterpriseJobController extends Controller
{
    // create job
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'company_name' => 'required|string',
            'location' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'employment_type' => 'required|string',
            "experience_level" => 'required|string'
        ]);

        /**
         {
        "title": "Software Engineer",
        "description": "We are looking for a skilled software engineer to join our team.",
        "company_name": "Tech Solutions Inc.",
        "location": "San Francisco, CA",
        "salary": 120000,
        "employment_type": "Full-time",
        "experience_level": "Mid"
        }
         */

        $job = Job::create($data);

        return response()->json(
            [
                'message' => 'Job created successfully',
            ],
            201,
        );
    }

    // update job
    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);

        $job->update($request->all());

        return response()->json($job);
    }

    // delete job
    public function destroy($id)
    {
        Job::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Job deleted'
        ]);
    }

    // list applicants
    public function applicants($jobId)
    {
        return Application::where('job_id', $jobId)
            ->get();
    }
}
