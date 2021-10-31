<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Http\Resources\UserVehicleIndexResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\UserVehicleCreateRequest;
use App\Http\Requests\UserVehicleUpdateRequest;
use App\Http\Traits\FileTrait;
use App\Models\VehicleImages;
use App\Models\VehicleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use PHPUnit\Util\Json;

class UserVehicleService
{
    use FileTrait;

    /**
     *  Return an index of a users vehicle based on request params
     * 
     *  @param Request $request
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
     *  @param UserVehicleCreateRequest $request
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
            'active' => 0
        ]);

        // Store the vehicle images
        $this->storeImages($request, $vehicle);

        return response()->json(201);
    }

    /**
     *  Delete a vehicles image
     * 
     *  @param Request $request
     * 
     *  @return JsonResponse
     */
    public function deleteImage(Request $request) : JsonResponse
    {
        $request->validate([
            'image' => 'required|exists:vehicle_images,image'
        ]);

        // If the image is a seeder image don't allow removal
        if (str_contains($request['image'], 'seeder')) {
            return response()->json('Seeder images cannot be removed', 403);
        }

        $image = VehicleImages::where('image', $request->image)->first();

        $vehicle = $image->vehicle;

        if (!$image->imageBelongsToUsersVehicle()) {
            return response()->json('This is not your vehicle', 403);
        }

        // Process the image path
        $split = explode('/', $image->image);
        $recombined = $split[2] . '/' . $split[3];

        // Delete the image from storage if it's not part of the seeder images
        Storage::disk('public')->delete($recombined);

        // Delete the record
        $image->delete();

        // If there are no images for the vehicle set it to inactive until a new
        // one is provided
        if (!$vehicle->vehicleHasImages()) {
            $vehicle->update([
                'active' => 0
            ]);
    
            return response()->json('Vehicle set to inactive until image is provided', 404);
        }

        return response()->json(204);
    }

    /**
     *  Update a vehicle
     * 
     *  @param int $id
     *  @param UserVehicleUpdateRequest $request
     * 
     *  @return JsonResponse
     */
    public function update(int $id, UserVehicleUpdateRequest $request)
    {
        $vehicle = Vehicle::find($id);

        if ($vehicle->user_id !== current_user()->id) {
            return response()->json('You do not own this vehicle', 403);
        }

        if (!$vehicle->vehicleHasImages() && empty($request['images'])) {
            $vehicle->update([
                'active' => 0
            ]);
    
            return response()->json('Please provide images for your vehicle', 404);
        }

        // Store the vehicle images
        if (!empty($request['images'])) {
            $this->storeImages($request, $vehicle);
        }

        // Update the vehicle attributes
        $vehicle->update([
            'price_day' => $request['price'],
            'description' => $request['description'],
            'active' => $this->isActive($request['active'])
        ]);

        return response()->json($request->toArray());
    }

    /**
     *  Store uploaded images
     * 
     *  @param Request $request
     *  @param Vehicle $vehicle
     * 
     *  @return void
     */
    public function storeImages(Request $request, Vehicle $vehicle) : void
    {
        foreach ($request['images'] as $image) {
            if ($image->getClientOriginalName() === $request['featured_id']) {
                $resizedImagePath = $this->processFeaturedImage($image);

                $vehicle->update([
                    'featured_image' => $resizedImagePath
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
    }

    /**
     *  Process the featured image
     * 
     *  @param Object $image
     * 
     *  @return string
     */
    private function processFeaturedImage(Object $image) : string
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
    private function isActive(string $status) : int
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