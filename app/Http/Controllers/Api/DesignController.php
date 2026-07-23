<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Desgin;
use App\Models\DesignSticker;
use App\Models\SavedDesign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DesignController extends Controller
{
    public function templates(): JsonResponse
    {
        $templates = Desgin::latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Design templates fetched successfully',
            'data' => [
                'templates' => $templates,
            ],
        ]);
    }

    public function stickers(Request $request): JsonResponse
    {
        $stickers = DesignSticker::query()
            ->where('is_active', true)
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Design stickers fetched successfully',
            'data' => [
                'stickers' => $stickers,
            ],
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $designs = $request->user()
            ->savedDesigns()
            ->with(['product:id,name,image', 'desgin'])
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return response()->json([
            'status' => true,
            'message' => 'Saved designs fetched successfully',
            'data' => [
                'designs' => $designs,
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('preview_image_file')) {
            $data['preview_image'] = Helper::storeImage($request->file('preview_image_file'), 'saved-designs');
        }

        unset($data['preview_image_file']);

        if (array_key_exists('design_id', $data)) {
            $data['desgin_id'] = $data['design_id'];
            unset($data['design_id']);
        }

        if (! empty($data['preview_image_url']) && empty($data['preview_image'])) {
            $data['preview_image'] = $data['preview_image_url'];
        }

        $design = $request->user()->savedDesigns()->create($data);

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Design saved successfully',
            'data' => $this->designResponseData($design->fresh()),
        ], 201);
    }

    public function show(Request $request, SavedDesign $savedDesign): JsonResponse
    {
        $this->authorizeSavedDesign($request, $savedDesign);

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Saved design fetched successfully',
            'data' => $this->designResponseData($savedDesign->load(['product:id,name,image', 'desgin'])),
        ]);
    }

    public function update(Request $request, SavedDesign $savedDesign): JsonResponse
    {
        $this->authorizeSavedDesign($request, $savedDesign);

        $data = $this->validatedData($request, true);

        if ($request->hasFile('preview_image_file')) {
            $data['preview_image'] = Helper::updateImage(
                $request->file('preview_image_file'),
                $savedDesign->getRawOriginal('preview_image'),
                'saved-designs'
            );
        }

        unset($data['preview_image_file']);

        if (array_key_exists('design_id', $data)) {
            $data['desgin_id'] = $data['design_id'];
            unset($data['design_id']);
        }

        if (! empty($data['preview_image_url']) && empty($data['preview_image'])) {
            $data['preview_image'] = $data['preview_image_url'];
        }

        $savedDesign->update($data);

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Design updated successfully',
            'data' => $this->designResponseData($savedDesign->fresh()->load(['product:id,name,image', 'desgin'])),
        ]);
    }

    public function destroy(Request $request, SavedDesign $savedDesign): JsonResponse
    {
        $this->authorizeSavedDesign($request, $savedDesign);

        if ($savedDesign->getRawOriginal('preview_image')) {
            Helper::deleteImage($savedDesign->getRawOriginal('preview_image'));
        }

        $savedDesign->delete();

        return response()->json([
            'status' => true,
            'message' => 'Design deleted successfully',
            'data' => null,
        ]);
    }

    private function validatedData(Request $request, bool $isUpdate = false): array
    {
        $required = $isUpdate ? 'sometimes' : 'required';

        return $request->validate([
            'name' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'design_id' => 'nullable|exists:desgins,id',
            'desgin_id' => 'nullable|exists:desgins,id',
            'preview_image' => 'nullable|string|max:2048',
            'preview_image_url' => 'nullable|string|max:2048',
            'preview_image_file' => 'nullable|image|max:4096',
            'design_data' => [$required, 'array'],
            'sticker_ids' => 'nullable|array',
            'sticker_ids.*' => [
                'integer',
                Rule::exists('design_stickers', 'id')->where('is_active', true),
            ],
        ]);
    }

    private function authorizeSavedDesign(Request $request, SavedDesign $savedDesign): void
    {
        abort_unless($savedDesign->user_id === $request->user()->id, 404);
    }

    private function designPayload(SavedDesign $design): array
    {
        return [
            'id' => $design->id,
            'user_id' => $design->user_id,
            'name' => $design->name,
            'product_id' => $design->product_id,
            'design_id' => $design->desgin_id,
            'desgin_id' => $design->desgin_id,
            'design_data' => $design->design_data,
            'sticker_ids' => $design->sticker_ids,
            'preview_image_url' => $design->preview_image_url ?? $design->preview_image,
            'created_at' => $design->created_at?->toJSON(),
            'updated_at' => $design->updated_at?->toJSON(),
            'product' => $design->relationLoaded('product') ? $design->product : null,
            'template' => $design->relationLoaded('desgin') ? $design->desgin : null,
        ];
    }

    private function designResponseData(SavedDesign $design): array
    {
        $payload = $this->designPayload($design);

        return array_merge($payload, [
            'design' => $payload,
        ]);
    }
}
