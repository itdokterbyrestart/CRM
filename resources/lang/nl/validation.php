<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Je moet akkoord gaan met de :attribute.',
    'active_url' => 'De :attribute is geen geldige URL.',
    'after' => 'De :attribute moet een datum zijn na :date.',
    'after_or_equal' => 'De :attribute moet een datum na of gelijk aan :date zijn.',
    'alpha' => 'De :attribute mag alleen letters bevatten.',
    'alpha_dash' => 'De :attribute mag alleen letters, nummers, strepen of underscores bevatten.',
    'alpha_num' => 'De :attribute mag alleen letters en nummers bevatten.',
    'array' => 'De :attribute moet een array zijn.',
    'before' => 'De :attribute moet een datum voor :date zijn.',
    'before_or_equal' => 'De :attribute moet een datum voor of gelijk aan :date zijn.',
    'between' => [
        'numeric' => 'De :attribute moet tussen :min en :max liggen.',
        'file' => 'Het :attribute moet tussen :min en :max kilobytes zijn.',
        'string' => 'De :attribute moet minimaal :min en maximaal :max karakters bevatten.',
        'array' => 'De :attribute moet minimaal :min en maximaal :max items bevattem.',
    ],
    'boolean' => 'De :attribute moet waar of niet waar zijn.',
    'confirmed' => 'De :attribute confirmatie is niet gelijk.',
    'current_password' => 'Het wachtwoord is onjuist.',
    'date' => 'De :attribute is geen geldige datum.',
    'date_equals' => 'De :attribute moet gelijk zijn aan :date.',
    'date_format' => 'De :attribute is niet gelijk aan het type :format.',
    'different' => 'De :attribute en :other moeten verschillend zijn.',
    'digits' => 'De :attribute moet :digits getallen zijn.',
    'digits_between' => 'De :attribute moet tussen de :min en :max getallen bevatten.',
    'dimensions' => 'De :attribute heeft ongeldige afbeelding dimensies.',
    'distinct' => 'De :attribute heeft een dubbele waarde.',
    'email' => 'Het :attribute moet een geldig e-mailadres bevatten.',
    'ends_with' => 'De :attribute moet eindigen met een van de volgende waarden: :values.',
    'exists' => 'Het geselecteerde :attribute is ongeldig.',
    'file' => 'De :attribute moet een bestand zijn.',
    'filled' => 'Het :attribute veld moet een waarde hebben.',
    'gt' => [
        'numeric' => 'De :attribute moet groter zijn dan :value.',
        'file' => 'Het :attribute moet groter zijn dan :value kilobytes.',
        'string' => 'De :attribute moet groter zijn dan :value karakters.',
        'array' => 'De :attribute moet meer dan :value items bevatten.',
    ],
    'gte' => [
        'numeric' => 'De :attribute moet groter zijn dan :value.',
        'file' => 'Het :attribute moet groter zijn dan :value kilobytes.',
        'string' => 'De :attribute moet groter zijn dan :value karakters.',
        'array' => 'De :attribute moet meer dan :value items bevatten.',
    ],
    'image' => 'De :attribute moet een afbeelding zijn.',
    'in' => 'Het geselecteerde :attribute is ongeldig.',
    'in_array' => 'Het :attribute veld bestaat niet in :other.',
    'integer' => 'Het :attribute moet een geheel getal zijn.',
    'ip' => 'Het :attribute moet een geldig IP adres bevatten.',
    'ipv4' => 'Het :attribute moet een geldig IPv4 adres zijn.',
    'ipv6' => 'Het :attribute moet een geldig IPv6 adres zijn.',
    'json' => 'Het :attribute moet een geldige JSON string zijn.',
    'lt' => [
        'numeric' => 'De :attribute moet groter zijn dan :value.',
        'file' => 'Het :attribute moet groter zijn dan :value kilobytes.',
        'string' => 'De :attribute moet groter zijn dan :value karakters.',
        'array' => 'De :attribute moet meer dan :value items bevatten.',
    ],
    'lte' => [
        'numeric' => 'De :attribute moet groter zijn dan :value.',
        'file' => 'Het :attribute moet groter zijn dan :value kilobytes.',
        'string' => 'De :attribute moet groter zijn dan :value karakters.',
        'array' => 'De :attribute moet meer dan :value items bevatten.',
    ],
    'max' => [
        'numeric' => 'De :attribute mag niet groter zijn dan :max.',
        'file' => 'Het :attribute mag niet groter zijn dan :max kilobytes.',
        'string' => 'De :attribute mag niet groter zijn dan :max karakters.',
        'array' => 'De :attribute mag niet meer dan :max items bevatten.',
    ],
    'mimes' => 'De :attribute moet een bestand zijn met type: :values.',
    'mimetypes' => 'De :attribute moet een bestand zijn met type: :values.',
    'min' => [
        'numeric' => 'De :attribute moet minimaal :min zijn.',
        'file' => 'Het :attribute moet minimaal :min kilobytes zijn.',
        'string' => 'Het :attribute moet minimaal :min karakters bevatten.',
        'array' => 'De :attribute moet minimaal :min items bevatten.',
    ],
    'multiple_of' => 'De :attribute moet meerdere items bevatten met een waarde van :value.',
    'not_in' => 'De geselecteerde :attribute is ongeldig.',
    'not_regex' => 'De :attribute is ongeldig.',
    'numeric' => 'De :attribute moet een nummer zijn.',
    'password' => 'Het wachtwoord is onjuist.',
    'present' => 'De :attribute moet aanwezig zijn.',
    'regex' => 'De :attribute is ongeldig.',
    'required' => 'Het veld :attribute is verplicht.',
    'required_if' => 'Het veld :attribute is verplicht wanneer :other gelijk is aan :value.',
    'required_unless' => 'Het veld :attribute is verplicht behalve wanneer :other gelijk is aan: :values.',
    'required_with' => 'het veld :attribute is verplicht wanneer :values aanwezig is.',
    'required_with_all' => 'Het veld :attribute is verplicht wanneer :values aanwezig zijn.',
    'required_without' => 'Het veld :attribute is verplicht wanneer :values niet aanwezig is.',
    'required_without_all' => 'Het veld :attribute is verplicht wanneer geen van de volgende waardes aanwezig is: :values.',
    'prohibited' => 'Het veld :attribute is verboden.',
    'prohibited_if' => 'Het veld :attribute is verboden wanneer :other gelijk is aan :value.',
    'prohibited_unless' => 'Het veld :attribute is verboden wanneer :other gelijk is aan :values.',
    'same' => 'De :attribute en :other moeten gelijk zijn.',
    'size' => [
        'numeric' => 'De :attribute moet :size zijn.',
        'file' => 'Het :attribute moet een groote van :size kilobytes hebben.',
        'string' => 'De :attribute moet :size karakters zijn.',
        'array' => 'De :attribute moet :size items bevatten.',
    ],
    'starts_with' => 'De :attribute moet starten met: :values.',
    'string' => 'De :attribute moet een string zijn.',
    'timezone' => 'De :attribute moet een geldige tijdzone bevatten.',
    'unique' => 'De :attribute is al in gebruik.',
    'uploaded' => 'Het is niet gelukt de :attribute te uploaden.',
    'url' => 'De :attribute moet een geldige URL zijn.',
    'uuid' => 'De :attribute moet een geldige UUID bevatten.',
    'phone' => 'Het :attribute is onjuist.',
    'postal_code' => 'De :attribute is onjuist.',
    'indisposable' => 'Wegwerp emailadressen zijn niet toegestaan.',
    'auth.failed' => 'Het email of wachtwoord is onjuist.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // User register form
        'first_name' => 'voornaam',
        'middle_name' => 'tussenvoegsel',
        'last_name' => 'achternaam',
        'gender' => 'geslacht',
        'birth_date' => 'geboortedatum',
        'street' => 'straat',
        'number' => 'nummer',
        'addition' => 'toevoeging',
        'postal_code' => 'postcode',
        'place_name' => 'plaatsnaam',
        'email' => 'e-mail',
        'phone' => 'telefoonnummer',
        'password' => 'wachtwoord',
        'policy' => 'policy',
        // Rol form
        'name' => 'naam',
        'permissions' => 'permissies',
        // Project comment
        'title' => 'titel',
        'description' => 'beschrijving',
        'image' => 'afbeelding',
    ],

];
