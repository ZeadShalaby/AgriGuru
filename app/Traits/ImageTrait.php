<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;


trait ImageTrait
{
    // ?todo save image 
    public function saveimage($image, $folder)
    {

        $image_name = time() . '.' . $image->extension();
        // $image_base_name = pathinfo($image_name, PATHINFO_FILENAME);
        $images = $image->move(public_path($folder), $image_name);
        $path = "/api/imageusers/$image_name";

        return $path;
    }

    // ?todo return image users I Want it
    public function returnimageusers($value, $folder)
    {
        return response()->download(public_path($folder . $value));
    }

    // ?todo return image users I Want it
    public function returnimageuser($value)
    {
        return response()->download(storage_path('app/public/images/users/' . $value));
    }


    // ?todo add new media 
    protected function Addmedia($info, $media)
    {
        $info->media()->create([
            'media' => $media
        ]);
    }


    // ?todo add new media 
    protected function AddCVmedia($info, $medias)
    {
        $user_media = Media::where('mediaable_type', 'User')->where('mediaable_id', auth()->user()->id)->first();
        $media = $user_media->media;
        $medias_name = pathinfo($medias, PATHINFO_FILENAME);
        $filePath = public_path('pdfs/' . $user_media->cv);
        //? check if file exists
        if (Storage::disk('public')->exists($filePath)) {
            //? delete old file
            Storage::disk('public')->delete($filePath);
        }
        $user_media->delete();
        $test = $info->media()->create([
            'media' => $media,
            'mediacv' => "/api/cv-users/" . $medias_name,
            'cv' => $medias
        ]);
    }

    // ?todo save pdf
    public function savePdf($file, $folder)
    {
        $folderPath = public_path($folder);

        //? Ensure the folder exists
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $CVFile = pathinfo($fileName, PATHINFO_FILENAME);
        try {
            $file->move($folderPath, $CVFile);
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
        return $fileName;
    }




}





