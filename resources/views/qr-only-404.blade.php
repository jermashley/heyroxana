@extends('layouts.app')

@section('title', 'Link Locked')

@section('content')
<div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
    <div
        class="rounded-3xl border border-white/10 bg-slate/70 px-6 py-8 text-center shadow-[0_30px_80px_-50px_rgba(0,0,0,0.9)] backdrop-blur">
        <div
            class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-ember/40 bg-ember/10 text-2xl">
            🗺️</div>
        <h1 class="font-display text-3xl font-semibold text-sand">This map is QR-only</h1>
        <p class="mt-3 text-sm text-steel">
            That link is locked. Please open the invite directly from the QR code so the token can verify it’s really
            you.
        </p>
        <p class="mt-4 text-xs text-steel">Hint: the QR code is in the box :).</p>
    </div>
</div>
@endsection
