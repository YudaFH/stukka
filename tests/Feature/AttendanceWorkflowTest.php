<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttendanceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default attendance setting
        AttendanceSetting::create([
            'office_latitude' => -6.200000,
            'office_longitude' => 106.816666,
            'radius_meters' => 500,
            'work_start_time' => '08:30:00',
            'work_end_time' => '17:00:00',
            'min_face_match_score' => 0.50,
            'office_name' => 'Stukka HQ Test',
            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        ]);
    }

    /**
     * Test 1: Employee cannot record attendance before registering facial biometrics
     */
    public function test_employee_without_registered_face_cannot_record_attendance(): void
    {
        $employee = User::factory()->create([
            'usertype' => 'karyawan',
            'role' => User::ROLE_FIELD_STAFF,
            'face_descriptor' => null,
        ]);

        $response = $this->actingAs($employee)->postJson(route('karyawan.attendance.record'), [
            'type' => 'in',
            'image' => 'data:image/jpeg;base64,' . base64_encode('fake-image-bytes'),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test 2: Employee records Clock-In (Absen Masuk) successfully
     */
    public function test_employee_can_clock_in_successfully(): void
    {
        Storage::fake('public');

        $employee = User::factory()->create([
            'usertype' => 'karyawan',
            'role' => User::ROLE_FIELD_STAFF,
            'face_descriptor' => json_encode(array_fill(0, 128, 0.1)),
        ]);

        $response = $this->actingAs($employee)->postJson(route('karyawan.attendance.record'), [
            'type' => 'in',
            'image' => 'data:image/jpeg;base64,' . base64_encode('test-snapshot-jpeg'),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'face_match_score' => 0.85,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'type' => 'in',
        ]);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $employee->id,
        ]);
    }

    /**
     * Test 3: Employee cannot clock out if they haven't clocked in yet
     */
    public function test_cannot_clock_out_without_clocking_in_first(): void
    {
        Storage::fake('public');

        $employee = User::factory()->create([
            'usertype' => 'karyawan',
            'role' => User::ROLE_FIELD_STAFF,
            'face_descriptor' => json_encode(array_fill(0, 128, 0.1)),
        ]);

        $response = $this->actingAs($employee)->postJson(route('karyawan.attendance.record'), [
            'type' => 'out',
            'image' => 'data:image/jpeg;base64,' . base64_encode('test-snapshot-jpeg'),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'face_match_score' => 0.88,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Anda belum melakukan absensi masuk hari ini!',
        ]);
    }

    /**
     * Test 4: Complete Clock-Out and save daily work summary with file
     */
    public function test_employee_can_clock_out_and_submit_daily_work_report(): void
    {
        Storage::fake('public');

        $employee = User::factory()->create([
            'usertype' => 'karyawan',
            'role' => User::ROLE_FIELD_STAFF,
            'face_descriptor' => json_encode(array_fill(0, 128, 0.1)),
        ]);

        // Clock in first
        $this->actingAs($employee)->postJson(route('karyawan.attendance.record'), [
            'type' => 'in',
            'image' => 'data:image/jpeg;base64,' . base64_encode('in-snapshot'),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'face_match_score' => 0.90,
        ]);

        // Clock out with work summary and report file
        $reportDoc = UploadedFile::fake()->create('laporan_harian.pdf', 200, 'application/pdf');

        $clockOutResponse = $this->actingAs($employee)->postJson(route('karyawan.attendance.record'), [
            'type' => 'out',
            'image' => 'data:image/jpeg;base64,' . base64_encode('out-snapshot'),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'face_match_score' => 0.92,
            'work_summary' => '1. Standby broadcast streaming. 2. Bongkar rigging dan kabel audio.',
            'work_file' => $reportDoc,
        ]);

        $clockOutResponse->assertStatus(200);
        $clockOutResponse->assertJson([
            'success' => true,
            'type' => 'out',
        ]);

        $attendance = Attendance::where('user_id', $employee->id)
            ->whereDate('date', Carbon::today()->toDateString())
            ->first();

        $this->assertNotNull($attendance->time_in);
        $this->assertNotNull($attendance->time_out);
        $this->assertEquals('1. Standby broadcast streaming. 2. Bongkar rigging dan kabel audio.', $attendance->work_summary);
        $this->assertNotNull($attendance->work_file);
    }

    /**
     * Test 5: Employee cannot clock out twice on the same date
     */
    public function test_employee_cannot_clock_out_twice(): void
    {
        Storage::fake('public');

        $employee = User::factory()->create([
            'usertype' => 'karyawan',
            'role' => User::ROLE_FIELD_STAFF,
            'face_descriptor' => json_encode(array_fill(0, 128, 0.1)),
        ]);

        // In
        $this->actingAs($employee)->postJson(route('karyawan.attendance.record'), [
            'type' => 'in',
            'image' => 'data:image/jpeg;base64,' . base64_encode('in-img'),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
        ]);

        // Out 1
        $this->actingAs($employee)->postJson(route('karyawan.attendance.record'), [
            'type' => 'out',
            'image' => 'data:image/jpeg;base64,' . base64_encode('out-img-1'),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
        ]);

        // Out 2 (Attempt duplicate checkout)
        $secondOut = $this->actingAs($employee)->postJson(route('karyawan.attendance.record'), [
            'type' => 'out',
            'image' => 'data:image/jpeg;base64,' . base64_encode('out-img-2'),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
        ]);

        $secondOut->assertStatus(422);
        $secondOut->assertJson([
            'success' => false,
        ]);
    }
}
