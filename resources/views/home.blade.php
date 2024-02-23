@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">

                        @if ($message = session('success'))
                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                    aria-label="Info:">
                                    <use xlink:href="#info-fill" />
                                </svg>
                                <div>
                                    {{ $message }}
                                </div>
                            </div>
                        @endif
                        <a href="{{ route('index.customer') }}" class="btn btn-dark mb-3"> Masuk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
