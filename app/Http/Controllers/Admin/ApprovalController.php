<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Jobs\PushAttendanceToPostgres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\OfficeLocation;
use App\Services\ZktecoApiService;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\ApprovedTransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;

class ApprovalController extends Controller
{
    protected $zktecoApiService;

    public function __construct(ZktecoApiService $zktecoApiService)
    {
        $this->zktecoApiService = $zktecoApiService;
    }

    public function index(Request $request)
    {
        $query = Attendance::with('user', 'officeLocation')
                            ->where('status', 'pending')
                            ->orderBy('created_at', 'desc');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        $pendingAttendances = $query->paginate(25)->withQueryString();
        
        return view('admin.approvals.index', [
            'pendingAttendances' => $pendingAttendances,
            'search' => $request->search ?? ''
        ]);
    }
    
    public function historyWaiting(Request $request)
    {
        $query = Attendance::with('user', 'officeLocation')
                            ->where('status', 'waiting')
                            ->orderBy('updated_at', 'desc');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        $waitingAttendances = $query->paginate(25)->withQueryString();
        return view('admin.approvals.history_waiting', [
            'waitingAttendances' => $waitingAttendances,
            'search' => $request->search ?? ''
        ]);
    }

    public function historyRejected(Request $request)
    {
        $query = Attendance::with('user', 'officeLocation')
                            ->where('status', 'rejected')
                            ->orderBy('updated_at', 'desc');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        $rejectedAttendances = $query->paginate(25)->withQueryString();
        return view('admin.approvals.history_rejected', [
            'rejectedAttendances' => $rejectedAttendances,
            'search' => $request->search ?? ''
        ]);
    }

    public function historyApproved(Request $request)
    {
        if ($request->has('export_success')) {
            session()->flash('success', 'File laporan berhasil dibuat dan sedang diunduh.');
        }

        try {
            $apiData = $this->zktecoApiService->getApprovedTransactions($request);

            //dd($apiData); 
            $transactionsData = $apiData['data'] ?? $apiData['results'] ?? [];
            $total = $apiData['count'] ?? 0;
            $perPage = 15;
            $currentPage = $request->input('page', 1);

            $transactions = new LengthAwarePaginator($transactionsData, $total, $perPage, $currentPage, ['path' => $request->url(), 'query' => $request->query()]);

            list($terminals, $departments) = $this->getFilterData();
            
            return view('admin.approvals.history_approved', [
                'transactions' => $transactions,
                'search' => $request->input('search', ''),
                'terminals' => $terminals,
                'departments' => $departments,
                'request' => $request
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Gagal mengambil data dari ZKTeco API: ' . $e->getMessage()]);
        }
    }
    
    private function getFilterData(): array
    {
        $cacheDuration = now()->addHours(6); 

        $terminals = Cache::remember('all_terminals_list', $cacheDuration, function () {
            return $this->zktecoApiService->getAllTerminals();
        });

        $departments = Cache::remember('all_departments_list', $cacheDuration, function () {
            return $this->zktecoApiService->getAllDepartments();
        });
        
        return [$terminals, $departments];
    }

    public function exportApproved(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'format' => 'required|in:excel,pdf',
            'columns' => 'required|array|min:1',
            'department' => 'nullable|string', // <-- TAMBAHKAN BARIS INI

        ]);

        try {
            $apiRequestData = $request->all();
            $apiRequestData['page_size'] = 36000; // Ambil semua data untuk ekspor
            $apiRequest = new Request($apiRequestData);
            
            $apiData = $this->zktecoApiService->getApprovedTransactions($apiRequest);
            $transactionsData = $apiData['data'] ?? $apiData['results'] ?? [];

            $format = $request->input('format');
            $selectedColumns = $request->input('columns');
            $fileName = 'export-' . now()->format('Y-m-d-His') . ($format == 'excel' ? '.xlsx' : '.pdf');

            $writerType = ($format == 'pdf') ? \Maatwebsite\Excel\Excel::DOMPDF : \Maatwebsite\Excel\Excel::XLSX;
            Excel::store(new ApprovedTransactionsExport($transactionsData, $selectedColumns), 'exports/' . $fileName, 'public', $writerType);

            return response()->json([
                'success' => true,
                'download_url' => route('admin.approvals.download_export', ['filename' => $fileName])
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membuat file ekspor: ' . $e->getMessage()], 500);
        }
    }

    public function downloadExportedFile($filename)
    {
        $path = storage_path('app/public/exports/' . $filename);
        if (!File::exists($path)) {
            abort(404);
        }
        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function approve(Attendance $attendance)
    {
        $attendance->update(['status' => 'waiting']);
        PushAttendanceToPostgres::dispatch($attendance);
        return redirect()->back()->with('success', 'Absensi telah ditambahkan ke antrean sinkronisasi.');
    }

    public function reject(Attendance $attendance)
    {
        $attendance->update(['status' => 'rejected']);
        return redirect()->route('admin.approvals.history_rejected')->with('success', 'Absensi berhasil ditolak.');
    }
    
    public function edit(Attendance $attendance)
    {
        $locations = OfficeLocation::all();
        return view('admin.approvals.edit', compact('attendance', 'locations'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'office_location_id' => 'required|exists:office_locations,id',
            'type' => 'required|in:kantor,dinas',
            'status' => 'required|in:pending,approved,rejected,waiting',
        ]);
        $attendance->update($request->all());
        return redirect()->route('admin.approvals.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroy(Attendance $attendance)
    {
        if (Storage::disk('public')->exists($attendance->photo_path)) {
            Storage::disk('public')->delete($attendance->photo_path);
        }
        $attendance->delete();
        return redirect()->back()->with('success', 'Data absensi berhasil dihapus.');
    }
}

