<?php

namespace App\Http\Traits;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait Upload
{
    public function makeDirectory($path)
    {
        if (file_exists($path)) return true;
        return mkdir($path, 0755, true);
    }

    public function removeFile($path)
    {
        return file_exists($path) && is_file($path) ? @unlink($path) : false;
    }

    public function uploadImage($file, $location, $size = null, $old = null, $thumb = null, $filename = null)
    {


        $path = $this->makeDirectory($location);

        if (!$path) throw new \Exception('File could not been created.');

        if (!empty($old)) {
            Storage::delete($location . '/' . $old);
            Storage::delete($location . '/thumb_' . $old);
            // $this->removeFile($location . '/' . $old);
            // $this->removeFile($location . '/thumb_' . $old);
        }

        if ($filename == null) {
            $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
        }

        $image = Image::make($file);


        if (!empty($size)) {
            $size = explode('x', strtolower($size));
            $image->resize($size[0], $size[1]);
        }
        // $image->save($location . '/' . $filename);
        Storage::putFileAs($location , $file, $filename);

        if (!empty($thumb)) {
            $thumb = explode('x', $thumb);
            // Image::make($file)->resize($thumb[0], $thumb[1])->save($location . '/thumb_' . $filename);
            $thumbFile = Image::make($file)->resize($thumb[0], $thumb[1]);
            Storage::putFileAs($location , $thumbFile, 'thumb_' . $filename);
        }

        return $filename;
    }


}

