<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Http\Resources\UserVehicleIndexResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\UserVehicleCreateRequest;
use App\Http\Traits\FileTrait;
use App\Models\VehicleImages;
use App\Models\VehicleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserVehicleService
{
    use FileTrait;

    /**
     *  Return an index of a users vehicle based on request params
     * 
     *  @param Request
     * 
     *  @return AnonymousResourceCollection
     */
    public function index(Request $request) : AnonymousResourceCollection
    {
        return UserVehicleIndexResource::collection(
            Vehicle::where('user_id', auth()->id())
                ->where('active', $this->isActive($request->active))
                ->orderBy('price_day', $this->priceSortDirection($request->priceSort))
                ->with('vehicleModel.vehicleMake')
                ->with('vehicleImages')
                ->paginate(10)
        );
    }

    /**
     *  Create a new vehicle belonging to the user
     * 
     *  @param UserVehicleCreateRequest
     * 
     *  @return JsonResponse
     */
    public function create(UserVehicleCreateRequest $request) : JsonResponse
    {
        $vehicle = Vehicle::create([
            'user_id' => current_user()->id,
            'vehicle_model_id' => VehicleModel::where('model', $request['model'])->first()->id,
            'featured_image' => 'none',
            'year' => $request['year'],
            'plate_num' => $request['plate'],
            'price_day' => $request['price'],
            'description' => $request['description'],
            'doors' => $request['doors'],
            'seats' => $request['seats'],
            'active' => 1
        ]);

        $featuredImageId = $request['featured_id'];

        // Store the vehicle images
        foreach ($request['images'] as $image) {
            // If the image is the one the user wants featured
            if ($image->getClientOriginalName() === $featuredImageId) {
                $resizedImage = $this->processFeaturedImage($image);

                $vehicle->update([
                    'featured_image' => $resizedImage
                ]);
            }

            $newName = $this->generateFileName($image->extension());

            $image->storeAs('vehicle-images', $newName, 'public');

            $fullPath = '/storage/vehicle-images/' . $newName;

            VehicleImages::create([
                'vehicle_id' => $vehicle->id,
                'image' => $fullPath
            ]);
        }

        return response()->json(201);
    }

    /**
     *  Process the featured image
     * 
     *  @param Object $image
     * 
     *  @return string
     */
    public function processFeaturedImage(Object $image) : string
    {
        $newName = $this->generateFileName($image->extension());

        $resize = Image::make($image)
            ->fit(600, 360);

        Storage::disk('public')->put('vehicle-images-featured/' . $newName, $resize->encode());

        return '/storage/vehicle-images-featured/' . $newName;
    }

    /**
     *  Determine active
     * 
     *  @param string $status
     * 
     *  @return boolean
     */
    private function isActive(string $status) : bool
    {
        return $status === 'true' ? 1 : 0;
    }

    /**
     *  Determine sort direction for price
     *  
     *  @param string $direction
     * 
     *  @return string
     */
    private function priceSortDirection(string $direction) : string
    {
        if (!in_array($direction, array('desc', 'asc'))) {
            return 'desc';
        }

        return $direction;
    }
}