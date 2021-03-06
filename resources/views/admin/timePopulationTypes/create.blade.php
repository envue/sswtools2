@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.timePopulationType.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.time-population-types.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.timePopulationType.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.timePopulationType.fields.name_helper') }}</span>
            </div>
            @can('user_management_access')
            <div class="form-group">
                <div class="form-check {{ $errors->has('system_value') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="system_value" value="0">
                    <input class="form-check-input" type="checkbox" name="system_value" id="system_value" value="1" {{ old('system_value', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="system_value">System Vanlue</label>
                </div>
                @if($errors->has('system_value'))
                    <span class="text-danger">{{ $errors->first('system_value') }}</span>
                @endif
                <span class="help-block">Creates a system wide value that is available to all users</span>
            </div>
            @endcan
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection