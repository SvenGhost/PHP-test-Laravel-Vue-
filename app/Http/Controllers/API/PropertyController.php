<?php

namespace App\Http\Controllers\API;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;

class PropertyController extends Controller
{
    public function index () {
        return PropertyResource::collection(Property::latest()->get());
    }
    public function searchProperties () {

        $properties = Property::query();

        $properties = $this->nameSearch($properties);

        $properties = $this->priceSearch($properties);

        $properties = $this->bedroomsSearch($properties);
        
        $properties = $this->bathroomsSearch($properties);
        
        $properties = $this->storiesSearch($properties);
        
        $properties = $this->garagesSearch($properties);
        
        $properties = $properties->latest()->get();

        return PropertyResource::collection($properties);
    }

    public function nameSearch($properties)
    {
        if(request()->has('name') && request()->filled('name')) {
            $properties = $properties->where('name', 'LIKE', '%'.request()->name.'%');
        }
        return $properties;
    }
    
    public function priceSearch($properties)
    {
        if(request()->has('price_from') && request()->filled('price_from')) {
            $properties = $properties->where('price', '>=', request()->price_from);
        }
        if(request()->has('price_to') && request()->filled('price_to')) {
            $properties = $properties->where('price', '<=', request()->price_to);
        }
        return $properties;
    }

    public function bedroomsSearch($properties)
    {
        if(request()->has('bedrooms') && request()->filled('bedrooms')) {
            $properties = $properties->where('bedrooms', request()->bedrooms);
        }
        return $properties;
    }
    
    public function bathroomsSearch($properties)
    {
        if(request()->has('bathrooms') && request()->filled('bathrooms')) {
            $properties = $properties->where('bathrooms', request()->bathrooms);
        }
        return $properties;
    }
    
    public function storiesSearch($properties)
    {
        if(request()->has('stories') && request()->filled('stories')) {
            $properties = $properties->where('stories', request()->stories);
        }
        return $properties;
    }
    
    public function garagesSearch($properties)
    {
        if(request()->has('garages') && request()->filled('garages')) {
            $properties = $properties->where('garages', request()->garages);
        }
        return $properties;
    }
}
