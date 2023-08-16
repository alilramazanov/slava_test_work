<?php

namespace App\Http\Requests\Excel;

use App\Http\Requests\BaseRequest;

class UploadFileRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'file' => 'file|required'
        ];
    }
}
