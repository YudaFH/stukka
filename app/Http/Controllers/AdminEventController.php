<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
// Panggil Library Intervention Image
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Event::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $status);
        }

        $events = $query->latest()->paginate(10)->withQueryString();

        return view('admin.events.index', compact('events', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'date'        => ['required', 'string', 'max:255'],
            'location'    => ['required', 'string', 'max:255'],
            'client_name' => ['required', 'string', 'max:255'],
            'status'      => ['required', Rule::in(['upcoming', 'ongoing', 'finished'])],

            'image' => ['required', 'image', 'max:5120'],
            'doc1'  => ['nullable', 'image', 'max:5120'],
            'doc2'  => ['nullable', 'image', 'max:5120'],
            'doc3'  => ['nullable', 'image', 'max:5120'],

            // checkbox featured (boleh ada / boleh tidak)
            'is_featured' => ['nullable', 'boolean'],
        ]);

        // Normalisasi checkbox: kalau gak dicentang, tetap jadi 0
        $validated['is_featured'] = $request->boolean('is_featured');

        // Set default price kalau memang selalu sama
        $validated['price'] = $validated['price'] ?? 'Hubungi Kami';

        // Upload files
        $validated['image'] = $this->compressAndUpload($request->file('image'), 'events');

        if ($request->hasFile('doc1')) $validated['doc1'] = $this->compressAndUpload($request->file('doc1'), 'events');
        if ($request->hasFile('doc2')) $validated['doc2'] = $this->compressAndUpload($request->file('doc2'), 'events');
        if ($request->hasFile('doc3')) $validated['doc3'] = $this->compressAndUpload($request->file('doc3'), 'events');

        Event::create($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Project berhasil disimpan & dikompres!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'date'        => ['required', 'string', 'max:255'],
            'location'    => ['required', 'string', 'max:255'],
            'client_name' => ['nullable', 'string', 'max:255'],

            'status'      => ['required', Rule::in(['upcoming', 'ongoing', 'finished'])],

            'image' => ['nullable', 'image', 'max:5120'],
            'doc1'  => ['nullable', 'image', 'max:5120'],
            'doc2'  => ['nullable', 'image', 'max:5120'],
            'doc3'  => ['nullable', 'image', 'max:5120'],

            'is_featured' => ['nullable', 'boolean'],
        ]);

        // Normalisasi featured agar bisa OFF saat checkbox tidak dicentang
        $validated['is_featured'] = $request->boolean('is_featured');

        // Kalau upload image baru → hapus lama → simpan baru
        if ($request->hasFile('image')) {
            $this->deleteOldImage($event->image);
            $validated['image'] = $this->compressAndUpload($request->file('image'), 'events');
        }

        if ($request->hasFile('doc1')) {
            $this->deleteOldImage($event->doc1);
            $validated['doc1'] = $this->compressAndUpload($request->file('doc1'), 'events');
        }
        if ($request->hasFile('doc2')) {
            $this->deleteOldImage($event->doc2);
            $validated['doc2'] = $this->compressAndUpload($request->file('doc2'), 'events');
        }
        if ($request->hasFile('doc3')) {
            $this->deleteOldImage($event->doc3);
            $validated['doc3'] = $this->compressAndUpload($request->file('doc3'), 'events');
        }

        $event->update($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Project berhasil diupdate!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Hapus semua file gambar dari server biar gak nyampah
        $this->deleteOldImage($event->image);
        $this->deleteOldImage($event->doc1);
        $this->deleteOldImage($event->doc2);
        $this->deleteOldImage($event->doc3);

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Project dihapus.');
    }

    // ==========================================
    // FUNGSI AJAIB: COMPRESS TO WEBP
    // ==========================================
    private function compressAndUpload($file)
    {
        // 1. Setup Manager (Driver GD)
        $manager = new ImageManager(new Driver());

        // 2. Baca Gambar
        $image = $manager->read($file);

        // 3. Resize (Scale)
        // Lebar dipaksa max 1000px, Tinggi menyesuaikan (biar file kecil)
        // Kalau gambar aslinya kecil, dia gak akan dibesarkan (upsize: false)
        $image->scale(width: 1000);

        // 4. Encode ke WebP dengan Kualitas 75%
        $encoded = $image->toWebp(quality: 75);

        // 5. Buat Nama Unik (.webp)
        $filename = time() . '_' . uniqid() . '.webp';

        // 6. Pastikan Folder 'events' Ada
        $path = storage_path('app/public/events');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // 7. Simpan File
        $encoded->save($path . '/' . $filename);

        // 8. Kembalikan Path Database
        return '/storage/events/' . $filename;
    }

    // Fungsi Hapus File Lama
    private function deleteOldImage($path)
    {
        if ($path) {
            // Ubah '/storage/events/abc.webp' jadi 'public/events/abc.webp' untuk dihapus
            $realPath = str_replace('/storage/', 'public/', $path);
            if (Storage::exists($realPath)) {
                Storage::delete($realPath);
            }
        }
    }
}
