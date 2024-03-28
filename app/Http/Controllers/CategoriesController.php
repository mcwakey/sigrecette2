<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Models\Canton;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryDataTable $dataTable)
    {
        //return view('pages/categories.list');
        return $dataTable->render('pages/categories.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category ,CategoryDataTable $categoriesDataTable)
    {
        return $categoriesDataTable->with('id',$category ->id)->render('pages/categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category )
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category )
    {
        //
    }
}

