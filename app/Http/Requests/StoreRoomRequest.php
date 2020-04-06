<?php

namespace App\Http\Requests;

use App\Room;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreRoomRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('room_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'name'     => [
                'required'
            ],
            'capacity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647'
            ],
            'hourly_rate' => [
                'nullable',
                'numeric',
            ]
        ];

    }
}
