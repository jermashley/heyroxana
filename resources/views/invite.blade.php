@extends('layouts.app')

@section('title', 'Hey ' . $inviteeName . ' :)')

@section('content')
@php
$initialStep = 1;
if (session('submitted')) {
$initialStep = 4;
} elseif ($errors->has('date_type')) {
$initialStep = 2;
} elseif ($errors->has('scheduled_date') || $errors->has('message')) {
$initialStep = 3;
} elseif (old('date_type')) {
$initialStep = 4;
}
$eveningLabel = \Illuminate\Support\Carbon::createFromFormat('H:i', config('invite.evening_time', '19:00'))
->format('g:i A');
@endphp

<div class="flex justify-center pt-2 mb-8">
    <img src="{{ asset('images/hey_roxana.png') }}" alt="Hey Roxana" class="h-24 w-auto sm:h-28">
</div>

<div class="mx-auto flex w-full max-w-4xl flex-col gap-6" x-data="inviteFlow({
        initialStep: {{ $initialStep }},
        submitted: @js(session('submitted', false)),
        prefill: @js([
            'date_type' => old('date_type'),
            'scheduled_date' => old('scheduled_date'),
            'message' => old('message'),
        ]),
        dateTypes: @js($dateTypes),
        availableDates: @js($availableDates),
        eveningLabel: @js($eveningLabel),
        token: @js(request('t')),
    })" x-init="init()" x-effect="persist()" x-cloak>
    <header
        class="flex flex-col gap-4 rounded-3xl border-2 border-ink bg-slate px-6 py-6 shadow-[6px_6px_0_0_rgba(43,27,21,0.9)]">
        <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.2em] text-steel">
            <span class="rounded-full border-2 border-ink bg-ember/15 px-3 py-1 text-ink">Operation: Perfect Date</span>
            <span class="rounded-full border-2 border-ink bg-white/70 px-3 py-1">Location Select</span>
            <span class="rounded-full border-2 border-ink bg-white/70 px-3 py-1">Vibe: Sweet</span>
        </div>
        <div class="space-y-2">
            <p class="text-sm text-steel">Match made in a cozy cat cafe.</p>
            <h1 class="font-display text-3xl font-semibold text-ink sm:text-4xl">
                Hey {{ $inviteeName }}, will you be my sweet Valentine?
            </h1>
            <p class="text-sm text-steel">
                Pick a location, choose a day, and I'll bring the sweetness. No rush, just cute vibes.
            </p>
        </div>
        <div class="flex flex-wrap gap-2 text-xs">
            <span class="rounded-full border-2 border-ink bg-white/70 px-3 py-1">🐾 Tabby Pick</span>
            <span class="rounded-full border-2 border-ink bg-white/70 px-3 py-1">🌸 Flower Mood</span>
            <span class="rounded-full border-2 border-ink bg-white/70 px-3 py-1">☕ Coffee</span>
        </div>
    </header>

    <div class="rounded-3xl border-2 border-ink bg-slate px-6 py-6 shadow-[6px_6px_0_0_rgba(43,27,21,0.9)]">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-steel">
                <span class="font-semibold text-ember">Step <span x-text="step"></span></span>
                <span>/ 4</span>
            </div>
            <div class="flex items-center gap-2">
                <template x-for="dot in 4" :key="dot">
                    <div class="h-2.5 w-2.5 rounded-full transition" :class="step >= dot ? 'bg-ember' : 'bg-ink/15'">
                    </div>
                </template>
            </div>
        </div>

        @if ($errors->any())
        <div class="mt-4 rounded-2xl border-2 border-rose bg-rose/10 px-4 py-3 text-sm text-rose" role="alert">
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

            <div x-show="step === 1" x-transition.opacity.duration.300>
                <div class="space-y-4">
                    <h2 class="font-display text-2xl font-semibold">Ready for a sweet little date?</h2>
                    <p class="text-sm text-steel">
                        We’re doing a quick four-step run. You pick the location and the day — I handle the rest.
                    </p>
                </div>
                <div class="mt-6 flex items-center gap-3">
                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-full border-2 border-ink bg-ember px-5 py-2 text-sm font-semibold text-ink shadow-[4px_4px_0_0_rgba(43,27,21,0.9)] transition hover:-translate-y-0.5 hover:bg-copper focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="next()">
                        Start the purrade
                        <span aria-hidden="true">→</span>
                    </button>
                </div>
            </div>

            <div x-show="step === 2" x-transition.opacity.duration.300 class="space-y-6">
                <div class="space-y-2">
                    <h2 class="font-display text-2xl font-semibold">Pick the location</h2>
                    <p class="text-sm text-steel">Choose a spot like you’re picking the coziest cat nook.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach ($dateTypes as $key => $option)
                    <button type="button"
                        class="group flex h-full flex-col overflow-hidden rounded-2xl border-2 border-ink bg-sand text-left shadow-[4px_4px_0_0_rgba(43,27,21,0.9)] transition hover:-translate-y-1 hover:bg-white focus-visible:outline focus-visible:outline-8 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        :class="date_type === '{{ $key }}' ? 'ring-5 ring-ember' : ''" @click="selectType('{{ $key }}')"
                        :aria-pressed="date_type === '{{ $key }}'">
                        <img src="{{ $option['image'] }}" alt=""
                            class="h-56 w-full object-cover transition-all duration-200 ease-in-out"
                            :class="date_type !== '{{ $key }}' ? 'grayscale-75' : ''">
                        <div class="flex flex-1 flex-col gap-2 px-4 py-4">
                            <div
                                class="flex items-center justify-start space-x-1 text-[0.625rem] tracking-wide font-semibold text-steel">
                                <span class="rounded-full border-2 border-ink bg-white/70 px-2.5 py-1">{{
                                    $option['map'] }}</span>
                                <span class="rounded-full border-2 border-ink bg-white/70 px-2.5 py-1">{{
                                    $option['loadout'] }}</span>
                            </div>
                            <h3 class="text-base font-semibold text-ink">{{ $option['title'] }}</h3>
                            <p class="text-xs text-steel">{{ $option['description'] }}</p>
                            <p class="text-xs text-steel">{{ $option['label'] }}</p>
                        </div>
                    </button>
                    @endforeach
                </div>

                <div class="flex items-center justify-between">
                    <button type="button"
                        class="rounded-full border-2 border-ink bg-white/70 px-4 py-2 text-sm text-ink shadow-[3px_3px_0_0_rgba(43,27,21,0.85)] transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="back()">
                        Back
                    </button>
                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-full border-2 border-ink bg-ember px-5 py-2 text-sm font-semibold text-ink shadow-[4px_4px_0_0_rgba(43,27,21,0.9)] transition hover:-translate-y-0.5 hover:bg-copper focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="next()" :disabled="!date_type" :class="!date_type ? 'opacity-60 grayscale' : ''">
                        Lock this location
                        <span aria-hidden="true">→</span>
                    </button>
                </div>
            </div>

            <div x-show="step === 3" x-transition.opacity.duration.300 class="space-y-6">
                <div class="space-y-2">
                    <h2 class="font-display text-2xl font-semibold">Choose the day</h2>
                    <p class="text-sm text-steel">All options are in the evening around {{ $eveningLabel }}.</p>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-medium text-ink" for="scheduled_date">Available days</label>
                    <select id="scheduled_date" name="scheduled_date"
                        class="w-full rounded-2xl border-2 border-ink bg-sand px-4 py-3 text-sm text-ink shadow-[3px_3px_0_0_rgba(43,27,21,0.2)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        x-model="scheduled_date">
                        <option value="">Select a day</option>
                        @foreach ($availableDates as $availableDate)
                        @php
                        $displayDate = \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $availableDate)
                        ->format('l • F j, Y');
                        @endphp
                        <option value="{{ $availableDate }}">{{ $displayDate }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-steel">Each option locks in an evening time — no extra scheduling steps.</p>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-medium text-ink" for="message">Anything you want to add?</label>
                    <textarea id="message" name="message" rows="4"
                        class="w-full rounded-2xl border-2 border-ink bg-sand px-4 py-3 text-sm text-ink placeholder:text-steel shadow-[3px_3px_0_0_rgba(43,27,21,0.2)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        placeholder="Want to bring snacks? A playlist? Any special request?"
                        x-model="message"></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <button type="button"
                        class="rounded-full border-2 border-ink bg-white/70 px-4 py-2 text-sm text-ink shadow-[3px_3px_0_0_rgba(43,27,21,0.85)] transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="back()">
                        Back
                    </button>
                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-full border-2 border-ink bg-ember px-5 py-2 text-sm font-semibold text-ink shadow-[4px_4px_0_0_rgba(43,27,21,0.9)] transition hover:-translate-y-0.5 hover:bg-copper focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="next()" :disabled="!scheduled_date"
                        :class="!scheduled_date ? 'opacity-60 grayscale' : ''">
                        Review the plan
                        <span aria-hidden="true">→</span>
                    </button>
                </div>
            </div>

            <div x-show="step === 4" x-transition.opacity.duration.300 class="space-y-6">
                <div class="space-y-2">
                    <h2 class="font-display text-2xl font-semibold">Confirm the date</h2>
                    <p class="text-sm text-steel">Check the details and send it with a heart.</p>
                </div>

                <div
                    class="rounded-2xl border-2 border-ink bg-sand px-5 py-4 text-sm text-ink shadow-[3px_3px_0_0_rgba(43,27,21,0.2)]">
                    <div class="flex flex-col gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-steel">Location</p>
                            <p class="font-semibold" x-text="typeTitle()"></p>
                            <p class="text-xs text-steel" x-text="typeLabel()"></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-steel">Day</p>
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
                        class="rounded-full border-2 border-ink bg-white/70 px-4 py-2 text-sm text-ink shadow-[3px_3px_0_0_rgba(43,27,21,0.85)] transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        @click="back()">
                        Back
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full border-2 border-ink bg-ember px-5 py-2 text-sm font-semibold text-ink shadow-[4px_4px_0_0_rgba(43,27,21,0.9)] transition hover:-translate-y-0.5 hover:bg-copper focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember"
                        :disabled="submitted || !scheduled_date" :class="submitted ? 'opacity-60 grayscale' : ''">
                        Send the invite
                        <span aria-hidden="true">✔</span>
                    </button>
                </div>

                <div class="rounded-2xl border-2 border-moss bg-moss/10 px-4 py-3 text-sm text-moss" x-show="submitted"
                    role="status">
                    Invite sent! I’ll follow up with a heart and a happy meow.
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
            availableDates: config.availableDates || [],
            eveningLabel: config.eveningLabel || 'Evening',
            token: config.token || '',
            date_type: config.prefill?.date_type || '',
            scheduled_date: config.prefill?.scheduled_date || '',
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
                    this.scheduled_date = saved.scheduled_date || this.scheduled_date;
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
                    scheduled_date: this.scheduled_date,
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
                if (!this.scheduled_date) {
                    return 'No day selected';
                }
                const parsed = new Date(`${this.scheduled_date}T00:00:00`);
                if (Number.isNaN(parsed?.valueOf())) {
                    return this.scheduled_date;
                }
                return `${parsed.toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' })} • ${this.eveningLabel}`;
            },
        }));
    });
</script>
@endsection
