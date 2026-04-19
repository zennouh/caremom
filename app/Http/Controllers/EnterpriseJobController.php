<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EnterpriseJobController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('company_id')) {

            $companyId = $request->query('company_id');
            return Job::where('company_id', $companyId)->get();
        } else {
            return Job::all();
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'company_id' => 'required|integer',
            'company_name' => 'required|string',
            'location' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'employment_type' => 'required|in:full-time,part-time,remote',
            "experience_level" => 'required|in:junior,mid,senior'
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors()
                ],
                422,
            );
        }

        $validated = $validator->validated();

        /**
         {
        "title": "Software Engineer",
        "company_id": 1,
        "description": "We are looking for a skilled software engineer to join our team.",
        "company_name": "Tech Solutions Inc.",
        "location": "San Francisco, CA",
        "salary": 120000,
        "employment_type": "Full-time",
        "experience_level": "Mid"
        }

         */

        $job = Job::create($validated);

        return response()->json(
            [
                'message' => 'Job created successfully',
                'job' => $job
            ],
            201,
        );
    }


    // public function update(Request $request, $id)
    // {
    //     $job = Job::findOrFail($id);

    //     $job->update($request->all());

    //     return response()->json($job);
    // }


    public function destroy($id)
    {
        Job::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Job deleted'
        ]);
    }


    public function applicants($jobId)
    {
        $connection = DB::connection("auth_db");
        $usersTable = $connection->table("users");
        /// empolyer 
        $apps = Application::where('job_id', $jobId)
            ->get();

        $apps->each(function ($app) use ($usersTable) {
            $user = $usersTable
                ->select('id', 'name', 'email', 'phone', 'avatar')
                ->where('id', $app->mom_id)
                ->first();
            $app->mom = $user;
        });

        return $apps;
        /**
         *
[
    {
        "id": 1,
        "job_id": 2,
        "mom_id": 3,
        "status": "pending",
        "cover_letter": "please i need it, please i need it, please i need it, please i need it,",
        "applied_at": "2026-04-14 08:24:43",
        "created_at": "2026-04-14T08:24:42.000000Z",
        "updated_at": "2026-04-14T08:24:42.000000Z",
        "mom": {
            "id": 3,
            "name": "jesika2004",
            "email": "jesika2004@gmail.com",
            "phone": null,
            "avatar": null
        }
    }
]
         */
    }

    public function updateStatus(Request $request, $applicationId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:accepted,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors()
                ],
                422,
            );
        }

        $validated = $validator->validated();

        $application = Application::findOrFail($applicationId);

        $application->update([
            'status' => $validated['status']
        ]);

        return response()->json($application);
    }
}
