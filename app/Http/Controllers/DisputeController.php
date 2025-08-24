<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Transaction;
use App\Models\DisputeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisputeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $disputes = Dispute::where('disputer_id', Auth::id())
            ->orWhere('disputed_user_id', Auth::id())
            ->with(['transaction', 'disputer', 'disputedUser'])
            ->latest()
            ->paginate(10);

        return view('disputes.index', compact('disputes'));
    }

    public function create(Transaction $transaction)
    {
        $this->authorize('createDispute', $transaction);

        return view('disputes.create', compact('transaction'));
    }

    public function store(Request $request, Transaction $transaction)
    {
        $this->authorize('createDispute', $transaction);

        $validated = $request->validate([
            'type' => 'required|in:payment,service,product,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'evidence_files' => 'nullable|array|max:5',
            'evidence_files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        $evidenceFiles = [];
        if ($request->hasFile('evidence_files')) {
            foreach ($request->file('evidence_files') as $file) {
                $path = $file->store('dispute_evidence', 'public');
                $evidenceFiles[] = $path;
            }
        }

        $dispute = Dispute::create([
            'transaction_id' => $transaction->id,
            'disputer_id' => Auth::id(),
            'disputed_user_id' => Auth::id() == $transaction->buyer_id ? $transaction->seller_id : $transaction->buyer_id,
            'type' => $validated['type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'evidence_files' => $evidenceFiles,
            'status' => 'open'
        ]);

        return redirect()->route('disputes.show', $dispute)
            ->with('success', 'Dispute has been filed successfully. Our team will review it shortly.');
    }

    public function show(Dispute $dispute)
    {
        $this->authorize('view', $dispute);

        $dispute->load(['messages.user', 'transaction', 'disputer', 'disputedUser']);

        return view('disputes.show', compact('dispute'));
    }

    public function addMessage(Request $request, Dispute $dispute)
    {
        $this->authorize('addMessage', $dispute);

        $validated = $request->validate([
            'message' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('dispute_attachments', 'public');
        }

        $dispute->messages()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'attachment' => $attachmentPath,
            'is_admin' => Auth::user()->isAdmin()
        ]);

        return redirect()->back()->with('success', 'Message added successfully.');
    }

    public function resolve(Request $request, Dispute $dispute)
    {
        $this->authorize('resolve', $dispute);

        $validated = $request->validate([
            'resolution' => 'required|string|min:20',
            'status' => 'required|in:resolved,rejected'
        ]);

        $dispute->update([
            'status' => $validated['status'],
            'resolution' => $validated['resolution'],
            'resolved_by' => Auth::id(),
            'resolved_at' => now()
        ]);

        return redirect()->route('admin.disputes')
            ->with('success', 'Dispute has been resolved.');
    }
}
