<?php

namespace App\Http\Controllers\Api;

use App\Models\CompanyCategory;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CompanyCategoryController extends BaseController
{
    protected $category;

    public function __construct(CompanyCategory $category)
    {
        $this->category = $category;
    }
    public function index()
    {
        try {
            $categories = $this->category->paginate(10);
            return $this->sentPaginationResponse($categories,10,'Categories List');
        }catch(Exception $e){
           return $this->sentServerError('Error in category list',$e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $category = $this->category->with('companies')->findOrFail($id);
            return $this->sentResponse($category,'Category fetched successfully.');
         }catch(ModelNotFoundException $e){
            return $this->sentError('Category not found', 404);
         }catch(Exception $e){
           return $this->sentServerError('Error in category show',$e->getMessage());
        }
    }

    public function store(Request $request){
        try {
            $request->validate([
                'title' => 'required|string|max:255'
            ]);

            $category = $this->category->create($request->only('title'));

            return $this->sentResponse($category,'Category stored successfully.');
         }catch(Exception $e){
           return $this->sentServerError('Error in category store',$e->getMessage());

        }
    }

    public function update(Request $request, $id){
        try {

            $request->validate([
                'title' => 'required|string|max:255'
            ]);

            $category = $this->category->findOrFail($id);
            $category->update($request->only('title'));

            return $this->sentResponse($category,'Category updated successfully.');
        }catch(ModelNotFoundException $e){
            return $this->sentError('Category not found', 404);
         }catch(Exception $e){
           return $this->sentServerError('Error in category update',$e->getMessage());
        }
    }

    public function destroy($id){
        try{
            $category = $this->category->findOrFail($id);
            $category->delete();

            return $this->sentResponse(null,'Category deleted successfully.');
        }catch(ModelNotFoundException $e){
            return $this->sentError('Category not found', 404);
        }catch(Exception $e){
           return $this->sentServerError('Error in category deletion',$e->getMessage());
        }

    }

    public function search(Request $request){
        try{
            $keyword = $request->query("keyword");
            $categories = $this->category->when($keyword,function($query,$keyword){
                $query->where('title','like',"%{$keyword}%");
            })->paginate(10);

            return $this->sentPaginationResponse($categories,10,'Categories result');
        }catch(Exception $e){
           return $this->sentServerError('Error in category searching',$e->getMessage());
        }
    }
}
