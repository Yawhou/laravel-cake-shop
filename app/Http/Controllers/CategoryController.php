<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        // Retrieve paginated categories
        $data['categories']=Category::select('id','cat_name', 'slug','status','description','image_cat')->paginate(10);
        // Return view with categories data
        return view('category.categoryindexuser', $data);// category/categoryindex works too
    }

    /**
     * Show the form for creating a new category.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        // Retrieve categories for select input
        $categories = Category::select('id','cat_name')->get();
        // Return view with categories data
        return view('category.categorycreate', compact('categories'));

    }

    /**
     * Store a newly created category in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate request data
        $this->validate($request, [
            'cat_name' => 'required|unique:categories,cat_name',
            'status' => 'required'
        ]);
        try {
             // Handle file upload
            $filename='';
            if($request->file('image_cat'))
            {
                $catename= Str::of($request->input('cat_name'))->trim();
                $file= $request->file('image_cat');
                $filename= $catename.time().'.'.$file->getClientOriginalExtension();
                $file-> move(public_path('/uploads/categories'), $filename);
            }
        // Create category
        Category::create([
            'cat_name' => trim($request->input('cat_name')),
            'slug' => Str::slug(trim($request->input('cat_name'))),
            'description' => trim($request->input('description')),
            'image_cat' =>$filename,
            'category_id'=>$request->input('category_id'),
            'status' => $request->input('status')

        ]);
            // Flash success message and redirect back
            session()->flash('message','Submitted!');
            session()->flash('type','success');
            return redirect()->back();
        }
        catch(Exception $e){
            // Flash error message and redirect back in case of exception
            session()->flash('message',$e->getMessage());
            session()->flash('type','danger');

            return redirect()->back();
        }

    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        try {
            // Retrieve category by ID with paginated products
            $data['category'] = Category::findOrFail($id)->setRelation('products', Category::findOrFail($id)->products()->paginate(10));

            // Return view with category data
            return view('category.categoryshowuser', $data);
        }
        catch(Exception $e){

        // Flash error message and redirect back in case of exception
        session()->flash('message',$e->getMessage());
        session()->flash('type','danger');
        $categories=Category::paginate(10);

        return view('category.categoryindexuser', compact('categories'));
    }
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        // Retrieve category and categories for select input
        $category=Category::find($id);
        $categories_name = Category::select('id','cat_name')->get();
        // Return view with category data
        return view('category.categoryedit', compact(['category', 'categories_name']));


    }

    /**
     * Update the specified category in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            // Retrieve category by ID
            $data = Category::findOrFail($id);
        }
        catch (Exception $e){
        // Flash error message and redirect back in case of exception
            session()->flash('message',$e->getMessage());
            session()->flash('type','danger');

            return redirect()->back();
        }

        // Validate request data
        $this->validate($request, [
            'cat_name' => 'required|unique:categories,cat_name,'.$id,
            'status' => 'required'
        ]);

        try {
            // Handle file upload if exists
            if($request->file('image_cat'))
            {
                if( File::exists(public_path('/uploads/categories/'.$data->image_cat)) ) {
                    File::delete(public_path('/uploads/categories/'.$data->image_cat));
                }

                $catename= Str::of($request->input('cat_name'))->trim();
                $file= $request->file('image_cat');
                $filename= $catename.time().'.'.$file->getClientOriginalExtension();
                $file-> move(public_path('/uploads/categories'), $filename);

                // Update category with new data and filename
                $data->update([
                    'cat_name' => trim($request->input('cat_name')),
                    'slug' => Str::slug(trim($request->input('cat_name'))),
                    'description' => trim($request->input('description')),
                    'image_cat' =>$filename,
                    'category_id'=>$request->input('category_id'),
                    'status' => $request->input('status')
                ]);

                // Flash success message and redirect back
                session()->flash('message','Submitted!');
                session()->flash('type','success');
                return redirect()->back();

            }
            else {
                // Update category without changing image
                $data->update([
                    'cat_name' => trim($request->input('cat_name')),
                    'slug' => Str::slug(trim($request->input('cat_name'))),
                    'description' => trim($request->input('description')),
                    'category_id' => $request->input('category_id'),
                    'status' => $request->input('status')
                ]);
                // Flash success message and redirect back
                session()->flash('message', 'Submitted!');
                session()->flash('type', 'success');
                return redirect()->back();
            }
        }
        catch(Exception $e){

            // Flash error message and redirect back in case of exception
            session()->flash('message',$e->getMessage());
            session()->flash('type','danger');

            return redirect()->back();
        }
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            // Retrieve and delete category by ID
            $data = Category::find($id);
            $data->delete();

            // Flash success message and redirect to categories index
            session()->flash('message', 'Deleted!');
            session()->flash('type', 'success');
            return redirect()->route("categories.index");
        }catch(Exception $e){
            // Flash error message and redirect back in case of exception
            session()->flash('message',$e->getMessage());
            session()->flash('type','danger');

            return redirect()->back();
        }

    }
}
