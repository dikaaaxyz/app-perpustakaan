<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DashboardAndReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_stats_from_internal_api(): void
    {
        $user = User::factory()->create();

        Http::fake([
            '*/api/stats' => Http::response([
                'total_buku' => 20,
                'total_anggota' => 15,
                'peminjaman_aktif' => 5,
            ], 200),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Total Buku');
        $response->assertSee('20');
        $response->assertSee('Total Anggota');
        $response->assertSee('15');
        $response->assertSee('Peminjaman Aktif');
        $response->assertSee('5');
    }

    public function test_dashboard_fallback_when_api_fails(): void
    {
        $user = User::factory()->create();

        Http::fake([
            '*/api/stats' => Http::response([], 500),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('0');
    }

    public function test_loan_report_displays_loans_from_internal_api(): void
    {
        $user = User::factory()->create();

        Http::fake([
            '*/api/loans*' => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'member' => ['id' => 1, 'nama' => 'Budi Santoso'],
                        'petugas' => ['id' => 1, 'name' => 'Bambang Sutrisno'],
                        'tanggal_pinjam' => '2026-11-01',
                        'tanggal_kembali' => '2026-11-08',
                        'tanggal_dikembalikan' => null,
                        'status' => 'dipinjam',
                        'books' => [
                            ['id' => 1, 'judul' => 'Laskar Pelangi'],
                        ],
                    ],
                ],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)->get(route('loans.report'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Peminjaman');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Bambang Sutrisno');
        $response->assertSee('Laskar Pelangi');
        $response->assertSee('Dipinjam');
    }

    public function test_custom_pagination_view_is_used(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['nama_kategori' => 'Novel']);
        Book::factory()->count(15)->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertSee('class="pagination"', false);
    }
}
