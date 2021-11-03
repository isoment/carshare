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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
        if (!current_user()->host) {
            return response()->json('You must be a host to create a vehicle', 403);
        }

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

        return response()->json('Created new vehicle', 201);
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
        $path = $this->imageNameFromPath($image->image);

        // Delete the image from storage if it's not part of the seeder images
        Storage::disk('public')->delete($path);

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
    public function update(int $id, UserVehicleUpdateRequest $request) : JsonResponse
    {
        $vehicle = Vehicle::find($id);

        // Check to make sure the user owns the vehicle
        if ($vehicle->user_id !== current_user()->id) {
            return response()->json('You do not own this vehicle', 403);
        }

        // If there are no existing images and no images in the request
        if (!$vehicle->vehicleHasImages() && empty($request['images'])) {
            $vehicle->update([
                'active' => 0
            ]);
    
            return response()->json('Please provide images for your vehicle', 404);
        }

        // Store the vehicle images, and set featured image if it is one the new
        // ones passed in the request.
        if (!empty($request['images'])) {
            $this->storeImages($request, $vehicle);
        }

        // If the featured image is an existing image
        if (!is_null($request['featured_id'])) {
            if (str_contains($request['featured_id'], '/storage/vehicle-images/')) {
                $this->setExistingImageAsFeatured($request, $vehicle);
            }
        }

        // Update the vehicle attributes
        $vehicle->update([
            'price_day' => $request['price'],
            'description' => $request['description'],
            'active' => $this->isActive($request['active'])
        ]);

        return response()->json(201);
    }

    /**
     *  Store uploaded images
     * 
     *  @param Request $request
     *  @param Vehicle $vehicle
     * 
     *  @return void
     */
    private function storeImages(Request $request, Vehicle $vehicle) : void
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
     *  Set an existing image as the featured image
     * 
     *  @param Request $request
     *  @param Vehicle $vehicle
     * 
     *  @return void
     */
    private function setExistingImageAsFeatured(Request $request, Vehicle $vehicle) : void
    {
        // Process the filename we pass in
        $imageName = $this->imageNameFromPath($request['featured_id']);

        // Get the image we want to feature from storage
        $existingImage = Storage::disk('public')->get($imageName);

        // The extension
        $extension = explode('.', $imageName);

        // Generate a new filename
        $newName = $this->generateFileName($extension[1]);

        // Resize the image
        $resize = Image::make($existingImage)
            ->fit(600, 360);

        // Store the new featured image
        Storage::disk('public')->put('vehicle-images-featured/' . $newName, $resize->encode());

        // The new complete path
        $completePath = '/storage/vehicle-images-featured/' . $newName;

        // Delete the old featured image
        $oldFeaturedImagePath = $this->imageNameFromPath($vehicle->featured_image);

        Storage::disk('public')->delete($oldFeaturedImagePath);

        // Update the vehicle
        $vehicle->update([
            'featured_image' => $completePath
        ]);
    }

    /**
     *  Process the featured image and return a string with its path
     * 
     *  @param UploadedFile $image
     * 
     *  @return string
     */
    private function processFeaturedImage(UploadedFile $image) : string
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
     *  @param string|null $status
     * 
     *  @return boolean
     */
    private function isActive(string|null $status) : int
    {
        return $status === 'true' ? 1 : 0;
    }

    /**
     *  Determine sort direction for price
     *  
     *  @param string|null $direction
     * 
     *  @return string
     */
    private function priceSortDirection(string|null $direction) : string
    {
        if (!in_array($direction, array('desc', 'asc'))) {
            return 'desc';
        }

        return $direction;
    }

    /**
     *  Get the image name and folder from the full path
     * 
     *  @param string $imagePath
     * 
     *  @return string
     */
    private function imageNameFromPath(string $imagePath) : string
    {
        $split = explode('/', $imagePath);

        return $split[2] . '/' . $split[3];
    }
}