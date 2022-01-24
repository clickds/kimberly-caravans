@if($dealer->getEmployees()->isNotEmpty())
  <div class="container mx-auto px-standard py-10">
    <h2 class="text-endeavour font-h2 font-semibold mb-10 text-center">Contact Us</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 grid-auto-rows">
      @foreach($dealer->getEmployees() as $employee)
        <div class="listing-card">
          @if ($image = $employee->getFirstMedia('image'))
            @include('site.shared.box-shadow-image', ['image' => $image, 'collectionName' => 'responsiveIndex'])
          @endif
          <div class="content-container">
            <h3 class="text-endeavour font-h3 font-medium">
              {{ $employee->name }}
            </h3>
            <div class="font-sans font-medium text-h5 mb-5">{{ $employee->role }}</div>
            @if($emailAddress = $employee->email)
              <div class="text-endeavour font-sans font-bold text-h5 underline">
                <a href="mailto:{{ $emailAddress }}">Contact {{ $employee->name }} by email</a>
              </div>
            @endif
            @if($telephoneNumber = $employee->phone)
              <div class="font-sans font-medium text-h5 mb-5 mt-3">Tel: {{ $telephoneNumber }}</div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endif