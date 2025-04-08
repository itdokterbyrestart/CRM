@component('mail::message')
# Shows evalueren!
## Er staan shows klaar die geëvalueerd moeten worden.

@component('mail::table')
    | Klant | Opdracht |
    |:-|:-|
    @foreach ($orders as $order)
        | {{ $order->customer->name }} | {{ $order->title }} |
    @endforeach
@endcomponent
@endcomponent
