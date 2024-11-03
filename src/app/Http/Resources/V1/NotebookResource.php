<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="NotebookResource",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID записи"
 *     ),
 *     @OA\Property(
 *         property="fullname",
 *         type="string",
 *         description="Полное имя",
 *         example="Иван Иванов Иванович"
 *     ),
 *     @OA\Property(
 *         property="company",
 *         type="string",
 *         description="Название компании",
 *         example="ООО Ромашка"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="Телефон",
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
 *         description="Дата рождения",
 *         example="1990-01-01"
 *     ),
 *     @OA\Property(
 *         property="photo",
 *         type="string",
 *         description="Путь фотографии",
 *         example="/storage/uploads/20230101_123456_photo.jpg"
 *     ),
 *     @OA\Property(
 *         property="created",
 *         type="string",
 *         format="datetime",
 *         description="Дата и время создания",
 *         example="2023-01-01 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated",
 *         type="string",
 *         format="datetime",
 *         description="Дата и время последнего обновления",
 *         example="2023-01-02 14:30:00"
 *     )
 * )
 */
class NotebookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'company' => $this->company,
            'phone' => $this->phone,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
            'photo' => $this->photo,
            'created' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
