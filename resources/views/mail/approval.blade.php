<p>
	Dear {{ $client->name }},
	<br>
	<br>
	The following task: 
	<br><br>
	<li><strong>{{ $task->task_name }}</strong></li>
	<br><br>
	has been <strong>{{ $status }}</strong> by <strong>{{ $assignee->name }}</strong>. 
    For more information give is a call at <strong>01778-000400</strong> or visit our office at 1st Floor, Plot 56/b, Road No 132, Gulshan 1, Dhaka 1212.
    <br>
    <br>
    Thank you.
</p>