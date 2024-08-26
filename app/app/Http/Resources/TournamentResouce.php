<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResouce extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="TournamentResouce",
     *     type="object",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="Id of the tournament"
     *     ),
     *     @OA\Property(
     *         property="date",
     *         type="date",
     *         description="Date of the tournament"
     *     ),
     *     @OA\Property(
     *         property="category",
     *         type="string",
     *         description="Category of the tournament"
     *     ),
     *      @OA\Property(
     *         property="winner",
     *         type="string",
     *         description="Winner of the tournament"
     *     )
     * )
    */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'category' => $this->category,
            'winner' => $this->winner
        ];
    }
}
