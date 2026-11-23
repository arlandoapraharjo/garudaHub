<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_rating_for_project(): void
    {
        $client = User::factory()->create(['role' => 'klien']);
        $developer = User::factory()->create(['role' => 'developer']);

        $project = Project::create([
            'client_id' => $client->id,
            'judul' => 'Website Profil Kampus',
            'deskripsi' => 'Pengembangan website interaktif profil kampus.',
            'kategori' => 'Web Development',
            'budget_min' => 1000000,
            'budget_max' => 2000000,
            'batas_lelang' => now()->addDays(7),
            'status' => 'terbuka',
        ]);

        $response = $this->actingAs($developer)->post(route('ratings.store', $project), [
            'skor' => 5,
            'ulasan' => 'Proyek sangat jelas dan komunikasi klien sangat baik!',
        ]);

        $response->assertRedirect(route('projects.show', $project));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ratings', [
            'project_id' => $project->id,
            'user_id' => $developer->id,
            'skor' => 5,
            'ulasan' => 'Proyek sangat jelas dan komunikasi klien sangat baik!',
        ]);

        $this->assertSame(5.0, $project->fresh()->averageRating());
    }

    public function test_user_can_update_existing_rating(): void
    {
        $client = User::factory()->create(['role' => 'klien']);
        $developer = User::factory()->create(['role' => 'developer']);

        $project = Project::create([
            'client_id' => $client->id,
            'judul' => 'Sistem Informasi Inventaris',
            'deskripsi' => 'Pengembangan aplikasi inventaris barang.',
            'kategori' => 'Desktop',
            'budget_min' => 1500000,
            'budget_max' => 3000000,
            'batas_lelang' => now()->addDays(5),
            'status' => 'terbuka',
        ]);

        // Submit initial rating
        $this->actingAs($developer)->post(route('ratings.store', $project), [
            'skor' => 4,
            'ulasan' => 'Cukup memuaskan.',
        ]);

        // Update rating
        $this->actingAs($developer)->post(route('ratings.store', $project), [
            'skor' => 5,
            'ulasan' => 'Revisi: Sangat memuaskan setelah finalisasi!',
        ]);

        $this->assertDatabaseCount('ratings', 1);
        $this->assertDatabaseHas('ratings', [
            'project_id' => $project->id,
            'user_id' => $developer->id,
            'skor' => 5,
            'ulasan' => 'Revisi: Sangat memuaskan setelah finalisasi!',
        ]);
        $this->assertSame(5.0, $project->fresh()->averageRating());
    }

    public function test_guest_cannot_submit_rating(): void
    {
        $client = User::factory()->create(['role' => 'klien']);
        $project = Project::create([
            'client_id' => $client->id,
            'judul' => 'Landing Page UMKM',
            'deskripsi' => 'Pembuatan landing page sederhana.',
            'kategori' => 'Web',
            'budget_min' => 500000,
            'budget_max' => 1000000,
            'batas_lelang' => now()->addDays(3),
            'status' => 'terbuka',
        ]);

        $response = $this->post(route('ratings.store', $project), [
            'skor' => 5,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('ratings', 0);
    }

    public function test_rating_score_validation(): void
    {
        $client = User::factory()->create(['role' => 'klien']);
        $developer = User::factory()->create(['role' => 'developer']);

        $project = Project::create([
            'client_id' => $client->id,
            'judul' => 'Aplikasi Mobile Kasir',
            'deskripsi' => 'Pembuatan POS mobile.',
            'kategori' => 'Mobile',
            'budget_min' => 2000000,
            'budget_max' => 4000000,
            'batas_lelang' => now()->addDays(10),
            'status' => 'terbuka',
        ]);

        // Score 0 is invalid (min: 1)
        $response = $this->actingAs($developer)->post(route('ratings.store', $project), [
            'skor' => 0,
        ]);
        $response->assertSessionHasErrors('skor');

        // Score 6 is invalid (max: 5)
        $response2 = $this->actingAs($developer)->post(route('ratings.store', $project), [
            'skor' => 6,
        ]);
        $response2->assertSessionHasErrors('skor');
    }
}
