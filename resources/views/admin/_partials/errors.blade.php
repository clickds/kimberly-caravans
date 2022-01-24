@if($errors->any())
  <div class="admin-form-errors">
    <p>Errors:</p>
    <ul>
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif