<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskPlanner;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $userId = request()->query('user');
            $task = TaskPlanner::whereUserId($userId)->latest()->get();
            if (is_null($task->first())) {
                $response = [ 'status'=>false, 'message'=>"No task found!"];
                return response()->json($response, 200);
            }
            $response = [ 'status'=>true, 'message'=>"Task is retrieved successfully.", 'data'=>$task];
            return response()->json($response, 200);
            
        } catch (Exception $err) {
            Log::channel('task')->error($err->getMessage());
            $response = [ 'status'=>false, 'message'=>"something went wrong."];
            return response()->json($response, 500);
        }
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'title' => 'required|max:50',
                    'description' => 'required',
                    'deadline' => 'required',
                    'user_id' => 'required|numeric'
                ]
            );
    
            if ($validator->fails()) {
                $message = $validator->getMessageBag()->first();
                $response = [ 'status'=>false, 'message'=>$message];
                return response()->json($response, 400);
            }
    
            # Store task...
            $task = TaskPlanner::create([
                'title'=>$request->title??'',
                'description'=>$request->description??'',
                'deadline'=>$request->deadline??'',
                'user_id'=>$request->user_id??''
            ]);
    
            if ($task) {
                $response = [ 'status'=>true, 'message'=>"task created successfully.", 'data'=>$task];
                return response()->json($response, 201);
            }else{
                $response = [ 'status'=>false, 'message'=>"something went wrong."];
                return response()->json($response, 500);
            }
        } catch (Exception $err) {
            Log::channel('task')->error($err->getMessage());
            $response = [ 'status'=>false, 'message'=>"something went wrong."];
            return response()->json($response, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $task = TaskPlanner::find($id);
  
            if (is_null($task)) {
                $response = [ 'status'=>false, 'message'=>"Task is not found!."];
                return response()->json($response, 200);
            }
    
            $response = [ 'status'=>true, 'message'=>"Task is retrieved successfully.", 'data'=>$task];
            return response()->json($response, 200);

        } catch (Exception $err) {
            Log::channel('task')->error($err->getMessage());
            $response = [ 'status'=>false, 'message'=>"something went wrong."];
            return response()->json($response, 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $task = TaskPlanner::where('title', 'like', '%'.$request->search.'%')->whereUserId($request->user)->get();
  
            if (is_null($task->first())) {
                $response = [ 'status'=>false, 'message'=>'Result not found for "'.$request->search.'"'];
                return response()->json($response, 200);
            }
    
            $response = [ 'status'=>true, 'message'=>'Showing result for "'.$request->search.'"', 'data'=>$task];
            return response()->json($response, 200);

        } catch (Exception $err) {
            Log::channel('task')->error($err->getMessage());
            $response = [ 'status'=>false, 'message'=>"something went wrong."];
            return response()->json($response, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id) {
        // some code....
    }

    public function taskUpdate(Request $request, string $id)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'title' => 'required|max:50',
                    'description' => 'required',
                    'deadline' => 'required',
                    'status' => 'required|numeric'
                ]
            );
    
            if ($validator->fails()) {
                $message = $validator->getMessageBag()->first();
                $response = [ 'status'=>false, 'message'=>$message];
                return response()->json($response, 400);
            }
    
            # update task...
            $task = TaskPlanner::find($id);
            if (is_null($task)) {
                $response = [ 'status'=>false, 'message'=>"Task is not found!."];
                return response()->json($response, 200);
            }

            $task->update([
                'title'=>$request->title??'',
                'description'=>$request->description??'',
                'deadline'=>$request->deadline??'',
                'status'=>$request->status??''
            ]);
    
            if ($task) {
                $response = [ 'status'=>true, 'message'=>"task updated successfully.", 'data'=>$task];
                return response()->json($response, 201);
            }else{
                $response = [ 'status'=>false, 'message'=>"something went wrong."];
                return response()->json($response, 500);
            }
        } catch (Exception $err) {
            Log::channel('task')->error($err->getMessage());
            $response = [ 'status'=>false, 'message'=>"something went wrong."];
            return response()->json($response, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
           
            $task = TaskPlanner::find($id);
  
            if (is_null($task)) {
                $response = [ 'status'=>false, 'message'=>"Task is not found!."];
                return response()->json($response, 200);
            }

            TaskPlanner::destroy($id);
            $response = [ 'status'=>true, 'message'=>"Task is deleted successfully."];
            return response()->json($response, 200);
            
            
        } catch (Exception $err) {
            Log::channel('task')->error($err->getMessage());
            $response = [ 'status'=>false, 'message'=>"something went wrong."];
            return response()->json($response, 500);
        }
    }

    public function getData() {
        try {
            $user = auth()->user();
            $data = [
                'name' => $user->name,
                'id' => $user->id,
            ];
    
            $response = [ 'status'=>true, 'message'=>'Data fetched successfully', 'data'=>$data];
            return response()->json($response, 200);

        } catch (Exception $err) {
            Log::channel('task')->error($err->getMessage());
            $response = [ 'status'=>false, 'message'=>"something went wrong."];
            return response()->json($response, 500);
        }
    }

}
