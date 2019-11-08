<?php

namespace App\Http\Controllers\API;

use Cloudder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{

  // Indicate the table before use
   public function imageUpload($request, $onCurrent = null) {

       if($request->hasFile('image') && $request->file('image')->isValid()) {
           
            // Check old image  and delete it if true
            $extension = strtolower($request->file('image')->extension());
            
            // Set the allowed extentions
            $allowed_ext = ['png', 'jpg', 'jpeg'];

            // Get the Image Size
            $file_size = filesize($request->file('user_image'));

            if (in_array($extension, $allowed_ext)) {
                
                if ($file_size > 5000000) {
                    return [
                        'status' => 0,
                        'code' => 413,
                        'message' => 'Resource too large',
                        'hint' => 'File size exceeded 5mb. Reduce your file'
                    ];
                }

                // Return the image data from the database after upload
                return $this->saveImages($request, $onCurrent);
            }
            else {
                return [
                    'status' => 0,
                    'code' => 415,
                    'message' => 'Unsupported media type',
                    'hint' => 'Supported media types are png, jpg and jpeg'
                ];
            }
       }else {
            return [
                'status' => 0,
                'code' => 400,
                'message' => 'Image is invalid',
            ];
       }
   }

    public function saveImages(Request $request, $onCurrent)
    {

        DB::beginTransaction();

        try{
            // Error hadling to control file size
            if($onCurrent != null) {
                if($onCurrent->image != 'noimage.jpg') {
                    $oldImage = pathinfo($onCurrent->image, PATHINFO_FILENAME);
                    
                    try {
                        $del_img = Cloudder::destroyImage($oldImage);
                    }
                    catch(Exception $e) {
                        return [
                            'status' => 0,
                            'code' => 501,
                            'message' => 'An error occured while deleting old image: please try again!',
                            'hint' => $e->getMessage()
                        ];
                    }
                }
            }

            $image        = $request->file('image')->getRealPath();
            
            $cloudder     = Cloudder::upload($image, null);

            list($width, $height) = getimagesize($image);
            
            $uploadResult = $cloudder->getResult();

            $image_url = $image_url = Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height"=>$height]);

            DB::commit();

            return [
                'status' => 1,
                'code' => 200,
                'message' => 'The image has been uploaded successfully!',
                'image_link' => $image_url,
                'image_round_format' => 'w_200,c_fill,ar_1:1,g_auto,r_max/',
                'image_square_format' => 'w_200,ar_1:1,c_fill,g_auto/',
                'image'  => $uploadResult["public_id"] . $uploadResult["format"]
            ];
        }
        catch(Exception $e) {
            DB::rollBack();

            return [
                'status' => 0,
                'code' => 501,
                'message' => 'An error occured, please try again',
                'hint' => $e->getMessage(),
            ];
        }
    }
}
