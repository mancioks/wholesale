@extends('layouts.admin')

@section('title')
    {{ __('BONUS calculator') }}
@endsection

@section('actions')
    {{ back_button(route('admin.tools.bonus_calculator')) }}
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form method="post" action="{{ route('admin.tools.bonus_calculator.submit') }}">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="date" class="form-label">{{ __('Date') }}</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="object" class="form-label">{{ __('Object') }}</label>
                        <input type="text" name="object" id="object" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="manager" class="form-label">{{ __('Manager') }}</label>
                        <select name="manager_id" id="manager" class="form-select">
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="installer" class="form-label">{{ __('Installer') }}</label>
                        <select name="installer_id" id="installer" class="form-select">
                            @foreach($installers as $installer)
                                <option value="{{ $installer->id }}">{{ $installer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="estimate-total" class="form-label">{{ __('Estimate total') }}</label>
                        <input type="number" name="estimate_total" id="estimate-total" class="form-control" step=".01" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="invoice-total" class="form-label">{{ __('Invoice total') }}</label>
                        <input type="number" name="invoice_total" id="invoice-total" class="form-control" step=".01" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="pricer" class="form-label">{{ __('Pricer') }}</label>
                        <select name="pricer_id" id="pricer" class="form-select">
                            <option value="" selected disabled>{{ __('Select pricer') }}</option>
                            @foreach($pricePeriods as $pricePeriod)
                                <option value="{{ $pricePeriod->id }}">{{ $pricePeriod->name }} ({{ $pricePeriod->from }} - {{ $pricePeriod->to }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="template" class="form-label">{{ __('Template') }}</label>
                        <select name="template_id" id="template" class="form-select">
                            <option value="" selected disabled>{{ __('Select template') }}</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                        <div class="alert alert-warning mt-3 mb-0">
                            {{ __('If template selected, pricer will be used from it')  }}
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
            </form>
        </div>
    </div>
@endsection
