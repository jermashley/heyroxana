@extends('layouts.app')

@section('title', 'Invite')

@section('content')
@php
$initialStep = 1;
if (session('submitted')) {
$initialStep = 4;
} elseif ($errors->has('date_type')) {
$initialStep = 2;
} elseif ($errors->has('scheduled_at') || $errors->has('message')) {
$initialStep = 3;
} elseif (old('date_type')) {
$initialStep = 4;
}
@endphp

<div class="mx-auto flex w-full max-w-3xl flex-col gap-6" x-data="inviteFlow({
        initialStep: {{ $initialStep }},
        submitted: @js(session('submitted', false)),
        prefill: @js([
            'date_type' => old('date_type'),
            'scheduled_at' => old('scheduled_at'),
            'message' => old('message'),
        ]),
        dateTypes: @js($dateTypes),
        unavailable: @js($unavailable),
        token: @js(request('t')),
    })" x-init="init()" x-effect="persist()" x-cloak>
    <header
        class="flex flex-col gap-4 rounded-3xl border border-white/10 bg-slate/70 px-6 py-6 shadow-[0_30px_80px_-50px_rgba(0,0,0,0.9)] backdrop-blur">
        <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.2em] text-steel">
            <span class="rounded-full border border-ember/40 bg-ember/10 px-3 py-1 text-ember">Operation: Coffee</span>
            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Map Select</span>
            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Vibe: Cozy</span>
        </div>
        <div class="space-y-2">
            <p class="text-sm text-steel">Match found.</p>
            <h1 class="font-display text-3xl font-semibold text-sand sm:text-4xl">
                Hey {{ $inviteeName }} — would you go on a mission to be my valentine?
            </h1>
            <p class="text-sm text-steel">
                Pick a map, lock in a time, and I'll take the next round. No rush, no sweats.
            </p>
        </div>
        <div class="flex flex-wrap gap-2 text-xs">
            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">🎯 Map Pick</span>
            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">🕰️ Round Time</span>
            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">☕ Coffee</span>
        </div>
    </header>

    <div
        class="rounded-3xl border border-white/10 bg-slate/70 px-6 py-6 shadow-[0_30px_80px_-50px_rgba(0,0,0,0.9)] backdrop-blur">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-steel">
                <span class="font-semibold text-ember">Step <span x-text="step"></span></span>
                <span>/ 4</span>
            </div>
            <div class="flex items-center gap-2">
                <template x-for="dot in 4" :key="dot">
                    <div class="h-2.5 w-2.5 rounded-full transition" :class="step >= dot ? 'bg-ember' : 'bg-white/10'">
                    </div>
                </template>
            </div>
        </div>

        @if ($errors->any())
        <div class="mt-4 rounded-2xl border border-rose/40 bg-rose/10 px-4 py-3 text-sm text-rose" role="alert">
            <p class="font-semibold">Quick fix needed:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="mt-6 space-y-6" method="POST" action="{{ route('invite.submit', ['t' => request('t')]) }}">
            @csrf
            <input type="hidden" name="t" :value="token">
            <input type="hidden" name="date_type" x-model="date_type">
            <input type="hidden" name="scheduled_at" x-model="scheduled_at">
            <input type="hidden" name="message" x-model="message">

            <div x-show="step === 1" x-transition.opacity.duration.300>
                <div class="space-y-4">
                    <h2 class="font-display text-2xl font-semibold">Ready for a new mission?</h2>
                    <p class="text-sm text-steel">
                        We’re doing a quick four-step run. You pick the map and the time — I handle the rest.
                    </p>
                </div>
                <div class="mt-6 flex items-center gap-3">
                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-full bg-ember px-5 py-2 text-sm font-semibold text-ink shadow-lg shadow-ember/30 transition hover:-translate-y-0.5 hover:bg-copper focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="next()">
                        Start the run
                        <span aria-hidden="true">→</span>
                    </button>
                </div>
            </div>

            <div x-show="step === 2" x-transition.opacity.duration.300 class="space-y-6">
                <div class="space-y-2">
                    <h2 class="font-display text-2xl font-semibold">Pick the map</h2>
                    <p class="text-sm text-steel">Select a date type like you’re choosing the next map in queue.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach ($dateTypes as $key => $option)
                    <button type="button"
                        class="group flex h-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-ink/60 text-left transition hover:-translate-y-1 hover:border-ember/60 hover:bg-ink/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        :class="date_type === '{{ $key }}' ? 'border-ember/80 bg-ink/90 ring-2 ring-ember/30' : ''"
                        @click="selectType('{{ $key }}')" :aria-pressed="date_type === '{{ $key }}'">
                        <img src="{{ $option['image'] }}" alt="" class="h-28 w-full object-cover">
                        <div class="flex flex-1 flex-col gap-2 px-4 py-4">
                            <div class="flex items-center justify-between text-xs text-steel">
                                <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1">{{
                                    $option['map'] }}</span>
                                <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1">{{
                                    $option['loadout'] }}</span>
                            </div>
                            <h3 class="text-base font-semibold text-sand">{{ $option['title'] }}</h3>
                            <p class="text-xs text-steel">{{ $option['description'] }}</p>
                            <p class="text-xs text-sand/80">{{ $option['label'] }}</p>
                        </div>
                    </button>
                    @endforeach
                </div>

                <div class="flex items-center justify-between">
                    <button type="button"
                        class="rounded-full border border-white/10 px-4 py-2 text-sm text-steel transition hover:border-white/30 hover:text-sand focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="back()">
                        Back
                    </button>
                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-full bg-ember px-5 py-2 text-sm font-semibold text-ink shadow-lg shadow-ember/30 transition hover:-translate-y-0.5 hover:bg-copper focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="next()" :disabled="!date_type" :class="!date_type ? 'opacity-60 grayscale' : ''">
                        Lock this map
                        <span aria-hidden="true">→</span>
                    </button>
                </div>
            </div>

            <div x-show="step === 3" x-transition.opacity.duration.300 class="space-y-6">
                <div class="space-y-2">
                    <h2 class="font-display text-2xl font-semibold">Choose date + time</h2>
                    <p class="text-sm text-steel">Pick a slot or leave it loose and we’ll coordinate later.</p>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-medium text-sand" for="scheduled_at">Time slot</label>
                    <input id="scheduled_at" type="datetime-local"
                        class="w-full rounded-2xl border border-white/10 bg-ink/60 px-4 py-3 text-sm text-sand placeholder:text-steel shadow-inner shadow-black/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        x-model="scheduled_at" @change="touchDate()">
                    <p class="text-xs text-steel">Optional: you can leave this empty and we’ll pick a time over coffee.
                    </p>
                    <p class="text-xs text-rose" x-show="scheduled_at && isUnavailable(scheduled_at)"
                        aria-live="polite">
                        That slot is blocked — pick another.
                    </p>
                    @if (count($unavailable))
                    <div class="flex flex-wrap gap-2 text-xs text-steel">
                        <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1">Unavailable:</span>
                        @foreach ($unavailable as $blocked)
                        <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1">{{ $blocked }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-medium text-sand" for="message">Anything you want to add?</label>
                    <textarea id="message" rows="4"
                        class="w-full rounded-2xl border border-white/10 bg-ink/60 px-4 py-3 text-sm text-sand placeholder:text-steel shadow-inner shadow-black/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        placeholder="Want to bring snacks? A playlist? Any special request?"
                        x-model="message"></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <button type="button"
                        class="rounded-full border border-white/10 px-4 py-2 text-sm text-steel transition hover:border-white/30 hover:text-sand focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="back()">
                        Back
                    </button>
                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-full bg-ember px-5 py-2 text-sm font-semibold text-ink shadow-lg shadow-ember/30 transition hover:-translate-y-0.5 hover:bg-copper focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="next()">
                        Review loadout
                        <span aria-hidden="true">→</span>
                    </button>
                </div>
            </div>

            <div x-show="step === 4" x-transition.opacity.duration.300 class="space-y-6">
                <div class="space-y-2">
                    <h2 class="font-display text-2xl font-semibold">Confirm the plan</h2>
                    <p class="text-sm text-steel">Check the details and send it.</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-ink/60 px-5 py-4 text-sm text-sand">
                    <div class="flex flex-col gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-steel">Map</p>
                            <p class="font-semibold" x-text="typeTitle()"></p>
                            <p class="text-xs text-steel" x-text="typeLabel()"></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-steel">Time</p>
                            <p class="font-semibold" x-text="formattedDate()"></p>
                        </div>
                        <div x-show="message">
                            <p class="text-xs uppercase tracking-[0.2em] text-steel">Note</p>
                            <p class="font-semibold" x-text="message"></p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="button"
                        class="rounded-full border border-white/10 px-4 py-2 text-sm text-steel transition hover:border-white/30 hover:text-sand focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="back()">
                        Back
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-ember px-5 py-2 text-sm font-semibold text-ink shadow-lg shadow-ember/30 transition hover:-translate-y-0.5 hover:bg-copper focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        :disabled="submitted || (scheduled_at && isUnavailable(scheduled_at))"
                        :class="submitted ? 'opacity-60 grayscale' : ''">
                        Send invite
                        <span aria-hidden="true">✔</span>
                    </button>
                </div>

                <div class="rounded-2xl border border-moss/40 bg-moss/10 px-4 py-3 text-sm text-moss" x-show="submitted"
                    role="status">
                    Invite sent! I’ll follow up with a coffee emoji and a smile.
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('inviteFlow', (config) => ({
            step: config.initialStep || 1,
            initialStep: config.initialStep || 1,
            submitted: config.submitted || false,
            dateTypes: config.dateTypes || {},
            unavailable: config.unavailable || [],
            token: config.token || '',
            date_type: config.prefill?.date_type || '',
            scheduled_at: config.prefill?.scheduled_at || '',
            message: config.prefill?.message || '',
            init() {
                if (this.submitted) {
                    this.clearSaved();
                    this.step = 4;
                    return;
                }

                const saved = this.restore();
                if (saved) {
                    this.step = saved.step || this.step;
                    this.date_type = saved.date_type || this.date_type;
                    this.scheduled_at = saved.scheduled_at || this.scheduled_at;
                    this.message = saved.message || this.message;
                } else {
                    this.step = this.initialStep;
                }
            },
            persist() {
                if (this.submitted || !window.sessionStorage) {
                    return;
                }
                const payload = {
                    step: this.step,
                    date_type: this.date_type,
                    scheduled_at: this.scheduled_at,
                    message: this.message,
                };
                window.sessionStorage.setItem('inviteState', JSON.stringify(payload));
            },
            restore() {
                if (!window.sessionStorage) {
                    return null;
                }
                const raw = window.sessionStorage.getItem('inviteState');
                if (!raw) {
                    return null;
                }
                try {
                    return JSON.parse(raw);
                } catch (error) {
                    return null;
                }
            },
            clearSaved() {
                if (window.sessionStorage) {
                    window.sessionStorage.removeItem('inviteState');
                }
            },
            next() {
                if (this.step < 4) {
                    this.step += 1;
                }
            },
            back() {
                if (this.step > 1) {
                    this.step -= 1;
                }
            },
            selectType(key) {
                this.date_type = key;
            },
            typeTitle() {
                return this.dateTypes?.[this.date_type]?.title || '—';
            },
            typeLabel() {
                return this.dateTypes?.[this.date_type]?.label || '—';
            },
            formattedDate() {
                if (!this.scheduled_at) {
                    return 'No exact time yet';
                }
                const parsed = new Date(this.scheduled_at);
                if (Number.isNaN(parsed?.valueOf())) {
                    return this.scheduled_at;
                }
                return parsed.toLocaleString();
            },
            isUnavailable(value) {
                return this.unavailable.includes(value);
            },
            touchDate() {
                if (!this.scheduled_at) {
                    return;
                }
                if (this.isUnavailable(this.scheduled_at)) {
                    this.step = 3;
                }
            },
        }));
    });
</script>
@endsection
