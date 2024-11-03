<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateNotebookRequest",
 *     required={"fullname", "phone", "email", "password"},
 *     @OA\Property(
 *         property="fullname",
 *         type="string",
 *         description="Полное имя",
 *         example="Иван Иванов Иванович"
 *     ),
 *     @OA\Property(
 *         property="company",
 *         type="string",
 *         description="Название компании (опционально)",
 *         example="ООО Ромашка"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="Номер телефона",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Email",
 *         example="ivan@example.com"
 *     ),
 *     @OA\Property(
 *         property="birthdate",
 *         type="string",
 *         format="date",
 *         description="Дата рождения (опционально)",
 *         example="1990-01-01"
 *     ),
 *     @OA\Property(
 *         property="photo",
 *         type="string",
 *         format="binary",
 *         description="Фотография (опционально)"
 *     )
 * )
 */
class UpdateNotebookRequest extends FormRequest
{
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
            'fullname' => 'required|string',
            'company' => 'nullable|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'birthdate' => 'nullable|date',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
