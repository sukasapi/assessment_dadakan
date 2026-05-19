@if(session('success'))
<div class="admin-alert admin-alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="admin-alert admin-alert-error">
    {{ session('error') }}
</div>
@endif

@if(isset($errors) && $errors->any() && !$errors->has('import_errors'))
<div class="admin-alert admin-alert-error">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            @if($error !== $errors->first('error'))
                <li>{{ $error }}</li>
            @endif
        @endforeach
    </ul>
</div>
@endif
