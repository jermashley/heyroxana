<?php

namespace App\Http\Controllers;

use App\Mail\InviteOpenedMail;
use App\Mail\InviteSubmissionMail;
use App\Models\InviteSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class InviteController extends Controller
{
    public function show(Request $request)
    {
        if (app()->environment('production')) {
            $mailTo = config('invite.mail_to_address');
            if ($mailTo) {
                Mail::to($mailTo)->send(new InviteOpenedMail(
                    token: (string) $request->input('t'),
                    ipAddress: $request->ip(),
                    userAgent: $request->userAgent(),
                    openedAt: now()->toDateTimeString(),
                ));
            }
        }

        return view('invite', [
            'inviteeName' => config('invite.invitee_name'),
            'dateTypes' => $this->dateTypes(),
            'availableDates' => $this->availableDates(),
        ]);
    }

    public function submit(Request $request)
    {
        $dateTypes = $this->dateTypes();
        $availableDates = $this->availableDates();

        $validated = $request->validate([
            't' => ['required', 'string'],
            'date_type' => ['required', Rule::in(array_keys($dateTypes))],
            'scheduled_date' => ['required', 'date_format:Y-m-d', Rule::in($availableDates)],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $scheduledAt = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['scheduled_date'].' '.config('invite.evening_time', '19:00'),
            config('app.timezone')
        );

        $submission = InviteSubmission::create([
            'token' => $validated['t'],
            'date_type' => $validated['date_type'],
            'date_type_label' => $dateTypes[$validated['date_type']]['label'],
            'scheduled_at' => $scheduledAt,
            'message' => $validated['message'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $mailTo = config('invite.mail_to_address');
        if ($mailTo) {
            Mail::to($mailTo)->send(new InviteSubmissionMail($submission));
        }

        return redirect()
            ->route('invite.success', [
                't' => $validated['t'],
                'submission' => $submission->id,
            ]);
    }

    public function success(Request $request, InviteSubmission $submission)
    {
        if ($submission->token !== $request->input('t')) {
            abort(404);
        }

        return view('invite-success', [
            'inviteeName' => config('invite.invitee_name'),
            'submission' => $submission,
            'eveningLabel' => Carbon::createFromFormat('H:i', config('invite.evening_time', '19:00'))
                ->format('g:i A'),
        ]);
    }

    private function dateTypes(): array
    {
        return [
            'drive' => [
                'title' => 'Free Roam Drive',
                'label' => 'Coffee and driving with no set destination. We can drive past the same gas station a dozen times :)',
                'description' => 'No timer, just vibes and a looping patrol route.',
                'image' => '/images/drive.png',
                'map' => '📍 Open Road',
                'loadout' => '🐾 Tabby Tail',
            ],
            'barton' => [
                'title' => 'Barton Springs Walk',
                'label' => 'Coffee and a walk around Barton Springs. Remember the bridge you dared me to jump into the creek from?',
                'description' => 'Skyline views, railroad walks, and possibly calling your bluff to jump into the water.',
                'image' => '/images/barton.png',
                'map' => '📍 Barton Springs',
                'loadout' => '🌸 Flower Bell',
            ],
            'san-marcos' => [
                'title' => 'San Marcos River',
                'label' => 'Coffee and a walk around San Marcos River',
                'description' => 'That first deep talk, the moment I felt like I could see you.',
                'image' => '/images/san-marcos-river.png',
                'map' => '📍 River Loop',
                'loadout' => '💕 Heart Collar',
            ],
        ];
    }

    private function availableDates(): array
    {
        return array_values(array_filter(config('invite.available_dates', [])));
    }
}
