<?php

namespace App\Http\Controllers;

use App\Task;
use Gate;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\PostDec;


class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public  function getAllTasks(){
$tasks = Task::orderby('created_at','desc')->get();

return view('task.index',['tasks'=>$tasks]);
 }

 public function AddTask(Request $request){
$this->validate($request,[
    'task'=>'required|min:4|regex:/(\w.+\s).+/'
]);
$task = new Task();
$task->task = $request->input('task');
$task->user_id = auth()->user()->id;
$task->save();
return redirect()->route('get_home')->with('success', 'Task Created');
 }

 public  function deleteTask($id){
$task =Task::find($id);
if (Gate::denies('auth-only',$task)){
    return redirect()->back()->with('error','You are not authorized to delete this! bee');
}

$task->delete();

return redirect()->route('get_home')->with('success','Task Deleted');
 }

 public function getEditTask($id){
$task = Task::find($id);
return view('task.edit',['task'=>$task,'id'=>$id]);

 }

 public function postEditTask(Request $request){

$this->validate($request,[
    'task'=>'required|min:4|regex:/(\w.+\s).+/'
]);
$task1 = Task::find($request->input('id'));
     if(Gate::denies('auth-only',$task1)){
         return redirect()->back()->with([
             'error' => 'Unauthorized action'
         ]);
     }
$task1->task =$request->input('task');
$task1->save();

return redirect()->route('get_home')->with('success', 'Task Edited');
 }
}


