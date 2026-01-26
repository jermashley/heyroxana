<?php

namespace App\Http\Controllers;

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
        return view('invite', [
            'inviteeName' => config('invite.invitee_name'),
            'dateTypes' => $this->dateTypes(),
            'unavailable' => config('invite.unavailable_datetimes', []),
        ]);
    }

    public function submit(Request $request)
    {
        $dateTypes = $this->dateTypes();

        $validated = $request->validate([
            't' => ['required', 'string'],
            'date_type' => ['required', Rule::in(array_keys($dateTypes))],
            'scheduled_at' => ['nullable', 'date_format:Y-m-d\\TH:i'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        if (! empty($validated['scheduled_at'])) {
            $unavailable = config('invite.unavailable_datetimes', []);

            if (in_array($validated['scheduled_at'], $unavailable, true)) {
                return back()
                    ->withErrors(['scheduled_at' => 'That time is unavailable. Pick another slot.'])
                    ->withInput();
            }
        }

        $scheduledAt = $validated['scheduled_at']
            ? Carbon::createFromFormat('Y-m-d\\TH:i', $validated['scheduled_at'], config('app.timezone'))
            : null;

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
            ->route('invite.show', ['t' => $validated['t']])
            ->with('submitted', true);
    }

    private function dateTypes(): array
    {
        return [
            'drive' => [
                'title' => 'Free Roam Drive',
                'label' => 'Coffee + driving with no set destination (we can drive past the same gas station a dozen times)',
                'description' => 'No timer, just vibes and a looping patrol route.',
                'image' => '/images/date-drive.svg',
                'map' => '🗺️ Aimless',
                'loadout' => '🚗 Automobile',
            ],
            'barton' => [
                'title' => 'Barton Springs Walk',
                'label' => 'Coffee + walking around Barton Springs (remember the bridge you dared me to jump into the creek from)',
                'description' => 'Bridge drops, water sparkle, and a little courage buff.',
                'image' => '/images/date-barton.svg',
                'map' => '🗺️ Barton Springs',
                'loadout' => '🚶🏼‍➡️ Walk',
            ],
            'pflugerville' => [
                'title' => 'San Marcos River',
                'label' => 'Coffee + walk around San Marcos River (that first walk we took together)',
                'description' => 'That first deep talk, the moment I was sure.',
                'image' => '/images/date-pflugerville.svg',
                'map' => '🗺️ River Loop',
                'loadout' => '🚶🏼‍➡️ Walk',
            ],
        ];
    }
}
