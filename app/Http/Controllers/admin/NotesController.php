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
        $jobNotesList = DB::table('job_notes as jn')
            ->selectRaw('jn.id as notes_id, jn.job_note, jn.name, jn.created_at, j.job_id, j.job_title')
            ->join('jobs as j', 'j.job_id', 'jn.job_id')
            ->where('jn.is_deleted', 0)
            ->orderBy('jn.created_at', 'desc')->get();
        return view('admin.notes')->with('jobNotesList', $jobNotesList);
    }
}
