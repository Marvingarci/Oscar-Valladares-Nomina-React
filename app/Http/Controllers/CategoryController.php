<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Models\Vitola;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Categories/CategoriesIndex', [
            'filters' => Request::all('search', 'trashed'),
            'categories' => Category::filter(Request::only('search', 'trashed'))
            ->paginate(5)
            ->appends(Request::all()),
        ]);

    }

    public function create()
    {
        return Inertia::render('Categories/CreateCategory', [
            'category' => [],
            'vitolas' => Vitola::all('id', 'name'),
        ]);
    }

    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create($request->validated());
        $vitolasId = collect($request->vitolas)->map(function ($item, $key) {
            return $item['id'];
        });
        $category->vitolas()->sync($vitolasId->all());
        return back()->with(['message' => 'Categoría Agregada con Éxito']);
    }

    public function show($id)
    {
        $category = Category::find($id);
        $category->vitolas;

        return Inertia::render('Categories/ShowCategory', [
            'category' => $category,
        ]);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $category->vitolas;

        return Inertia::render('Categories/CreateCategory', [
            'category' => $category,
            'vitolas' => Vitola::all('id', 'name'),
        ]);
    }

    public function update(CategoryUpdateRequest $request)
    {

        $category = Category::find($request->id);
        $category->update($request->validated());

        $vitolasId = collect($request->vitolas)->map(function ($item, $key) {
            return $item['id'];
        });
        $category->vitolas()->sync($vitolasId->all()); // para mandar las vitolas y borrar las que no pertenecen a ese arreglo

        return back()->with(['message' => 'Categoría Editada con Éxito']);
    }

    public function destroy(Category $category)
    {
        //
    }
}
