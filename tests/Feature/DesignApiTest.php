<?php

namespace Tests\Feature;

use App\Models\DesignSticker;
use App\Models\SavedDesign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DesignApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_save_and_list_own_designs(): void
    {
        $user = User::factory()->create();
        $sticker = DesignSticker::create([
            'name' => 'Smile',
            'image' => '/storage/images/stickers/smile.png',
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/designs/saved', [
            'name' => 'My Shirt',
            'design_id' => null,
            'design_data' => [
                'canvas' => ['width' => 320, 'height' => 420],
                'layers' => [
                    ['type' => 'text', 'text' => 'Athar'],
                ],
            ],
            'sticker_ids' => [$sticker->id],
        ])
            ->assertCreated()
            ->assertJsonPath('status', true)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'My Shirt')
            ->assertJsonPath('data.design_id', null)
            ->assertJsonPath('data.design_data.layers.0.text', 'Athar')
            ->assertJsonPath('data.design.name', 'My Shirt')
            ->assertJsonPath('data.design.user_id', $user->id);

        $this->getJson('/api/designs/saved')
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.designs.data.0.name', 'My Shirt');
    }

    public function test_user_cannot_view_another_users_saved_design(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $design = SavedDesign::create([
            'user_id' => $owner->id,
            'name' => 'Private Design',
            'design_data' => ['layers' => []],
        ]);

        Sanctum::actingAs($otherUser);

        $this->getJson("/api/designs/saved/{$design->id}")
            ->assertNotFound();
    }
}
