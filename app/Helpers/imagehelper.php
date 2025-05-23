<?php

namespace App\Helpers;

class ImageHelper
{
    public static function uploadAndResize(
        $file,
        $directory,
        $filename,
        $witdh = null,
        $height = null
    ) {
        $destinationPath=public_path($directory);
        $extension=strtolower($file->getClientOriginalExtension());
        $image=null;
        // Tentukan metode pembuatan gambar berdasarkan ekstensi file
        switch($extension){
            case 'jpeg':
            case 'jpg':
                $image=imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
                $image=imagecreatefrompng($file->getRealPath());
                break;
            case 'gif':
                $image=imagecreatefromgif($file->getRealPath());
                break;
            default:
            throw new \Exception('Unsupported image type');
        }
        // Resize gambar jika lebar diset
        if($witdh){
            $oldWidth=imagesx($image);
            $oldHeight=imagesy($image);
            $aspectRatio=$oldWidth/$oldHeight;
            if (!$height){
                $height=$witdh/$aspectRatio; // Hitung tinggi dengan mempertahankan aspek ratio
            }
        $newImage=imagecreatetruecolor($witdh, $height);
        imagecopyresampled(
            $newImage,
            $image,
            0,
            0,
            0,
            0,
            $witdh,
            $height,
            $oldWidth,
            $oldHeight
        );
        imagedestroy($image);
        $image=$newImage;
        }
        // Simpan gambar dengan kualitas asli
        switch ($extension){
            case 'jpeg':
                case 'jpg':
                    imagejpeg($image, $destinationPath . '/' . $filename);
                    break;
                case 'png':
                    imagepng($image, $destinationPath . '/' . $filename);
                    break;
                case 'gif':
                    imagegif($image, $destinationPath . '/' . $filename);
                    break;
        }
        imagedestroy($image);
        return $filename;
    }
}