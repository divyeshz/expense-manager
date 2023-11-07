<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Return Category View Page
    public function category()
    {
        return view('pages.category');
    }

    // Return Category List to Ajax Call
    public function categoryList(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::all();
            return Datatables::of($data)
                ->addColumn('#', function() {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<button type="submit" data-toggle="modal" data-target="#editCategoryModal" class="btn btn-primary" onclick="viewCategory('.$row->id.')">Edit</button>
                    <button type="submit" class="btn btn-danger "onclick="deleteCategory('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // Save New Category Data Into Database And Return Response to Ajax Call
    public function categorySave(Request $request)
    {
        $request->validate([
            'name'              => 'required|string',
        ]);

        // store the data
        $add = Category::create([
            'name' => $request->name,
        ]);

        if ($add) {
            $response = [
                'status'    => 'success',
                'message'   => 'Category Created SuccessFully!!!'
            ];
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Category Created failed!!!'
            ];
        }
        return $response;
    }

    // Return Specific Category Data For Edit
    public function categoryView(Request $request)
    {
        return Category::findOrFail($request->id);
    }

    // Edit Category Data And Update Database Record And Return Response to Ajax Call
    public function categoryEdit(Request $request)
    {

        $request->validate([
            'name'              => 'required|string',
            'edit_category_id'  => 'required|integer'
        ]);

        $update = [
            'name' => $request->name,
        ];

        $edit = Category::where('id', $request->edit_category_id)->update($update);

        if($edit){
            $response = [
                'status'=>'success',
                'message'=> 'Category updated SuccessFully!!!'
            ];
        }else{
            $response = [
                'status'=>'error',
                'message'=>' Category updated failed!!!'
            ];
        }
        return $response;
    }

    // Delete Category And Return Response to Ajax Call
    public function categoryDelete(Request $request)
    {
        $delete = Category::findOrFail($request->id)->delete();

        if ($delete) {
            $response = [
                'status'    => '200',
                'message'   => 'Category Deleted SuccessFully!!!'
            ];
        } else {
            $response = [
                'status'    => '400',
                'message'   => 'Category Deleted Failed!!!'
            ];
        }
        return $response;
    }
}
