<?php

namespace App\Http\Controllers\Admin\Dealer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dealer\Employees\StoreRequest;
use App\Http\Requests\Admin\Dealer\Employees\UpdateRequest;
use App\Models\Dealer;
use App\Models\DealerEmployee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeesController extends Controller
{
    public function index(Dealer $dealer): View
    {
        $employees = $dealer->employees()->get();

        return view('admin.dealer.employees.index', [
            'dealer' => $dealer,
            'employees' => $employees,
        ]);
    }

    public function create(Dealer $dealer): View
    {
        return view('admin.dealer.employees.create', [
            'dealer' => $dealer,
            'employee' => new DealerEmployee(),
        ]);
    }

    public function store(StoreRequest $request, Dealer $dealer): RedirectResponse
    {
        $employee = $dealer->employees()->create($request->validated());

        $this->storeImage($employee, $request);

        return redirect()->route('admin.dealers.employees.index', $dealer);
    }

    public function edit(Dealer $dealer, DealerEmployee $employee): View
    {
        return view('admin.dealer.employees.edit', [
            'dealer' => $dealer,
            'employee' => $employee,
        ]);
    }

    public function update(UpdateRequest $request, Dealer $dealer, DealerEmployee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        $this->storeImage($employee, $request);

        return redirect()->route('admin.dealers.employees.index', $dealer);
    }

    public function destroy(Dealer $dealer, DealerEmployee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('admin.dealers.employees.index', $dealer);
    }

    private function storeImage(DealerEmployee $employee, FormRequest $request): void
    {
        if (!isset($request->validated()['image'])) {
            return;
        }

        $employee->addMediaFromRequest('image')->toMediaCollection('image');
    }
}
