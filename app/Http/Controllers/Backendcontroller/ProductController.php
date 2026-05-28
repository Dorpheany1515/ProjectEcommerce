<?php

namespace App\Http\Controllers\BackendController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProduct(){
        $category=Category::query()->get();
        return view('backend.addProduct',compact('category'));
    }
    public function listProduct(){
        $products=Product::query()
                    ->join('category','product.cate_id','=','category.id')
                    ->join('users','product.user_id','=','users.id')
                    ->select('product.*','category.category_name as category','users.profile as profile')
                    ->paginate(7);
        return view('backend.listProduct',compact('products'));
    }
    public function addProductSubmit(Request $request){
        $input=$request->all();
        $input=$request->validate([
            'product_name' => 'required',
            'qty'=> 'required',
            'regular_price' => 'required',
            'sale_price' => 'required|numeric|min:0|max:99999999.99',
            'cate_id'=> 'required',
            'description'=> 'required',

        ]);

        if(($request->hasFile('image'))){
            $file=$request->file('image');
            $filename=date('ymdhis').'_'.$file->getClientOriginalName();
            $file->move('images',$filename);
            $input['image']=url('images/'.$filename);

        }else{
            $input['image']=$request->old_image;
        }

        $input['user_id']=auth()->user()->id;
        $btn=$request->btn;
        if($btn=='Add Product'){
            $insert =Product::create($input);
            return redirect('/admin/add-product')->with('success','Product Added Successfully');
        }else{

            $id=$request->id;
            $update=Product::query()->where('id',$id)->update($input);
            return redirect('/admin/list-product')->with('success','Product Updated Successfully');
        }


    }
    public function deleteProduct(Request $request){
        $id=$request->remove_id;
        $delete=Product::query()->where('id',$id)->delete();
        if($delete){
            return redirect('/admin/list-product')->with('success','Product Deleted Successfully');
        }
        return redirect('/admin/list-product')->with('error','Something went wrong');
    }
    public function editProduct(Product $product){

        $getCate=Product::query()
                ->join('category','product.cate_id','=','category.id')
                ->select('product.*','category.category_name as category')
                ->where('product.id',$product->id)
                ->first();
        $category=Category::query()->get();
        return view('backend.addProduct',compact('getCate','category'));
    }

    }