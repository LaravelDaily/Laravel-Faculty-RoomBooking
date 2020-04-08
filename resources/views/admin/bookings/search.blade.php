@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Search Room
    </div>

    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input class="form-control datetime" type="text" name="start_time" id="start_time" value="{{ request()->input('start_time') }}" placeholder="{{ trans('cruds.event.fields.start_time') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input class="form-control datetime" type="text" name="end_time" id="end_time" value="{{ request()->input('end_time') }}" placeholder="{{ trans('cruds.event.fields.end_time') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input class="form-control" type="number" name="capacity" id="capacity" value="{{ request()->input('capacity') }}" placeholder="{{ trans('cruds.room.fields.capacity') }}" step="1" required>
                    </div>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-success">
                        Search
                    </button>
                </div>
            </div>
        </form>
        @if($rooms !== null)
            <hr />
            @if($rooms->count())
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable datatable-Event">
                        <thead>
                            <tr>
                                <th>
                                    {{ trans('cruds.event.fields.room') }}
                                </th>
                                <th>
                                    {{ trans('cruds.room.fields.capacity') }}
                                </th>
                                <th>
                                    {{ trans('cruds.room.fields.hourly_rate') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rooms as $room)
                                <tr>
                                    <td class="room-name">
                                        {{ $room->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $room->capacity ?? '' }}
                                    </td>
                                    <td>
                                        {{ $room->hourly_rate ? '$' . number_format($room->hourly_rate, 2) : 'FREE' }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#bookRoom" data-room-id="{{ $room->id }}">
                                            Book Room
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">There are no rooms available at the time you have chosen</p>
            @endif
        @endif
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="bookRoom">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking of a room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.bookRoom') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="room_id" id="room_id" value="{{ old('room_id') }}">
                    <input type="hidden" name="start_time" value="{{ request()->input('start_time') }}">
                    <input type="hidden" name="end_time" value="{{ request()->input('end_time') }}">
                    <div class="form-group">
                        <label class="required" for="title">{{ trans('cruds.event.fields.title') }}</label>
                        <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                        @if($errors->has('title'))
                            <div class="invalid-feedback">
                                {{ $errors->first('title') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.event.fields.title_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="description">{{ trans('cruds.event.fields.description') }}</label>
                        <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                        @if($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.event.fields.description_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="recurring_until">Recurring until</label>
                        <input class="form-control date {{ $errors->has('recurring_until') ? 'is-invalid' : '' }}" type="text" name="recurring_until" id="recurring_until" value="{{ old('recurring_until') }}">
                        @if($errors->has('recurring_until'))
                            <div class="invalid-feedback">
                                {{ $errors->first('recurring_until') }}
                            </div>
                        @endif
                    </div>
                    <button type="submit" style="display: none;"></button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitBooking">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$('#bookRoom').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var roomId = button.data('room-id');
    var modal = $(this);
    modal.find('#room_id').val(roomId);
    modal.find('.modal-title').text('Booking of a room ' + button.parents('tr').children('.room-name').text());

    $('#submitBooking').click(() => {
        modal.find('button[type="submit"]').trigger('click');
    });
});
</script>
@endsection
