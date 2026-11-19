<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create initial Super Admin (ID 1)
        User::create([
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'superadmin@stukka.com',
            'usertype' => 'admin',
            'role' => User::ROLE_FULL_ACCESS,
            'roles' => [User::ROLE_FULL_ACCESS],
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_admin_can_assign_multiple_roles_to_employee(): void
    {
        $admin = User::find(1);

        $response = $this->actingAs($admin)->post(route('admin.employees.store'), [
            'name' => 'Multi Role Employee',
            'email' => 'multirole@stukka.com',
            'nip' => 'NIP-9988',
            'position' => 'Event Producer & Content Admin',
            'roles' => [User::ROLE_EVENT_FINANCE, User::ROLE_CONTENT_MANAGEMENT],
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'multirole@stukka.com',
        ]);

        $user = User::where('email', 'multirole@stukka.com')->first();
        $this->assertTrue($user->canManageEventFinance());
        $this->assertTrue($user->canManageContent());
        $this->assertFalse($user->canManageEmployees());
        $this->assertTrue($user->hasAdminAccess());
        $this->assertEquals(['event_finance', 'content_management'], $user->roles_list);
    }

    public function test_multi_role_employee_can_access_both_permitted_modules(): void
    {
        $multiUser = User::create([
            'name' => 'Dual Manager',
            'email' => 'dual@stukka.com',
            'usertype' => 'karyawan',
            'role' => User::ROLE_EVENT_FINANCE,
            'roles' => [User::ROLE_EVENT_FINANCE, User::ROLE_CONTENT_MANAGEMENT],
            'password' => bcrypt('password123'),
        ]);

        // Can access Event Management
        $eventResp = $this->actingAs($multiUser)->get(route('admin.scheduled-events.index'));
        $this->assertEquals(200, $eventResp->getStatusCode());

        // Can access Portfolio / Content Management
        $contentResp = $this->actingAs($multiUser)->get(route('admin.events.index'));
        $this->assertEquals(200, $contentResp->getStatusCode());

        // Cannot access Employee Management (Full Access only)
        $employeeResp = $this->actingAs($multiUser)->get(route('admin.employees.index'));
        $employeeResp->assertRedirect(route('admin.index'));
    }

    public function test_admin_can_update_employee_roles(): void
    {
        $admin = User::find(1);

        $employee = User::create([
            'name' => 'Staff To Promote',
            'email' => 'staff@stukka.com',
            'usertype' => 'karyawan',
            'role' => User::ROLE_FIELD_STAFF,
            'roles' => [User::ROLE_FIELD_STAFF],
            'password' => bcrypt('password123'),
        ]);

        $this->assertFalse($employee->canManageContent());

        $response = $this->actingAs($admin)->put(route('admin.employees.update', $employee->id), [
            'name' => 'Staff To Promote',
            'email' => 'staff@stukka.com',
            'nip' => 'NIP-1234',
            'position' => 'Promoted Coordinator',
            'roles' => [User::ROLE_CONTENT_MANAGEMENT, User::ROLE_EVENT_FINANCE],
        ]);

        $response->assertRedirect(route('admin.employees.index'));

        $employee->refresh();
        $this->assertTrue($employee->canManageContent());
        $this->assertTrue($employee->canManageEventFinance());
        $this->assertEquals(['content_management', 'event_finance'], $employee->roles_list);
    }

    public function test_validation_fails_if_no_role_selected(): void
    {
        $admin = User::find(1);

        $employee = User::create([
            'name' => 'Sample Staff',
            'email' => 'sample@stukka.com',
            'usertype' => 'karyawan',
            'role' => User::ROLE_FIELD_STAFF,
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin)->put(route('admin.employees.update', $employee->id), [
            'name' => 'Sample Staff',
            'email' => 'sample@stukka.com',
            'roles' => [],
        ]);

        $response->assertSessionHasErrors('roles');
    }
}
