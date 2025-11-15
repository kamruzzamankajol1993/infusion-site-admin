@extends('admin.master.master')
@section('title', 'Payment Gateway Settings')
@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="fw-bold">Payment Gateway Settings</h2>
            <p class="text-muted">Configure the credentials for your online payment providers.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('settings.payment.gateway.update') }}" method="POST">
                    @csrf
                    <ul class="nav nav-tabs mb-4" id="gatewayTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="sslcz-tab" data-bs-toggle="tab" data-bs-target="#sslcz" type="button" role="tab" aria-controls="sslcz" aria-selected="true">SSLCZ</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="amarpay-tab" data-bs-toggle="tab" data-bs-target="#amarpay" type="button" role="tab" aria-controls="amarpay" aria-selected="false">Amarpay</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bkash-tab" data-bs-toggle="tab" data-bs-target="#bkash" type="button" role="tab" aria-controls="bkash" aria-selected="false">bKash</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="gatewayTabsContent">
                        <div class="tab-pane fade show active" id="sslcz" role="tabpanel" aria-labelledby="sslcz-tab">
                            <h5 class="mb-3">SSLCommerz Settings</h5>
                            <div class="mb-3">
                                <label for="sslcz_store_id" class="form-label">Store ID</label>
                                <input type="text" class="form-control" id="sslcz_store_id" name="SSLCZ_STORE_ID" value="{{ old('SSLCZ_STORE_ID', $settings['SSLCZ_STORE_ID'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="sslcz_store_password" class="form-label">Store Password</label>
                                <input type="password" class="form-control" id="sslcz_store_password" name="SSLCZ_STORE_PASSWORD" value="{{ old('SSLCZ_STORE_PASSWORD', $settings['SSLCZ_STORE_PASSWORD'] ?? '') }}">
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input type="hidden" name="SSLCZ_TESTMODE" value="false">
                                <input class="form-check-input" type="checkbox" name="SSLCZ_TESTMODE" value="true" id="sslcz_testmode" @checked(old('SSLCZ_TESTMODE', $settings['SSLCZ_TESTMODE'] ?? 'false') === 'true')>
                                <label class="form-check-label" for="sslcz_testmode">Enable Test Mode (Sandbox)</label>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="amarpay" role="tabpanel" aria-labelledby="amarpay-tab">
                            <h5 class="mb-3">Amarpay Settings</h5>
                            <div class="mb-3">
                                <label for="amarpay_store_id" class="form-label">Store ID</label>
                                <input type="text" class="form-control" id="amarpay_store_id" name="AMARPAY_STORE_ID" value="{{ old('AMARPAY_STORE_ID', $settings['AMARPAY_STORE_ID'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="amarpay_signature_key" class="form-label">Signature Key</label>
                                <input type="password" class="form-control" id="amarpay_signature_key" name="AMARPAY_SIGNATURE_KEY" value="{{ old('AMARPAY_SIGNATURE_KEY', $settings['AMARPAY_SIGNATURE_KEY'] ?? '') }}">
                            </div>
                             <div class="form-check form-switch mb-3">
                                <input type="hidden" name="AMARPAY_SANDBOX" value="false">
                                <input class="form-check-input" type="checkbox" name="AMARPAY_SANDBOX" value="true" id="amarpay_sandbox" @checked(old('AMARPAY_SANDBOX', $settings['AMARPAY_SANDBOX'] ?? 'false') === 'true')>
                                <label class="form-check-label" for="amarpay_sandbox">Enable Sandbox Mode</label>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="bkash" role="tabpanel" aria-labelledby="bkash-tab">
                            <h5 class="mb-3">bKash Settings</h5>
                             <div class="mb-3">
                                <label for="bkash_app_key" class="form-label">App Key</label>
                                <input type="text" class="form-control" id="bkash_app_key" name="BKASH_APP_KEY" value="{{ old('BKASH_APP_KEY', $settings['BKASH_APP_KEY'] ?? '') }}">
                            </div>
                             <div class="mb-3">
                                <label for="bkash_app_secret" class="form-label">App Secret</label>
                                <input type="password" class="form-control" id="bkash_app_secret" name="BKASH_APP_SECRET" value="{{ old('BKASH_APP_SECRET', $settings['BKASH_APP_SECRET'] ?? '') }}">
                            </div>
                             <div class="mb-3">
                                <label for="bkash_username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="bkash_username" name="BKASH_USERNAME" value="{{ old('BKASH_USERNAME', $settings['BKASH_USERNAME'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="bkash_password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="bkash_password" name="BKASH_PASSWORD" value="{{ old('BKASH_PASSWORD', $settings['BKASH_PASSWORD'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="bkash_callback_url" class="form-label">Callback URL</label>
                                <input type="text" class="form-control" id="bkash_callback_url" name="BKASH_CALLBACK_URL" value="{{ old('BKASH_CALLBACK_URL', $settings['BKASH_CALLBACK_URL'] ?? '') }}">
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input type="hidden" name="BKASH_SANDBOX" value="false">
                                <input class="form-check-input" type="checkbox" name="BKASH_SANDBOX" value="true" id="bkash_sandbox" @checked(old('BKASH_SANDBOX', $settings['BKASH_SANDBOX'] ?? 'false') === 'true')>
                                <label class="form-check-label" for="bkash_sandbox">Enable Sandbox Mode</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection