@component('mail::message')
## <strong>Feestdatum:</strong> {{ $date }}
## <strong>Opdracht:</strong> {{ $order->title }}
## <strong>Naam:</strong> {{ $customer->name }}
## <strong>Telefoonnummer:</strong> {{ $customer->phone }}
## <strong>E-mail:</strong> {{ $customer->email }}
@endcomponent
