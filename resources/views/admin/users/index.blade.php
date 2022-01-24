@extends('layouts.admin')

@section('title', 'All Users')

@section('page-title', 'All Users')

@section('page-actions')
  @can('create', \App\Models\User::class)
    @include('admin._partials.page-actions', [
      'name' => 'User',
      'url'  => routeWithCurrentUrlAsRedirect('admin.users.create')
    ])
  @endcan
@endsection

@section('page')
  @include('admin.users._filter-form')

  @if ($users->isNotEmpty())
    <table class="admin-table w-full mt-5">
      <thead>
        <tr>
          <th></th>
          <th class="text-left">Name</th>
          <th class="text-left">Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
          <tr>
            <td class="text-center">{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              <div class="w-full flex action justify-center">
                @can('update', $user)
                  <div>
                    <a href="{{ routeWithCurrentUrlAsRedirect('admin.users.edit', $user) }}" class="bg-green-500 hover:bg-blue-700 text-white py-2 px-4 rounded-full inline-block ml-1">
                      Edit
                    </a>
                  </div>
                @endcan

                @can('delete', $user)
                  <div>
                    <form action="{{ routeWithCurrentUrlAsRedirect('admin.users.destroy', $user) }}" method="POST">
                      @method('delete')
                      @csrf
                      <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded-full inline-block ml-1">
                        Delete
                      </button>
                    </form>
                  </div>
                @endcan
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
@endsection