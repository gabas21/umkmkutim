<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Database\MySqlConnection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class UmkmCoordinatesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_stored_point_reads_back_with_latitude_and_longitude_in_the_right_order(): void
    {
        $umkm = $this->createUmkmAt(0.493, 117.545);

        $stored = Umkm::query()->withCoordinates()->findOrFail($umkm->id);

        $this->assertEqualsWithDelta(0.493, (float) $stored->latitude, 0.00001);
        $this->assertEqualsWithDelta(117.545, (float) $stored->longitude, 0.00001);
    }

    public function test_nearby_scope_finds_point_by_distance(): void
    {
        $umkm = $this->createUmkmAt(0.493, 117.545);

        $nearby = Umkm::query()->nearby(0.494, 117.546, 1000)->pluck('id');

        $this->assertContains($umkm->id, $nearby);
    }

    public function test_mysql_8_uses_geographic_axis_order(): void
    {
        $connection = Mockery::mock(MySqlConnection::class);
        $connection->shouldReceive('getDriverName')->andReturn('mysql');
        $connection->shouldReceive('isMaria')->andReturn(false);
        $connection->shouldReceive('getServerVersion')->andReturn('8.0.36');
        DB::shouldReceive('connection')->andReturn($connection);

        $this->assertSame('ST_Latitude(location)', Umkm::latitudeExpression());
        $this->assertSame('ST_Longitude(location)', Umkm::longitudeExpression());
        $this->assertSame("ST_GeomFromText(?, 4326, 'axis-order=long-lat')", Umkm::geomFromTextExpression());
    }

    public function test_mariadb_keeps_x_y_axis_order(): void
    {
        $connection = Mockery::mock(MySqlConnection::class);
        $connection->shouldReceive('getDriverName')->andReturn('mysql');
        $connection->shouldReceive('isMaria')->andReturn(true);
        DB::shouldReceive('connection')->andReturn($connection);

        $this->assertSame('ST_Y(location)', Umkm::latitudeExpression());
        $this->assertSame('ST_X(location)', Umkm::longitudeExpression());
        $this->assertSame('ST_GeomFromText(?, 4326)', Umkm::geomFromTextExpression());
    }

    private function createUmkmAt(float $latitude, float $longitude): Umkm
    {
        $kategori = Kategori::first() ?? Kategori::create([
            'nama' => 'Kuliner',
            'slug' => 'kuliner',
            'icon' => 'utensils',
        ]);

        return Umkm::create([
            'nama_usaha' => 'UMKM Uji Koordinat',
            'slug' => 'umkm-uji-koordinat-'.uniqid(),
            'kategori_id' => $kategori->id,
            'deskripsi' => 'UMKM uji koordinat.',
            'alamat' => 'Jl. Uji Koordinat',
            'kecamatan' => 'Sangatta Utara',
            'location' => Umkm::makePoint($latitude, $longitude),
            'status' => 'active',
            'status_klaim' => 'belum_diklaim',
            'sumber_data' => 'mandiri',
        ]);
    }
}
