<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Traits\FileUpload;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CompanyController extends BaseController
{
    use FileUpload;

    protected $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function index()
    {
        try {
            $companies = $this->company->with('category')->paginate(10);
            return $this->sentPaginationResponse(CompanyResource::collection($companies),10,'Companies List');
        }catch(Exception $e){
           return $this->sentServerError('Error in company list',$e->getMessage());
        }
    }

    public function show($id){
        try {
            $company = $this->company->with('category')->findOrFail($id);
            return $this->sentResponse(new CompanyResource($company),'Company fetched successfully.');
        }catch(ModelNotFoundException $e){
            return $this->sentError('Company not found', 404);
        }catch(Exception $e){
           return $this->sentServerError('Error in company show',$e->getMessage());
        }
    }

    public function store(CompanyRequest $request){
        try {
            $data = $request->validated();

            if($request->hasFile('image')){
                $data['image'] = $request->file('image')->store('companies','public');
            }

            $company = $this->company->create($data);

            return $this->sentResponse(new CompanyResource($company),'Company stored successfully.');
        }catch(Exception $e){
           return $this->sentServerError('Error in company store',$e->getMessage());

        }
    }

    public function update(CompanyRequest $request, $id){
        try {
            $data = $request->validated();

            $company = $this->company->findOrFail($id);

            if($request->hasFile('image')){
               $this->handleImageDelete($company->image);
                $data['image'] = $request->file('image')->store('companies','public');
            }

            $company->update($data);

            return $this->sentResponse(new CompanyResource($company),'Company updated successfully.');
        }catch(ModelNotFoundException $e){
            return $this->sentError('Company not found', 404);
        }catch(Exception $e){
           return $this->sentServerError('Error in company update',$e->getMessage());
        }
    }

    public function destroy($id){
        try{
            $company = $this->company->findOrFail($id);
            $this->handleImageDelete($company->image);
            $company->delete();
            return $this->sentResponse(null,'Company Deleted successfully.');

        }catch(ModelNotFoundException $e){
            return $this->sentError('Company not found', 404);
        }catch(Exception $e){
           return $this->sentServerError('Error in company deletion',$e->getMessage());
        }

    }

}
