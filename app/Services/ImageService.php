<?php

namespace App\Services;

/**
 * Class ImageService
 * @package App\Services
 */
class ImageService
{
    //Add image
    public static function addImage($path, $image, $imageName) {

            $filename = strtolower(
                uniqid($imageName)
                .'.'
                .$image->getClientOriginalExtension()
            );
            str_replace(' ', '-', $filename);
            return basename($image->move($path, $filename));
    }
    //update image
    public static function updateImage($path, $image, $oldImage,$imageName) {
        $filename = strtolower(
            uniqid($imageName)
            .'.'
            .$image->getClientOriginalExtension()
        );
        str_replace(' ', '-', $filename);
        $move_image =  basename($image->move($path, $filename));
        //delete Old Image
        $image_path = public_path().'/'.$path.'/'.basename($oldImage);
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        //end delete Old Image
        return $move_image;
    }
    // Add Multiple images
    public static function addMultipleImage($path, $images, $imageName) {
        $imgData = [] || null;
        foreach ($images as $file) {
            $filename = strtolower(uniqid($imageName).'.'.$file->getClientOriginalExtension());
            $file->move($path, $filename);
            $imgData[] = $filename;
        }
        return json_encode($imgData);
    }
}
