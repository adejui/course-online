<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderByDesc('id')->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        DB::transaction(function () use ($request) {

            $validated = $request->validated();

            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('icons', 'public');
                $validated['icon'] = $iconPath;
            }

            $validated['slug'] = Str::slug($validated['name']);

            $category = Category::create($validated);
        });

        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // dd($category);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        DB::transaction(function () use ($request, $category) {

            $validated = $request->validated();

            if ($request->hasFile('icon')) {
                // Cek apakah file baru berbeda dari yang lama
                $newFile = $request->file('icon');
                $newHash = md5_file($newFile->getRealPath());

                // Cek hash file lama jika ada
                $oldFilePath = storage_path('app/public/' . $category->icon);
                $oldHash = file_exists($oldFilePath) ? md5_file($oldFilePath) : null;

                // Jika berbeda, hapus file lama
                if ($oldHash !== $newHash && $category->icon && Storage::disk('public')->exists($category->icon)) {
                    Storage::disk('public')->delete($category->icon);
                }

                // Simpan file baru
                $iconPath = $newFile->store('icons', 'public');
                $validated['icon'] = $iconPath;
            }
            // if ($request->hasFile('icon')) {
            //     $iconPath = $request->file('icon')->store('icons', 'public');
            //     $validated['icon'] = $iconPath;
            // }

            $validated['slug'] = Str::slug($validated['name']);

            $category->update($validated);
        });

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();

        try {
            $category->delete();
            DB::commit();

            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.categories.index')->with('error', 'Terjadi error!');
        }
    }
}
