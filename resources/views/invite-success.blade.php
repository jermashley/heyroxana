@extends('layouts.app')

@section('title', 'Invite Sent')

@section('content')
<div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
    <div class="flex justify-center pt-2">
        <img src="{{ asset('images/hey_roxana.png') }}" alt="Hey Roxana" class="h-24 w-auto sm:h-28">
    </div>

    <div class="rounded-3xl border-2 border-ink bg-slate px-6 py-8 text-center shadow-[6px_6px_0_0_rgba(43,27,21,0.9)]">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full border-2 border-ink bg-ember/20 text-2xl">
            💌
        </div>
        <h1 class="font-display text-3xl font-semibold text-ink">Invite sent!</h1>
        <p class="mt-3 text-sm text-steel">
            Thanks, {{ $inviteeName }}. Your sweet pick is locked in.
        </p>

        <div class="mt-6 rounded-2xl border-2 border-ink bg-sand px-5 py-4 text-left text-sm text-ink shadow-[3px_3px_0_0_rgba(43,27,21,0.2)]">
            <div class="space-y-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-steel">Location</p>
                    <p class="font-semibold">{{ $submission->date_type_label }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-steel">Day</p>
                    <p class="font-semibold">
                        {{ $submission->scheduled_at?->format('l • F j, Y') ?? 'To be decided' }}
                        @if ($submission->scheduled_at)
                            • {{ $submission->scheduled_at->format('g:i A') }}
                        @else
                            • {{ $eveningLabel }}
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-steel">Note</p>
                    <p class="font-semibold">{{ $submission->message ?: 'No extra notes — just good vibes.' }}</p>
                </div>
            </div>
        </div>

        <p class="mt-6 text-xs text-steel">If anything changes, just message me. 🐾</p>
    </div>
</div>
@endsection
