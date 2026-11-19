<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AttendanceSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllRolesAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        AttendanceSetting::create([
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'late_tolerance_minutes' => 15,
            'office_latitude' => -6.200000,
            'office_longitude' => 106.816666,
            'office_radius_meters' => 1000,
            'min_face_match_score' => 70,
            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'is_wfa_allowed' => true,
        ]);
    }

    public function test_all_roles_can_access_attendance_and_scan()
    {
        $rolesToTest = [
            ['content_management'],
            ['event_finance'],
            ['full_access'],
            ['field_staff'],
            ['event_finance', 'content_management'],
        ];

        foreach ($rolesToTest as $roles) {
            $user = User::factory()->create([
                'usertype' => 'karyawan',
                'role' => $roles[0],
                'roles' => $roles,
            ]);

            // 1. Can access karyawan dashboard
            $response = $this->actingAs($user)->get(route('karyawan.dashboard'));
            $response->assertStatus(200);

            // 2. Can access face register
            $response = $this->actingAs($user)->get(route('karyawan.face.register'));
            $response->assertStatus(200);

            // 3. Register face
            $mockDescriptor = array_fill(0, 128, 0.123);
            $response = $this->actingAs($user)->postJson(route('karyawan.face.store'), [
                'face_descriptor' => json_encode($mockDescriptor),
                'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
            ]);
            $response->assertStatus(200)->assertJson(['success' => true]);

            // 4. Can access scan
            $response = $this->actingAs($user)->get(route('karyawan.attendance.scan'));
            $response->assertStatus(200);

            // 5. Can record attendance
            $user->refresh();
            $response = $this->actingAs($user)->postJson(route('karyawan.attendance.record'), [
                'type' => 'in',
                'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'face_match_score' => 95,
            ]);
            $response->assertStatus(200)->assertJson(['success' => true]);
        }
    }

    public function test_admin_usertype_can_also_access_attendance()
    {
        $admin = User::factory()->create([
            'usertype' => 'admin',
            'role' => 'full_access',
            'roles' => ['full_access'],
        ]);

        $response = $this->actingAs($admin)->get(route('karyawan.dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('karyawan.attendance.scan'));
        $response->assertStatus(200);
    }
}
