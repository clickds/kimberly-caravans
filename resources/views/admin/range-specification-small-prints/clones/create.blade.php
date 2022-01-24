@extends('layouts.admin')

@section('title', 'Clone range specification small print')
@section('page-title', 'Clone range specification small print')

@section('page')
  <div class="w-full">
    <form method="POST" action="{{ route('admin.range-specification-small-prints.clones.store', $rangeSpecificationSmallPrint) }}" enctype="multipart/form-data" class="admin-form">
      @csrf
      @include('admin._partials.errors')

      <div>
        <label for="site_id">Site</label>
        <select name="site_id">
          @foreach($sites as $site)
            <option value="{{ $site->id }}"{{ old('site_id') == $site->id ? ' selected' : '' }}>
              {{ $site->country }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="flex items-center justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
          @lang('global.clone')
        </button>
      </div>
    </form>
  </div>
@endsection