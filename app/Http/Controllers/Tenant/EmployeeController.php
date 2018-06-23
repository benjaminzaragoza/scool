<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteEmployee;
use App\Http\Requests\StoreEmployee;
use App\Models\Employee;

/**
 * Class EmployeeController.
 *
 * @package App\Http\Controllers\Tenant
 */
class EmployeeController extends Controller
{
    /**
     * Store.
     *
     * @return string
     */
    public function store(StoreEmployee $request)
    {
        $employee= Employee::create([
            'user_id' => $request->user_id,
            'job_id' => $request->job_id,
            'holder' => $request->holder,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at
        ]);

        return $employee;
    }

    /**
     * Destroy
     *
     * @param DeleteEmployee $request
     * @param $tenant
     * @param Employee $employee
     * @return Employee
     * @throws \Exception
     */
    public function destroy(DeleteEmployee $request, $tenant, Employee $employee)
    {
        $employee->delete();
        return $employee;
    }
}
