@extends('admin.layouts.master')
@section("title") Restaurant Owner's Restaurants - Dashboard
@endsection
@section('content')
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-circle-right2 mr-2"></i>
                <span class="font-weight-bold mr-2">Editing</span>
                <span class="badge badge-primary badge-pill animated flipInX">"{{ $user->email }}"</span>
            </h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
<div class="content">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.updateManageRestaurantOwnersRestaurants') }}" method="POST">
                    <legend class="font-weight-semibold text-uppercase font-size-sm">
                        <i class="icon-address-book mr-2"></i> Restaurant Owner's Restaurants
                    </legend>
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="form-group row form-group-feedback form-group-feedback-right">
                        @if(count($userRestaurants) === 0)
                        <div class="col-lg-9">
                            <p class="text-warning">{{ $user->name }} is not an owner of any restaurant.</p>
                        </div>
                        @else
                        <br>
                        <div class="col-lg-9">
                            <p>{{ $user->name }} is the owner of following restaurants:</p>
                            @foreach($userRestaurants as $ur)
                            <span class="badge badge-flat border-grey-800" style="font-size: 0.9rem;">{{ $ur->name }}</span>
                            @endforeach
                        </div>
                        @endif
                        <div class="col-lg-9 mt-3">
                            <label class="col-form-label">Select Restaurants:</label>
                            <div class="">
                                <select multiple="multiple" class="form-control select" data-fouc name="user_restaurants[]">
                                    @foreach($allRestaurants as $ar)
                                    <option value="{{ $ar->id }}" class="text-capitalize">{{ $ar->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                        UPDATE
                        <i class="icon-database-insert ml-1"></i>
                        </button>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('.select').select2({
            minimumResultsForSearch: Infinity,
            placeholder: 'Select restaurants that needs to be assigned',
        });
    });
</script>
@endsection