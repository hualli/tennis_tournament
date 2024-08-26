<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class InputTournamentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'required|string|in:male,female',
            'players' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    // Contar la cantidad de jugadores
                    $count = count($value);

                    // Verificar si la cantidad es una potencia de 2
                    if ($count < 1 || ($count & ($count - 1)) !== 0) {
                        $fail('The ' . $attribute . ' must contain a number of players that is a power of 2.');
                    }
                },
            ],
            'players.*.name' => 'required|string|min:3|max:100',
            'players.*.skill_level' => 'required|integer|between:0,100',

            // Condicional para 'strength' y 'travel_speed'
            'players.*.strength' => [
                Rule::requiredIf($this->input('category') === 'male'),
                'integer',
            ],
            'players.*.travel_speed' => [
                Rule::requiredIf($this->input('category') === 'male'),
                'integer',
            ],

            // Condicional para 'reaction_time'
            'players.*.reaction_time' => [
                Rule::requiredIf($this->input('category') === 'female'),
                'integer',
            ],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ]
        ));
    }
}
