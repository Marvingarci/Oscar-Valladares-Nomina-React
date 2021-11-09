<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vitola;
use App\Models\Category;
use Inertia\Inertia;

use Illuminate\Support\Facades\Request;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Products/ProductIndex', [
            'filters' => Request::all('search', 'trashed'),
            'products' => Product::filter(Request::only('search', 'trashed'))
                ->with(['vitola', 'category'])
                ->paginate(5)
                ->appends(Request::all()),
            'vitolas' => Vitola::all('id', 'name'),
            'categories' => Category::orderBy('id','asc')->with('vitolas')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create($request->validated());
        return back()->with(['message' => 'Producto Guardado con Éxito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $vitola = Product::find($request->id);
        $vitola->update($request->validated());
        return back()->with(['message' => 'Producto Editado con Éxito']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
