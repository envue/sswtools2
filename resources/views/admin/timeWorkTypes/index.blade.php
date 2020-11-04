@extends('layouts.admin')
@section('content')

@can('time_work_type_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <h3>Work types</h3>
        </div>
    </div>
@endcan
<div class="row">
    <div class="col-lg-12">
    <div class="card">
              <div class="card-header">             
                <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#custom-work-types" data-toggle="tab">Custom Work Types</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#system-work-types" data-toggle="tab">System Work Types</a>
                </li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                    <!-- custom work types tab -->
                    <div class="tab-pane active" id="custom-work-types">
                        <div class="row">
                            <div class="col-sm-8">
                                <h5 class="font-weight-bold">My custom work types <br>
                                <small class="text-muted">Define custom work types for time entries and reports.</small>
                                </h5>
                            </div>
                            <div class="col-sm-4">
                                <a class="btn btn-success btn-inline float-right" style="margin-top:10px;" href="{{ route('admin.time-work-types.create') }}">
                                + Add custom work type
                                </a>
                            </div>
                        </div>
                        @can('user_edit')
                        <div style="border-bottom:1px solid #ccc; margin-bottom: 20px;"></div>
                        @endcan
                        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-TimeWorkType">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    @can('user_edit')
                                    <th>
                                        {{ trans('cruds.timeWorkType.fields.id') }}
                                    </th>
                                    @endcan
                                    <th>
                                        {{ trans('cruds.timeWorkType.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.timeWorkType.fields.description') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.timeWorkType.fields.color') }}
                                    </th>
                                    <th>
                                        Use Pop
                                    </th>
                                    <th>
                                        Use Case
                                    </th>
                                    @can('user_edit')
                                    <th>
                                        System
                                    </th>
                                    @endcan
                                    <th>
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                        </table>                        
                    </div>
                    <div class="tab-pane" id="system-work-types">
                        <div class="row">
                            <div class="col-lg-12">                                
                                <h5 class="font-weight-bold">System work types <br>
                                    <small class="text-muted">Show or hide the default system work types on forms and report filters. 
                                    Existing time entries will not be affected and will continue to display on the calendar and reports.</small>
                                </h5>
                            </div>
                        </div>
                        <form id="updateHiddenWorkTypes" method="POST" action="{{ route("profile.password.updateHiddenWorkTypes") }}">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-condensed  table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Work type</th>
                                            <th>Description</th>
                                            <th>Color</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach($system_work_types as $id => $system_work_type)
                                        <tr>
                                            <td class="text-nowrap"> 
                                                <input class="switch-input" type="checkbox" id="{{ $system_work_type['name'] }}-{{ $id }}" name="hidden_work_types[]" value="{{ $id }}" data-toggle="toggle" data-size="sm">
                                                <label class="switch-label" style="font-style: strong; margin-left: 5px;"for="{{ $system_work_type['name'] }}">{{ $system_work_type['name'] }}</label>
                                            </td>
                                            <td>
                                                {{ $system_work_type['description'] }}
                                            </td>
                                            <td>
                                                <h5><span style="background-color:{{ $system_work_type['color'] }}; color: #ffffff;" class="badge">  {{ $system_work_type['color'] }}  </span></h5>
                                            </td>
                                        </tr>
                                        @endforeach
                                                                
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group" style="margin-bottom: 0px">
                                <button class="btn btn-danger" type="submit">
                                    Save Changes
                                </button>
                            </div>
                        </form>                        
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    //Toogle Switches
    $('.switch-input').bootstrapToggle({
        on: 'Off',
        off: 'On',
        onstyle: 'light',
        offstyle: 'primary'
        });

    $('.switch-input').each(function( i ) {
    var val = $(this).val();
    var hiddenWorkTypes = "{{ $user_hidden_work_types }}";

    if (hiddenWorkTypes.includes(val) ) {
        $(this).bootstrapToggle('on');
    } 
    });
    
    //Datatables
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('time_work_type_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.time-work-types.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
            var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                return entry.id
            });

            if (ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}')

                return
            }

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'POST',
                url: config.url,
                data: { ids: ids, _method: 'DELETE' }})
                .done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan

        let dtOverrideGlobals = {
            dom:
                @can('user_edit')
                "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                @endcan
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-4'i><'col-sm-8'p>>", 
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.time-work-types.index') }}",
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                @can('user_edit')
                { data: 'id', name: 'id' },
                @endcan
                { data: 'name', name: 'name', className: 'text-nowrap' },
                { data: 'description', name: 'description' },
                { data: 'color', name: 'color' },
                { data: 'use_population_type', name: 'use_population_type' },
                { data: 'use_caseload_type', name: 'use_caseload_type' },
                @can('user_edit')
                { data: 'system_value', name: 'system_value' },
                @endcan
                { data: 'actions', name: '{{ trans('global.actions') }}' }
                    ],
                    orderCellsTop: true,
                    order: [[ 1, 'desc' ]],
                    pageLength: 100,
        };
        let table = $('.datatable-TimeWorkType').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    });

    // Create or Update Events via Ajax
    
    $('#updateHiddenWorkTypes').submit(function (e) {
                e.preventDefault();
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                //process ajax request
                $.ajax({
                    data: $('#updateHiddenWorkTypes').serialize(),
                    url: "{{ route('profile.password.updateHiddenWorkTypes') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        toastr["success"]("Settings updated successfully.", "Success!");
                        console.log('Success:', data);
                        $('.btn').removeClass('active');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        toastr["danger"]("Something went wrong.", "Oh no!");
                    }
                });
            });

</script>
@endsection