<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index(Request $request)
    {
        $query = Major::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $majors = $query->latest()->paginate(10)->withQueryString();

        return view('admin.majors.index', compact('majors'));
    }

    public function create()
    {
        return view('admin.majors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:majors,code',
        ]);

        Major::create($validated);

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil dibuat.');
    }

    public function edit(Major $major)
    {
        return view('admin.majors.edit', compact('major'));
    }

    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:majors,code,' . $major->id,
        ]);

        $major->update($validated);

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Major $major)
    {
        $major->delete();

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}
