<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (!$this->resource && !$this->resource->id) {
            return [];
        }
        return [
            'name' => $this->name,
            'price' => $this->numberFormatPrecision($this->price),
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'stories' => $this->stories,
            'garages' => $this->garages
        ];
    }

    private function numberFormatPrecision($number, $precision = 2, $separator = '.')
    {
        $numberParts = explode($separator, $number);
        $response = $numberParts[0];
        if (count($numberParts)>1 && $precision > 0) {
            $response .= $separator;
            $response .= substr($numberParts[1], 0, $precision);
        }
        return $response;
    }
}