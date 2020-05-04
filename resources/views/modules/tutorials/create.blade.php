@extends('layouts.app')

@section('title', 'Create a New Tutorial')

@section('content')

    <h1>Create a New Tutorial</h1>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Details</div>

                    <div class="card-body">

                        <form action="{{ route('modules.tutorials.store', $module->id) }}" method="post">

                            @csrf
                            <div class="form-group row">

                                <label class="col-form-label col-md-4 text-md-right" for="module_id">
                                    Module Id:
                                </label>

                                <div class="col-md-6">
                                    <input
                                        type="text"
                                        id="module_id"
                                        class="form-control-plaintext"
                                        name="module_id"
                                        readonly
                                        value="{{ $module->id }}"
                                    >

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-form-label col-md-4 text-md-right" for="time_start">
                                    Start Time:
                                </label>

                                <div class="col-md-6">
                                    <input
                                        type="datetime-local"
                                        id="time_start"
                                        class="form-control @error('time_start') is-invalid @enderror"
                                        name="time_start"
                                        value="{{ old('time_start') }}"
                                    >

                                    @error('time_start')
                                    <span class="invalid-feedback text-left">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-form-label col-md-4 text-md-right" for="time_end">
                                    End Time:
                                </label>

                                <div class="col-md-6">
                                    <input
                                        type="datetime-local"
                                        id="time_end"
                                        class="form-control @error('time_end') is-invalid @enderror"
                                        name="time_end"
                                        value="{{ old('time_end') }}"
                                    >

                                    @error('time_end')
                                    <span class="invalid-feedback text-left">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-form-label col-md-4 text-md-right" for="room">
                                    Room:
                                </label>

                                <div class="col-md-6">
                                    <input
                                        type="text"
                                        id="room"
                                        class="form-control @error('room') is-invalid @enderror"
                                        name="room"
                                        value="{{ old('room') }}"
                                    >

                                    @error('room')
                                    <span class="invalid-feedback text-left">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-form-label col-sm-4 text-right" for="tutors">
                                    Select Tutors:
                                </label>
                                <select
                                    id="tutors"
                                    class="form-control col-sm-2 @error('tutors') is-invalid @enderror"
                                    multiple
                                    size="{{ count($tutors) }}"
                                    name="tutors[]"
                                >

                                    @foreach($tutors as $tutor)

                                        <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>

                                    @endforeach

                                </select>

                                @error('tutors')
                                <span class="col invalid-feedback text-left">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="col-sm-8 text-right">

                                <input type="submit" name="submit" class="btn btn-primary" value="Create Module">

                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
