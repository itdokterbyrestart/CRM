@component('mail::message')
# Nieuwe shows binnen 21 dagen!
## Er zijn automatisch uitnodigingen voor een afspraak verstuurd.

@component('mail::table')
    | Klant | Opdracht | Email |
    |:-|:-|:-|
    @foreach ($orders as $order)
        | {{ $order->customer->name }} | {{ $order->title }} | {{ $order->customer->email ?? 'info@deitdokter.nl' }} |
    @endforeach
@endcomponent
@endcomponent
