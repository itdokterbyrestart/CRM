@component('mail::message')

# De offerte {{ lcfirst($quote->title) }} van {{ $customer->name }} is {{ lcfirst($status->name) }}
## Datum: {{ $date }}
## ID: {{ $quote->id }}
## IP adres: {{ $clientIP }}

@endcomponent
