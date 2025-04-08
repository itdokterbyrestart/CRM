@component('mail::message')
# Urenrapport {{ $user->name }}
## Datum: {{ $start_date }} tot {{ $end_date }}
## Weeknummer: {{ $week_number }}
@if (count($orderHours) > 0)
## Gewerkte uren: {{ $orderHours->sum('amount') }}
@component('mail::table')
    | Opdracht | Klant | Uren |
    |:-|:-|:-|
    @foreach ($orderHours as $orderHour)
        | {{ $orderHour->order->title }} | {{ $orderHour->order->customer->name }} | {{ $orderHour->amount }} |
    @endforeach
@endcomponent
@endif

@if (count($orderHours) > 0)
@component('mail::button', ['url' => route('profile.show', $user) ])
Uren bekijken
@endcomponent
@endif

@if (count($orderHours) === 0)
## Er zijn geen gewerkte uren of nieuwe uren aangemaakt deze week.
@endif

@endcomponent
