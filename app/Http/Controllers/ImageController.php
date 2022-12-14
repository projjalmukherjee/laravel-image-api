<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    //
    public function index() {

        $image = Image::all();

        $data['response']['data'] = [];
        $data['response']['msg'] = "Image List successfully fetch";
        return response()->json($data,200);     

    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:20048',
        ]);

        $data['response']['data'] = [];
        if($validator->fails()){

            $data['response']['msg'] = $validator->errors();
            return response()->json($data,404);     
        }

        $file = $request->file('image');
        
        $imageName = time().'.'.$file->getClientOriginalExtension();
        $imagePath = public_path(). '/images';

        $file->move($imagePath, $imageName);

        $img['title'] = $request->title;
        $img['image'] = $imageName;

        $image_obj = Image::create($img);

        $data['response']['data'] =  $image_obj;
        $data['response']['msg'] = 'Image upload successfully.';
        return response()->json($data,200);     
    
    }

    public function edit($id) {

        $data['response']['data'] = [];

       try {
             $image_obj = Image::findOrFail($id);

             $data['response']['data'] =  $image_obj;
             $data['response']['data']['full_path'] = env('APP_URL').'/images/'.$image_obj->image;
             $data['response']['msg'] = 'Record fetch successfully.';
             return response()->json($data,200);      
       }
       catch(Exception $e) {

         $data['response']['msg'] = 'Image Not found';
         return response()->json($data,404);     
       }
        

    }

    public function update(Request $request,$id) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:images,title,'.$id,
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:20048',
        ]);

        $data['response']['data'] = [];
        if($validator->fails()){

            $data['response']['msg'] = $validator->errors();
            return response()->json($data,404);     
        }

        try{
            $img_obj = Image::findOrfail($id);
            $old_img_name = $img_obj->image;

            if($request->file('image')) {

                $file = $request->file('image');
            
                $imageName = time().'.'.$file->getClientOriginalExtension();
                $imagePath = public_path(). '/images';
                $old_file_path = $imagePath.'/'.$old_img_name;

                unlink($old_file_path);

                $file->move($imagePath, $imageName);

                $img_obj->image = $imageName;

            }
            
            $img_obj->title = $request->title;
            $img_obj->save();

            $data['response']['data'] =  $img_obj;
            $data['response']['msg'] = 'Image update successfully.';
            return response()->json($data,200); 
        
       } catch(Exception $e) {

        $data['response']['msg'] = 'Image Not found';
        return response()->json($data,404); 
       }
        
    }

    public function destroy($id) {

        
        $data['response']['data'] = [];

       try {
             $image_obj = Image::findOrFail($id);
             $img_nm = $image_obj->image;
             
             $imagePath = public_path(). '/images';
             $img_path = $imagePath.'/'.$img_nm;

             unlink($img_path);

             $image_obj->delete();

             $data['response']['data'] =  '';
             $data['response']['msg'] = 'Record delete successfully.';
             return response()->json($data,200);      
       }
       catch(Exception $e) {

         $data['response']['msg'] = 'Image Not found';
         return response()->json($data,404);     
       }

    }
}
