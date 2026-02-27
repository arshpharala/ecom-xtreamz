<?php

namespace App\Http\Controllers\Admin\CMS;

/* use App\Models\ContactSubmission; (Already exists in App\Models) */
use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()->of(ContactSubmission::query())
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('admin.cms.contact-submissions.show', $row->id).'" class="btn btn-primary btn-sm mx-1 shadow-sm"><i class="fas fa-eye"></i></a>';
                    $btn .= '<button type="button" class="btn btn-danger btn-sm btn-delete ml-1 shadow-sm" data-url="'.route('admin.cms.contact-submissions.destroy', $row->id).'"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })
                ->addColumn('subject_styled', function ($row) {
                    $color = 'secondary';
                    $subject = strtolower($row->subject);
                    if (str_contains($subject, 'sales')) {
                        $color = 'success';
                    } elseif (str_contains($subject, 'support')) {
                        $color = 'info';
                    } elseif (str_contains($subject, 'urgent')) {
                        $color = 'danger';
                    }

                    return '<span class="badge badge-'.$color.'">'.$row->subject.'</span>';
                })
                ->addColumn('status', function ($row) {
                    if ($row->notified_at) {
                        return '<span class="text-success small" title="Notified at '.$row->notified_at.'"><i class="fas fa-check-double mr-1"></i> Notified</span>';
                    }

                    return '<span class="text-muted small"><i class="fas fa-clock mr-1"></i> Pending</span>';
                })
                ->addColumn('date', function ($row) {
                    return '<div class="small">'.$row->created_at->format('M d, Y').'</div><div class="text-muted extra-small">'.$row->created_at->format('H:i').'</div>';
                })
                ->rawColumns(['action', 'subject_styled', 'status', 'date'])
                ->make(true);
        }

        return view('theme.adminlte.cms.contact-submissions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $submission = ContactSubmission::findOrFail($id);

        return view('theme.adminlte.cms.contact-submissions.show', compact('submission'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $submission = ContactSubmission::findOrFail($id);
        $submission->delete();

        return response()->json([
            'message' => 'Submission deleted successfully.',
            'redirect' => route('admin.cms.contact-submissions.index'),
        ]);
    }
}
