@extends('layouts.app')

@section('title', 'Create a New Module')

@section('content')

    <h1>Create a New Module</h1>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Details</div>

                    <div class="card-body">

                        <form action="{{ route('modules.store') }}" method="post">

                            @csrf

                            <div class="form-group row">

                                <label class="col-form-label col-md-4 text-md-right" for="id">
                                    Module Id:
                                </label>

                                <div class="col-md-6">
                                    <input
                                        type="text"
                                        id="id"
                                        class="form-control @error('id') is-invalid @enderror"
                                        name="id"
                                        value="{{ old('id') }}"
                                        placeholder="module id"
                                    >

                                    @error('id')
                                    <span class="invalid-feedback text-left">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-form-label col-md-4 text-md-right" for="name">
                                    Module Name:
                                </label>

                                <div class="col-md-6">
                                    <input
                                        type="text"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="module name"
                                    >

                                    @error('name')
                                    <span class="invalid-feedback text-left">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-form-label col-sm-4 text-right" for="lead_tutor_id">
                                    Module Leader:
                                </label>
                                <select
                                    id="lead_tutor_id"
                                    class="form-control col-sm-2 @error('lead_tutor_id') is-invalid @enderror"
                                    name="lead_tutor_id"
                                >
                                    <option value="">Please select...</option>

                                    @foreach($tutors as $tutor)

                                        <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>

                                    @endforeach

                                </select>

                                @error('lead_tutor_id')
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
