<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Users\StoreRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class UsersController extends BaseController
{
    public function index(Request $request): View
    {
        $users = User::ransack($request->all())->orderBy('name', 'asc')->get();

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function create(Request $request): View
    {
        $user = new User();

        return view('admin.users.create', [
            'user' => $user,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $user = new User();
        if ($this->saveUser($user, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'User created');
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User created');
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('warning', 'Failed to create user');
    }

    public function edit(User $user, Request $request): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, User $user): RedirectResponse
    {
        if ($this->saveUser($user, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'User updated');
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User updated');
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Failed to update user');
    }

    public function destroy(User $user, Request $request): RedirectResponse
    {
        $user->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'User deleted');
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted');
    }

    private function saveUser(User $user, FormRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $baseData = Arr::except($request->validated(), ['password']);
            if ($password = $request->input('password')) {
                $baseData['password'] = bcrypt($password);
            }
            $user->fill($baseData);
            $user->save();

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }
}
