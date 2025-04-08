@component('mail::message')
# Er is een nieuwe prijsopgave gedaan
## Contact info
### Naam: {{ $customer->name }}
### Email: {{ $customer->email }}
### Telefoonnummer: {{ $customer->phone }}

## Feest info
### Type: {{ $quote->party_type }}
### Datum: {{ date('l j F Y', strtotime($quote->party_date)) }}
### Tijden: {{ substr($quote->start_time, 0, 5) }} - {{ substr($quote->end_time, 0, 5) }}
### Locatie: {{ $quote->location }}
### Aantal gasten: {{ $quote->guest_amount }}
@endcomponent
