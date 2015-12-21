<?php

namespace App\Http\Controllers;

use App\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $products = Product::all();
      return view('admin.products', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newProduct() {
      return view('admin.new');
    }

    /**
     * Basically a create.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $file = Request::file('file');
        $extension = $file->getClientOriginalExtension();
        Storage::disk('local')->put($file->getFileName().'.'$extension, File::get($file));

        $entry = new \App\File();
        $entry->mime = $file->getClientMimeType();
        $entry->original_filename = $file->getClientOriginalName();
        $entry->filename = $file->getFileName().'.'.$extension;

        $entry->save();

        $product = new Product();
        $product->file_id=$entry->id;
        $product->name =Request::input('name');
        $product->description =Request::input('description');
        $product->price =Request::input('price');
        $product->imageurl =Request::input('imageurl');

        $product->save();

        return redirect('/admin/products');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return redirect('/admin/products');
    }
}
