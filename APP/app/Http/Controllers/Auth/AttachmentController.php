<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Authentication\AttachmentResource;
use App\Models\Auth\Attachments;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function __construct()
    {
         $this->middleware('permission:view')->only(['index']);
    }

    protected function index(Request $request)
    {
        $query = Attachments::select('id', 'parent_id', 'file_name', 'file_size', 'path_name', 'form_code')
            ->where('parent_id', $request->id)
            ->where('form_code', $request->form_code)
            ->orderBy('id', 'asc');
        $perPage = $request->input('per_page') ?? self::PER_PAGE;
        $records = $query->paginate((int) $perPage);
        // return $records;
        return AttachmentResource::collection($records);
    }

    protected function download($id = 0)
    {
        $attachment = Attachments::select(
            'id',
            'form_code',
            'path_name',
            'file_name',
        )
            ->where('id', $id)
            ->first();
        return response()->file(public_path('/' . 'storage/' . $attachment->path_name));
    }
}
