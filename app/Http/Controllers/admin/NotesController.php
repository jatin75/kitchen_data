<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App\Admin;
use App\Job;
use App\JobNote;


class NotesController extends Controller
{
    public function index() {
        if(Session::get('login_type_id') == 9) {
            $client_id = Session::get('employee_id');
            $getJobId = Job::selectRaw('job_id')->where('company_clients_id', 'like', '%'.$client_id.'%')->where('is_deleted',0)->pluck('job_id')->toArray();

            $jobNotesList = DB::table('job_notes as jn')
                ->selectRaw('jn.id as notes_id, jn.job_note, jn.name, jn.created_at, j.job_id, j.job_title')
                ->join('jobs as j', 'j.job_id', 'jn.job_id')
                ->where('jn.is_deleted', 0)
                ->whereIn('jn.job_id', $getJobId)
                ->orderBy('jn.created_at', 'asc')->get();

            JobNote::where('is_seen',0)->whereIn('job_id', $getJobId)->update(['is_seen'=>1]);
        }else {
            $jobNotesList = DB::table('job_notes as jn')
            ->selectRaw('jn.id as notes_id, jn.job_note, jn.name, jn.created_at, j.job_id, j.job_title')
            ->join('jobs as j', 'j.job_id', 'jn.job_id')
            ->where('jn.is_deleted', 0)
            ->orderBy('jn.created_at', 'asc')->get();
            JobNote::where('is_seen',0)->update(['is_seen'=>1]);
        }
        if(Session::get('login_type_id') == 1  || Session::get('login_type_id') == 2 || (Session::get('login_type_id') == 9 && Session::has('job_notes_status') && Session::get('job_notes_status') == 1)){
            return view('admin.notes')->with('jobNotesList', $jobNotesList);
        }else {
            return redirect(route('dashboard'));
        }
    }
}
