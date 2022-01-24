@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page')
  <h1>Dashboard:</h1>

  <div class="grid grid-cols-2 gap-4">
    <div class="bg-white rounded-lg border border-gray-400 p-4">
      <h2>Latest Edited Pages</h2>

      <table class="w-full table-auto">
        <tr>
          <th class="border px-4 py-2">Page</th>
          <th class="border px-4 py-2">Updated</th>
        </tr>
        @foreach ($pages as $page)
          <tr>
            <td class="border px-4 py-2">
              <a class="underline text-blue-500" href="{{ route('admin.pages.edit', $page) }}">
                {{ $page->name }}
              </a>
            </td>
            <td class="border px-4 py-2">
              {{ $page->updated_at->format('jS F Y H:i') }}
            </td>
          </tr>
        @endforeach
      </table>
    </div>

    <div class="bg-white rounded-lg border border-gray-400 p-4">
      <h2>Latest Edited Areas</h2>

      <table class="w-full table-auto">
        <tr>
          <th class="border px-4 py-2">Area</th>
          <th class="border px-4 py-2">Updated</th>
        </tr>
        @foreach ($areas as $area)
          <tr>
            <td class="border px-4 py-2">
              <a class="underline text-blue-500" href="{{ route('admin.pages.areas.edit', [
                'page' => $area->page_id,
                'area' => $area,
                ]) }}">
                {{ $area->name }}
              </a>
            </td>
            <td class="border px-4 py-2">
              {{ $area->updated_at->format('jS F Y H:i') }}
            </td>
          </tr>
        @endforeach
      </table>
    </div>

    <div class="bg-white rounded-lg border border-gray-400 p-4">
      <h2>Latest Edited Panels</h2>

      <table class="w-full table-auto">
        <tr>
          <th class="border px-4 py-2">Panel</th>
          <th class="border px-4 py-2">Updated</th>
        </tr>
        @foreach ($panels as $panel)
          <tr>
            <td class="border px-4 py-2">
              <a class="underline text-blue-500" href="{{ route('admin.areas.panels.edit', [
                'area' => $panel->area_id,
                'panel' => $panel,
                ]) }}">
                {{ $panel->name }}
              </a>
            </td>
            <td class="border px-4 py-2">
              {{ $panel->updated_at->format('jS F Y H:i') }}
            </td>
          </tr>
        @endforeach
      </table>
    </div>

    <div class="bg-white rounded-lg border border-gray-400 p-4">
      <h2>Latest Edited Manufacturers</h2>

      <table class="w-full table-auto">
        <tr>
          <th class="border px-4 py-2">Manufacturer</th>
          <th class="border px-4 py-2">Updated</th>
        </tr>
        @foreach ($manufacturers as $manufacturer)
          <tr>
            <td class="border px-4 py-2">
              <a class="underline text-blue-500" href="{{ route('admin.manufacturers.edit', [
                'manufacturer' => $manufacturer
                ]) }}">
                {{ $manufacturer->name }}
              </a>
            </td>
            <td class="border px-4 py-2">
              {{ $manufacturer->updated_at->format('jS F Y H:i') }}
            </td>
          </tr>
        @endforeach
      </table>
    </div>


    <div class="bg-white rounded-lg border border-gray-400 p-4">
      <h2>Latest Edited Motorhome Ranges</h2>

      <table class="w-full table-auto">
        <tr>
          <th class="border px-4 py-2">Motorhome Range</th>
          <th class="border px-4 py-2">Updated</th>
        </tr>
        @foreach ($motorhomeRanges as $motorhomeRange)
          <tr>
            <td class="border px-4 py-2">
              <a class="underline text-blue-500" href="{{ route('admin.manufacturers.motorhome-ranges.edit', [
                'manufacturer' => $motorhomeRange->manufacturer_id,
                'motorhome_range' => $motorhomeRange,
                ]) }}">
                {{ $motorhomeRange->name }}
              </a>
            </td>
            <td class="border px-4 py-2">
              {{ $motorhomeRange->updated_at->format('jS F Y H:i') }}
            </td>
          </tr>
        @endforeach
      </table>
    </div>

    <div class="bg-white rounded-lg border border-gray-400 p-4">
      <h2>Latest Edited Caravan Ranges</h2>

      <table class="w-full table-auto">
        <tr>
          <th class="border px-4 py-2">Caravan Range</th>
          <th class="border px-4 py-2">Updated</th>
        </tr>
        @foreach ($caravanRanges as $caravanRange)
          <tr>
            <td class="border px-4 py-2">
              <a class="underline text-blue-500" href="{{ route('admin.manufacturers.caravan-ranges.edit', [
                'manufacturer' => $caravanRange->manufacturer_id,
                'caravan_range' => $caravanRange,
                ]) }}">
                {{ $caravanRange->name }}
              </a>
            </td>
            <td class="border px-4 py-2">
              {{ $caravanRange->updated_at->format('jS F Y H:i') }}
            </td>
          </tr>
        @endforeach
      </table>
    </div>

    <div class="bg-white rounded-lg border border-gray-400 p-4">
      <h2>Latest Edited Motorhomes</h2>

      <table class="w-full table-auto">
        <tr>
          <th class="border px-4 py-2">Motorhome</th>
          <th class="border px-4 py-2">Updated</th>
        </tr>
        @foreach ($motorhomes as $motorhome)
          <tr>
            <td class="border px-4 py-2">
              <a class="underline text-blue-500" href="{{ route('admin.motorhome-ranges.motorhomes.edit', [
                'motorhomeRange' => $motorhome->motorhome_range_id,
                'motorhome' => $motorhome,
                ]) }}">
                {{ $motorhome->name }}
              </a>
            </td>
            <td class="border px-4 py-2">
              {{ $motorhome->updated_at->format('jS F Y H:i') }}
            </td>
          </tr>
        @endforeach
      </table>
    </div>

    <div class="bg-white rounded-lg border border-gray-400 p-4">
      <h2>Latest Edited Caravans</h2>

      <table class="w-full table-auto">
        <tr>
          <th class="border px-4 py-2">Caravan</th>
          <th class="border px-4 py-2">Updated</th>
        </tr>
        @foreach ($caravans as $caravan)
          <tr>
            <td class="border px-4 py-2">
              <a class="underline text-blue-500" href="{{ route('admin.caravan-ranges.caravans.edit', [
                'caravanRange' => $caravan->caravan_range_id,
                'caravan' => $caravan,
                ]) }}">
                {{ $caravan->name }}
              </a>
            </td>
            <td class="border px-4 py-2">
              {{ $caravan->updated_at->format('jS F Y H:i') }}
            </td>
          </tr>
        @endforeach
      </table>
    </div>

  </div>
@endsection