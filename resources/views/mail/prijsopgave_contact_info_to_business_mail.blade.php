@component('mail::message')
# Er is een nieuwe onafgeronde prijsopgave gedaan
## Contact info
### Naam: {{ $prijsopgave->name }}
### Email: {{ $prijsopgave->email }}
### Telefoonnummer: {{ $prijsopgave->phone }}

## Feest info
### Type: {{ !empty($prijsopgave->party_type) ? $prijsopgave->party_type : 'Onbekend' }}
### Datum: {{ date('l j F Y', strtotime($prijsopgave->party_date)) }}
### Tijden: {{ ($prijsopgave->start_time != null AND $prijsopgave->end_time != null) ? (substr($prijsopgave->start_time, 0, 5) . ' - ' . substr($prijsopgave->end_time, 0, 5)) : 'Onbekend' }}
### Locatie: {{ !empty($prijsopgave->location) ? $prijsopgave->location : 'Onbekend' }}
### Aantal gasten: {{ !empty($prijsopgave->guest_amount) ? $prijsopgave->guest_amount : 'Onbekend' }}
### Show: {{ !empty($prijsopgave->show_type) ? $prijsopgave->show_type : 'Onbekend' }}

### <a href="{{ route('quote.customer.prijsopgave_unsubscribe', $prijsopgave->id) }}" target="_blank">Prijsopgave verwijderen</a>
@endcomponent
