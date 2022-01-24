@extends('layouts.admin')

@section('title', 'Show Submission')

@section('page-title', 'Show Submission')

@section('page')
  <div>
    <div>
      <h2>{{ $form->name }} Submission</h2>
    </div>
    <div class="mt-5 border-t border-gray-200 pt-5">
      <dl>
        <div class=" sm:grid sm:grid-cols-3 sm:gap-4">
          <dt class="text-sm leading-5 font-medium text-gray-500">
            Submission Date
          </dt>
          <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
            {{ $submission->created_at }}
          </dd>
        </div>

        @foreach($submission->submission_data as $name => $value)
          <div class="mt-8 sm:grid sm:mt-5 sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
            <dt class="text-sm leading-5 font-medium text-gray-500">
              {{ $name }}
            </dt>
            @if(is_array($value))
              <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                <ul>
                  @foreach($value as $individual_value)
                    <li>{{ $individual_value }}</li>
                  @endforeach
                </ul>
              </dd>
            @else
              <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                {{ $value }}
              </dd>
            @endif
          </div>
        @endforeach
      </dl>
    </div>
    @if ($submission->getMedia())
      <div class="mt-10">
        <h3>Uploaded Files</h3>
        @foreach ($submission->getMedia('file-uploads') as $file)
          <a href="{{ $file->getFullUrl() }}" target="_blank" class="underline text-endeavour">{{ $file->file_name }}</a>
        @endforeach
      </div>
    @endif
  </div>
@endsection