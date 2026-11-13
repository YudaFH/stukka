<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminTestimonialController extends Controller
{
    public function index() {
        $testimonials = \App\Models\Testimonial::latest()->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'content' => 'required|string',
            'stars' => 'required|integer|min:1|max:5',
        ]);
        \App\Models\Testimonial::create($data);
        return back()->with('success', 'Testimoni berhasil ditambahkan!');
    }

    public function destroy($id) {
        \App\Models\Testimonial::findOrFail($id)->delete();
        return back()->with('success', 'Testimoni dihapus.');
    }
}
