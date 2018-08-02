<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClientTask;
use App\Task;
use App\Comment;
use App\Appointment;
use Auth;

class CommentController extends Controller
{
    public function task($client_task_id)
    {
    	$data['active_class'] = 'tasks';
    	$data['client_task'] = $client_task = ClientTask::find($client_task_id);
    	$data['task'] = Task::find($client_task->task_id);
    	$data['comments'] = Comment::getAllTaskComment($client_task_id);

    	return view('comments.tasks', $data);
    }

    public function taskCommentStore(Request $request)
    {
        Comment::create([
            'activity_id' => $request->client_task_id,
            'activity_type' => 'task',
            'comment' => $request->comment,
            'commenter_id' => Auth::user()->id,
        ]);

        return redirect()->back();
    }

    public function appointment($client_appointment_id)
    {
        $data['active_class'] = 'tasks';
        $data['client_appointment'] = $client_appointment = Appointment::find($client_appointment_id);
        $data['comments'] = Comment::getAllAppointmentComment($client_appointment_id);

        return view('comments.appointments', $data);
    }

    public function appointmentCommentStore(Request $request, $client_appointment_id)
    {
        Comment::create([
            'activity_id' => $client_appointment_id,
            'activity_type' => 'appointment',
            'comment' => $request->comment,
            'commenter_id' => Auth::user()->id,
        ]);

        return redirect()->back();
    }
}
