<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin can see all service requests
            $serviceRequests = ServiceRequest::with(['buyer', 'agent'])
                ->latest()
                ->paginate(15);
        } elseif ($user->isAgent()) {
            // Agents can see service requests assigned to them
            $serviceRequests = ServiceRequest::with(['buyer'])
                ->where('agent_id', $user->id)
                ->latest()
                ->paginate(15);
        } else {
            // Buyers can see their own service requests
            $serviceRequests = ServiceRequest::with(['agent'])
                ->where('buyer_id', $user->id)
                ->latest()
                ->paginate(15);
        }

        return view('service-requests.index', compact('serviceRequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', ServiceRequest::class);

        // Get available agents for assignment
        $agents = User::where('role', 'agent')
            ->whereHas('agentProfile', function($query) {
                $query->where('verification_status', 'verified');
            })
            ->get();

        return view('service-requests.create', compact('agents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ServiceRequest::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category' => 'required|string|max:100',
            'budget' => 'required|numeric|min:0',
            'country_from' => 'required|string|max:100',
            'country_to' => 'required|string|max:100',
            'deadline' => 'required|date|after:today',
            'agent_id' => 'nullable|exists:users,id',
            'product_images' => 'nullable|array|max:5',
            'product_images.*' => 'image|mimes:jpeg,jpg,png|max:2048',
            'additional_documents' => 'nullable|array|max:3',
            'additional_documents.*' => 'file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Handle file uploads
        $productImagePaths = [];
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $path = $image->store('service_request_images', 'public');
                $productImagePaths[] = $path;
            }
        }

        $documentPaths = [];
        if ($request->hasFile('additional_documents')) {
            foreach ($request->file('additional_documents') as $document) {
                $path = $document->store('service_request_documents', 'public');
                $documentPaths[] = $path;
            }
        }

        // Create service request
        $serviceRequest = ServiceRequest::create([
            'buyer_id' => Auth::id(),
            'agent_id' => $validated['agent_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'budget' => $validated['budget'],
            'country_from' => $validated['country_from'],
            'country_to' => $validated['country_to'],
            'status' => 'order', // Initial status
            'deadline' => $validated['deadline'],
            'product_images' => json_encode($productImagePaths),
            'additional_documents' => json_encode($documentPaths),
        ]);

        return redirect()->route('service-requests.show', $serviceRequest)
            ->with('success', 'Your inspection request has been submitted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $serviceRequest = ServiceRequest::with(['buyer', 'agent', 'transactions', 'reviews'])
            ->findOrFail($id);

        $this->authorize('view', $serviceRequest);

        return view('service-requests.show', compact('serviceRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $this->authorize('update', $serviceRequest);

        // Only allow editing if status is still "order"
        if ($serviceRequest->status !== 'order') {
            return redirect()->route('service-requests.show', $serviceRequest)
                ->with('error', 'This service request can no longer be edited as it is already in progress.');
        }

        // Get available agents for assignment
        $agents = User::where('role', 'agent')
            ->whereHas('agentProfile', function($query) {
                $query->where('verification_status', 'verified');
            })
            ->get();

        return view('service-requests.edit', compact('serviceRequest', 'agents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $this->authorize('update', $serviceRequest);

        // Only allow updating if status is still "order"
        if ($serviceRequest->status !== 'order') {
            return redirect()->route('service-requests.show', $serviceRequest)
                ->with('error', 'This service request can no longer be updated as it is already in progress.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category' => 'required|string|max:100',
            'budget' => 'required|numeric|min:0',
            'country_from' => 'required|string|max:100',
            'country_to' => 'required|string|max:100',
            'deadline' => 'required|date|after:today',
            'agent_id' => 'nullable|exists:users,id',
            'product_images' => 'nullable|array|max:5',
            'product_images.*' => 'image|mimes:jpeg,jpg,png|max:2048',
            'additional_documents' => 'nullable|array|max:3',
            'additional_documents.*' => 'file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Handle file uploads
        $productImagePaths = json_decode($serviceRequest->product_images, true) ?? [];
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $path = $image->store('service_request_images', 'public');
                $productImagePaths[] = $path;
            }
        }

        $documentPaths = json_decode($serviceRequest->additional_documents, true) ?? [];
        if ($request->hasFile('additional_documents')) {
            foreach ($request->file('additional_documents') as $document) {
                $path = $document->store('service_request_documents', 'public');
                $documentPaths[] = $path;
            }
        }

        // Update service request
        $serviceRequest->update([
            'agent_id' => $validated['agent_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'budget' => $validated['budget'],
            'country_from' => $validated['country_from'],
            'country_to' => $validated['country_to'],
            'deadline' => $validated['deadline'],
            'product_images' => json_encode($productImagePaths),
            'additional_documents' => json_encode($documentPaths),
        ]);

        return redirect()->route('service-requests.show', $serviceRequest)
            ->with('success', 'Service request updated successfully.');
    }

    /**
     * Update the status of the service request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $this->authorize('updateStatus', $serviceRequest);

        $validated = $request->validate([
            'status' => 'required|in:order,inspection,shipping,delivery,completed,cancelled',
            'inspection_report' => 'nullable|required_if:status,inspection|string|min:10',
            'inspection_photos' => 'nullable|array|max:10',
            'inspection_photos.*' => 'image|mimes:jpeg,jpg,png|max:2048',
            'tracking_number' => 'nullable|required_if:status,shipping|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Handle inspection photos upload
        $inspectionPhotoPaths = json_decode($serviceRequest->inspection_photos, true) ?? [];
        if ($request->hasFile('inspection_photos')) {
            foreach ($request->file('inspection_photos') as $photo) {
                $path = $photo->store('inspection_photos', 'public');
                $inspectionPhotoPaths[] = $path;
            }
        }

        // Update service request status
        $serviceRequest->update([
            'status' => $validated['status'],
            'inspection_report' => $validated['inspection_report'] ?? $serviceRequest->inspection_report,
            'inspection_photos' => json_encode($inspectionPhotoPaths),
            'tracking_number' => $validated['tracking_number'] ?? $serviceRequest->tracking_number,
            'notes' => $validated['notes'] ?? $serviceRequest->notes,
        ]);

        // If status is completed and there's no review yet, create a placeholder for review
        if ($validated['status'] === 'completed' && !$serviceRequest->reviews()->exists()) {
            // Notification or logic to prompt buyer to leave a review can be added here
        }

        return redirect()->route('service-requests.show', $serviceRequest)
            ->with('success', "Service request status updated to {$validated['status']}.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $this->authorize('delete', $serviceRequest);

        // Only allow deletion if status is still "order"
        if ($serviceRequest->status !== 'order' && !Auth::user()->isAdmin()) {
            return redirect()->route('service-requests.show', $serviceRequest)
                ->with('error', 'This service request can no longer be deleted as it is already in progress.');
        }

        // Delete associated files
        if ($serviceRequest->product_images) {
            $productImages = json_decode($serviceRequest->product_images, true) ?? [];
            foreach ($productImages as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        if ($serviceRequest->additional_documents) {
            $documents = json_decode($serviceRequest->additional_documents, true) ?? [];
            foreach ($documents as $document) {
                Storage::disk('public')->delete($document);
            }
        }

        if ($serviceRequest->inspection_photos) {
            $inspectionPhotos = json_decode($serviceRequest->inspection_photos, true) ?? [];
            foreach ($inspectionPhotos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $serviceRequest->delete();

        return redirect()->route('service-requests.index')
            ->with('success', 'Service request deleted successfully.');
    }

    /**
     * Display service request dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin dashboard with statistics
            $stats = [
                'total' => ServiceRequest::count(),
                'order' => ServiceRequest::where('status', 'order')->count(),
                'inspection' => ServiceRequest::where('status', 'inspection')->count(),
                'shipping' => ServiceRequest::where('status', 'shipping')->count(),
                'delivery' => ServiceRequest::where('status', 'delivery')->count(),
                'completed' => ServiceRequest::where('status', 'completed')->count(),
                'cancelled' => ServiceRequest::where('status', 'cancelled')->count(),
            ];

            $recentRequests = ServiceRequest::with(['buyer', 'agent'])
                ->latest()
                ->take(10)
                ->get();

            return view('service-requests.admin-dashboard', compact('stats', 'recentRequests'));
        } elseif ($user->isAgent()) {
            // Agent dashboard
            $stats = [
                'total' => ServiceRequest::where('agent_id', $user->id)->count(),
                'order' => ServiceRequest::where('agent_id', $user->id)->where('status', 'order')->count(),
                'inspection' => ServiceRequest::where('agent_id', $user->id)->where('status', 'inspection')->count(),
                'shipping' => ServiceRequest::where('agent_id', $user->id)->where('status', 'shipping')->count(),
                'delivery' => ServiceRequest::where('agent_id', $user->id)->where('status', 'delivery')->count(),
                'completed' => ServiceRequest::where('agent_id', $user->id)->where('status', 'completed')->count(),
                'cancelled' => ServiceRequest::where('agent_id', $user->id)->where('status', 'cancelled')->count(),
            ];

            $recentRequests = ServiceRequest::with(['buyer'])
                ->where('agent_id', $user->id)
                ->latest()
                ->take(10)
                ->get();

            return view('service-requests.agent-dashboard', compact('stats', 'recentRequests'));
        } else {
            // Buyer dashboard
            $stats = [
                'total' => ServiceRequest::where('buyer_id', $user->id)->count(),
                'order' => ServiceRequest::where('buyer_id', $user->id)->where('status', 'order')->count(),
                'inspection' => ServiceRequest::where('buyer_id', $user->id)->where('status', 'inspection')->count(),
                'shipping' => ServiceRequest::where('buyer_id', $user->id)->where('status', 'shipping')->count(),
                'delivery' => ServiceRequest::where('buyer_id', $user->id)->where('status', 'delivery')->count(),
                'completed' => ServiceRequest::where('buyer_id', $user->id)->where('status', 'completed')->count(),
                'cancelled' => ServiceRequest::where('buyer_id', $user->id)->where('status', 'cancelled')->count(),
            ];

            $recentRequests = ServiceRequest::with(['agent'])
                ->where('buyer_id', $user->id)
                ->latest()
                ->take(10)
                ->get();

            return view('service-requests.buyer-dashboard', compact('stats', 'recentRequests'));
        }
    }

    /**
     * Download service request file.
     *
     * @param  int  $id
     * @param  string  $fileType
     * @param  string  $filename
     * @return \Illuminate\Http\Response
     */
    public function downloadFile($id, $fileType, $filename)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $this->authorize('view', $serviceRequest);

        $filePath = null;
        $files = [];

        if ($fileType === 'product_image') {
            $files = json_decode($serviceRequest->product_images, true) ?? [];
        } elseif ($fileType === 'document') {
            $files = json_decode($serviceRequest->additional_documents, true) ?? [];
        } elseif ($fileType === 'inspection_photo') {
            $files = json_decode($serviceRequest->inspection_photos, true) ?? [];
        }

        // Find the requested file
        foreach ($files as $file) {
            if (basename($file) === $filename) {
                $filePath = $file;
                break;
            }
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($filePath);
    }
}