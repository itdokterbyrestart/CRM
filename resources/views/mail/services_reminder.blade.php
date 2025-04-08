@component('mail::message')
# Nieuwe service opdrachten!
## Er staan nieuwe opdrachten klaar voor de services van deze maand.
### Maand: {{ $month }}

@component('mail::table')
    | Klant | Service |
    |:-|:-|
    @php $customerservices = \App\Models\CustomerService::with(['customer','service'])->where('month', Carbon\Carbon::now()->month)->get() @endphp
    @foreach ($customerservices as $customerservice)
        | {{ $customerservice->customer->name }} | {{ $customerservice->service->name }} |
    @endforeach
@endcomponent
@endcomponent
