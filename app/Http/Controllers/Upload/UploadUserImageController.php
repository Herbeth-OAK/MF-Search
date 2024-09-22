<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;


class UploadUserImageController extends Controller
{
    /*
    subir uma imagem e executar o comando
    php artisan storage:link

    **/
    /**
     * Handle the incoming request.
     */
    public function __invoke(ImageUploadRequest $request)
    {
        $file = $request->file('file');

        $fileName = uniqid(time()) . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('uploads/users/'), $fileName);

        $imageUrl = "uploads/users/$fileName";

        return $this->send($imageUrl);
    }
}
