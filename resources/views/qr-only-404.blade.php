@extends('layouts.app')

@section('title', 'Link Locked')

@section('content')
<div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
    <div class="flex justify-center pt-2">
        <img src="{{ asset('images/hey_roxana.png') }}" alt="Hey Roxana" class="h-24 w-auto sm:h-28">
    </div>
    <div class="rounded-3xl border-2 border-ink bg-slate px-6 py-8 text-center shadow-[6px_6px_0_0_rgba(43,27,21,0.9)]">
        <div
            class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full border-2 border-ink bg-ember/20 text-2xl">
            🐱</div>
        <h1 class="font-display text-3xl font-semibold text-ink">This kitten is QR-only</h1>
        <p class="mt-3 text-sm text-steel">
            This link is locked. Please open the invite directly from the QR code so the token can verify it's really
            you.
        </p>
        <p class="mt-4 text-xs text-steel">Hint: the QR code is on the card in the box.</p>
    </div>
</div>
@endsection
