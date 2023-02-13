<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for uploading images.
|
| uploadedFile =    Image file
| folder =          Directory path
| disk =            Public
| filename =        Image name
*/

trait UploadTrait
{
    public function uploadFile(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
      // dd($disk == 'public');
      // file path
      $path = $disk.$folder;
      // Get just ext
      $extension = $uploadedFile->getClientOriginalExtension();
      // Filename to store
      $fileNameToStore = time() . rand(1,200) . '.' . $extension;
      // Upload
      if($disk == 'public')
        $uploadedFile->storeAs($path,$fileNameToStore);
      else
        $uploadedFile->move(public_path($path), $fileNameToStore);

      return $fileNameToStore;
    }
}