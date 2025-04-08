<?php

namespace App\Livewire\Frontend\Prijsopgave;

use App\Mail\QuoteMail;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Setting,
    Customer,
    Quote,
    QuoteStatus,
    Order,
    QuoteProduct,
    Product,
    ProductGroup,
    Prijsopgave,
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PrijsopgaveMailConfirmation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

class Form extends Component
{
    public $prijsopgave_id, $name, $email, $phone;
    public $cacheKey, $blocked_dates_array = [];
    public $party_date, $location_array = [], $location = "", $party_type_array = [], $party_type = "", $start_time, $end_time, $guest_amount, $party_on_upper_floor = false, $upper_floor_elevator_available = false, $party_date_available, $show_type_array = [], $show_type = "", $party_duration = 0;
    public $currentStep = 1;
    public $location_has_equipment = false;

    public function mount()
    {
        $this->party_type_array = [
            'Bruiloft',
            'Verjaardagsfeest',
            'Jubileumfeest',
            'Bedrijfsfeest',
            'Evenement',
            'Festival',
            'Overig',
        ];
        $this->location_array = [
            'Veghel',
            'Gemert',
            'Sint-Oedenrode',
            'Schijndel',
            'Erp',
            'Boerdonk',
            'Boskant',
            'Eerde',
            'Keldonk',
            'Mariaheide',
            'Nijnsel',
            'Wijbosch',
            'Zijtaart',
            'Uden',
            'Schaijk',
            'Zeeland',
            'Volkel',
            'Odiliapeel',
            'Reek',
            'Heesch',
            'Heeswijk-Dinther',
            'Loosbroek',
            'Nistelrode',
            'Vinkel',
            'Vorstenbosch',
            'Langenboom',
            'Wilbertoord',
            'Mill',
            'Herpen',
            'Ravenstein',
            'Rijkevoort',
            'Den bosch',
            'Nuland',
            'Rosmalen',
            'Boxtel',
            'Liempde',
            'Esch',
            'Vught',
            'Helvoirt',
            'Cromvoirt',
            'Sint-Michielsgestel',
            'Berlicum',
            'Den Dungen',
            'Gemonde',
            'Maaskantje',
            'Middelrode',
            'Vlijmen',
            'Oss',
            'Grave',
            'Berghem',
            'Elshout',
            'Nieuwkuijk',
            'Haarsteeg',
            'Hedel',
            'Geffen',
            'Eindhoven',
            'Son en Breugel',
            'Best',
            'Nuenen',
            'Helmond',
            'Stiphout',
            'Oirschot',
            'Gerwen',
            'Nederwetten',
            'Gemert',
            'Bakel',
            'De Mortel',
            'Elsendorp',
            'Handel',
            'Milheeze',
            'Boekel',
            'Beek en Donk',
            'Milheeze',
            'Venhorst',
            'Huize Padua',
            'Mariahout',
            'Tilburg',
            'Oisterwijk',
            'Waalwijk',
            'Moergestel',
            'Berkel Enschot',
            'Kaatsheuvel',
            'Udenhout',
            'Haaren',
            'Drunen',
            'Biezenmortel',
            'Loon op Zand',
            'Cuijk',
            'Milsbeek',
            'Gennep',
            'Heijen',
            'Groesbeek',
            'Malden',
            'Mook',
            'Beers',
            'Overasselt',
            'Heumen',
            'Wijchen',
            'Nijmegen',
            'Beuningen',
            'Ewijk',
            'Berg en Dal',
            'Lent',
        ];
        sort($this->location_array);
        array_unshift($this->location_array,'De plaats staat niet in de lijst');
        $this->show_type_array = [
            'Premium DJ Show',
            'Deluxe DJ Show',
            'Alleen DJ',
        ];
        $this->cacheKey = 'prijsopgave_id_' . \Request::ip() ?? 'Onbekend';
        $this->getProgress();
        $this->blocked_dates_array = array_map(function ($value) {return Carbon::parse($value)->format('Y-m-d');}, explode(',', str_replace(' ', '', Setting::where('name','blocked_dates')->first()->value ?? '')));
    }

    protected function rules()
    {
        return [
            1 => [
                'party_date' => 'required|date_format:Y-m-d|after:today|before:'. Carbon::now()->startOfDay()->addWeeks(78)->format('Y-m-d'),
            ],
            2 => [
                'name' => 'required|string|min:2',
                'email' => 'required|email|string|min:2',
                'phone' => 'required|string|min:10',
            ],
            3 => [
                'location' => 'required|string|in:' . implode(',', $this->location_array),
                'party_type' => 'required|string|in:' . implode(',', $this->party_type_array),
                'start_time' => ['required', 'date_format:H:i', function($attribute, $value, $fail) {
                    $parts = explode(':', $value);
                    if (count($parts) != 2 || intval($parts[1]) % 30 !== 0) {
                        $fail('De starttijd moet binnen een interval van 30 minuten vallen.');
                    }
                }],
                'end_time' => ['required', 'date_format:H:i', function($attribute, $value, $fail) {
                    $parts = explode(':', $value);
                    if (count($parts) != 2 || intval($parts[1]) % 30 !== 0) {
                        $fail('De eindtijd moet binnen een interval van 30 minuten vallen.');
                    }
                }],
                'guest_amount' => 'required|numeric|max:300|min:1',
                'party_on_upper_floor' => 'required|boolean',
                'upper_floor_elevator_available' => 'boolean',
                'show_type' => 'required|string|in:' . implode(',', $this->show_type_array),
            ]
        ];
    }

    protected $validationAttributes = [
        'party_date' => 'feestdatum',
        'name' => 'naam',
        'email' => 'e-mailadres',
        'phone' => 'telefoonnummer',
        'location' => 'Feestlocatie',
        'party_type' => 'feest type',
        'start_time' => 'starttijd',
        'end_time' => 'eindtijd',
        'guest_amount' => 'aantal gasten',
        'party_on_upper_floor' => 'feest op verdieping',
        'upper_floor_elevator_available' => 'lift beschikbaar',
        'show_type' => 'show',
    ];

    private function calculatePartyDuration()
    {
        $this->party_duration = (strtotime($this->end_time) - strtotime($this->start_time))/60/60;
        if ($this->party_duration < 0) {
            $this->party_duration = 24 + $this->party_duration;
        }
        $this->party_duration = (int)ceil($this->party_duration);
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->Rules()[$this->currentStep]);
        if ($this->currentStep > 2) {
            $this->saveData();
        }
    }

    private function getProgress()
    {
        $this->prijsopgave_id = Cache::get($this->cacheKey);

        if ($this->prijsopgave_id) {
            $prijsopgave = Prijsopgave::find($this->prijsopgave_id);

            if ($prijsopgave) {
                $this->party_date = (!empty($prijsopgave->party_date) ? Carbon::parse($prijsopgave->party_date)->format('Y-m-d') : null);
                $this->party_date_available = $prijsopgave->party_date_available;
                $this->name = $prijsopgave->name;
                $this->email = $prijsopgave->email;
                $this->phone = $prijsopgave->phone;
                $this->start_time = (!empty($prijsopgave->start_time) ? substr($prijsopgave->start_time, 0, 5) : null);
                $this->end_time = (!empty($prijsopgave->end_time) ? substr($prijsopgave->end_time, 0, 5) : null);
                $this->party_duration = (int)$prijsopgave->party_duration;
                $this->party_type = $prijsopgave->party_type ?? '';
                $this->location = $prijsopgave->location ?? '';
                $this->party_on_upper_floor = $prijsopgave->party_on_upper_floor;
                $this->upper_floor_elevator_available = $prijsopgave->upper_floor_elevator_available;
                $this->guest_amount = $prijsopgave->guest_amount;
                $this->show_type = $prijsopgave->show_type ?? '';
                $this->currentStep = $prijsopgave->currentStep;
            } else {
                $this->prijsopgave_id = null;
            }
        }
    }

    public function nextStep()
    {
        $this->validate($this->Rules()[$this->currentStep]);

        if ($this->currentStep == 3) {
            $this->calculatePartyDuration();
        }

        $this->currentStep++;
        
        if ($this->currentStep > 2) {
            $this->saveData();
        }
        
    }

    private function saveData()
    {
        try {
            DB::transaction(function () {
                $prijsopgave = Prijsopgave::updateOrCreate([
                    'id' => $this->prijsopgave_id
                ],
                [
                    'party_date' => $this->party_date,
                    'party_date_available' => $this->party_date_available,
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'party_duration' => $this->party_duration,
                    'party_type' => $this->party_type,
                    'location' => $this->location,
                    'party_on_upper_floor' => $this->party_on_upper_floor,
                    'upper_floor_elevator_available' => $this->upper_floor_elevator_available,
                    'guest_amount' => $this->guest_amount,
                    'show_type' => $this->show_type,
                    'currentStep' => $this->currentStep,
                    'reminder' => 1,
                    'reminder_at' => Carbon::now()->addHours(1),
                ]);

                $this->prijsopgave_id = $prijsopgave->id;

                // Save progress to cache 10 days
                Cache::put($this->cacheKey, $this->prijsopgave_id, now()->addMinutes(14400));
            });
        } catch(\Exception $e) {
            return $this->currentStep = 0;
        }    
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function StepSelection($number)
    {
        $this->currentStep = $number;
        if ($this->prijsopgave_id) {
            $this->saveData();
        }
    }

    public function updatedPartyDate($value)
    {
        $date = Carbon::parse($value);
        
        if (Order::whereDate('created_at', $date->format('Y-m-d'))->exists()) {
            return $this->party_date_available = false;
        }

        if (in_array($date->format('Y-m-d'), $this->blocked_dates_array)) {
            return $this->party_date_available = false;
        }

        $this->party_date_available = true;
    }

    public function updatedGuestAmount($value)
    {
        if ($value > 75) {
            if ($this->show_type == 'Premium DJ Show') {
                $this->show_type = 'Deluxe DJ Show';
            }
            $this->show_type_array = array_diff($this->show_type_array, ['Premium DJ Show']);
        }
        if ($value <= 75) {
            $this->show_type_array = [
                'Premium DJ Show',
                'Deluxe DJ Show',
                'Alleen DJ',
            ];
        }
    }

    public function store()
    {
        foreach ($this->Rules() as $step) {
            $this->validate($step);
        }

        $clientIP = \Request::ip() ?? 'onbekend';

        try {
            DB::transaction(function () use ($clientIP) {
                // Define variables
                $title = ucfirst(($this->party_type != 'Overig' ? $this->party_type : 'Feest')) . ' op ' . Carbon::parse($this->party_date)->translatedFormat('j F Y');
                $description = 'Prijsopgave aangevraagd op ' . Carbon::now()->translatedFormat('j F Y') . ' via IP adres ' . $clientIP . ' - Telefoonnummer: ' . $this->phone . ' - Email: ' . $this->email . ' - Feestdatum: ' . Carbon::parse($this->party_date)->format('d-m-Y') . ' van ' . $this->start_time . ' - ' . $this->end_time . ' - Locatie: ' . $this->location . ' - Feest type: ' . $this->party_type . ' - Aantal gasten: ' . $this->guest_amount . ' - Show: ' . $this->show_type;
                $quote_text = str_replace("Bedankt voor het fijne gesprek.", "Bedankt voor je interesse in een feest met DJ T-Fooh.", Setting::where('name','quote_text')->first()->value);
                if ($this->party_type == 'Bruiloft') {
                    $quote_text = str_replace("Hierbij de offerte voor het", "Hierbij de offerte voor de", $quote_text);
                }
                if ($this->party_type == 'Overig') {
                    $quote_text = str_replace("Hierbij de offerte voor het", "Hierbij de offerte voor", $quote_text);
                }

                $customer = Customer::updateOrCreate([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                ]);

                // Generate quote
                $quote = Quote::create([
                        'title' => $title,
                        'description' => $description,
                        'expiration_date' => Carbon::now()->startOfDay()->addDays(14),
                        'quote_text' => $quote_text,
                        'customer_id' => $customer->id,
                        'prices_exclude_tax' => 0,
                        'show_product_group_images' => 1,
                        'show_packages' => 1,
                        'show_amount_and_total' => 0,
                        'sent_to_customer' => 1,
                        'sent_at' => Carbon::now(),
                        'party_date' => $this->party_date,
                        'location' => $this->location,
                        'party_type' => $this->party_type,
                        'start_time' => $this->start_time,
                        'end_time' => $this->end_time,
                        'guest_amount' => $this->guest_amount,
                    ]);

                // Attach quote status
                $quote->quote_statuses()->attach(QuoteStatus::where('name', 'Wachten op klant')->first() ?? QuoteStatus::first(), [
                    'comment' => $description,
                ]);

                // Obtain pricing array
                $pricing_array = json_decode(Setting::where('name','party_pricing_array')->first()->value, true) ?? [];
                // Create pricing prompt
                $pricing_prompt = strtolower(str_replace(' ','_', $this->show_type)) . '_' . ((Carbon::parse($this->party_date)->isFriday() OR Carbon::parse($this->party_date)->isSaturday()) ? 'high_season' : 'off_season') . ($this->party_type == 'Bruiloft' ? '_wedding' : '');

                // Get all products from database
                $products = Product::with(['media' => function ($q) {$q->where('collection_name','product_images');}])->get();

                // Define packages array
                $package_array = ['brons', 'zilver', 'goud'];

                $package_pricing_array = [];

                $order_iteration = 1;
                foreach ($package_array as $package_name) {
                    if ($this->show_type == 'Alleen DJ') {
                        $product = $products->where('name', 'Alleen DJ - ' . ucfirst($package_name))->first();
                    } else {
                        $product = $products->where('name', ($this->party_type == 'Bruiloft' ? 'Bruiloft ' : 'Feest ') . ucfirst($package_name) .  ' - ' . $this->show_type)->first();
                    }

                    if ($package_name != 'brons') {
                        // Obtain package products name and amount
                        $package_product_name_and_amount_array = json_decode(Setting::where('name','package_'. $package_name . '_' . ($this->party_type == 'Bruiloft' ? 'wedding' : 'feest') . '_product_name_and_amount_array')->first()->value, true) ?? [];
                    }

                    // Calculate total package price
                        // Define package price and include price_index_next_year in the price
                    $package_total_price_customer_including_tax = Carbon::parse($this->party_date)->year != Carbon::now()->year ? (int)Setting::where('name','price_index_next_year')->first()->value : 0;
                    if ($package_name != 'brons') {
                        foreach ($package_product_name_and_amount_array as $name => $amount) {
                            $package_total_price_customer_including_tax = $package_total_price_customer_including_tax + ($products->where('name', $name)->first()->price_customer_including_tax * (float)$amount);
                        }
                    }
                    $package_total_price_customer_including_tax = $package_total_price_customer_including_tax + $pricing_array[$this->party_duration][$pricing_prompt];
                    
                    // Calculate package discount price
                    if ($package_name != 'brons') {
                        $package_total_discount_price_customer_including_tax = $package_total_price_customer_including_tax - ((int)Setting::where('name','discount_price_package_' . $package_name . '_' . ($this->party_type == 'Bruiloft' ? 'wedding' : 'feest'))->first()->value ?? 125);
                    } else {
                        $package_total_discount_price_customer_including_tax = $pricing_array[$this->party_duration][$pricing_prompt];
                    }

                    $package_pricing_array[$package_name] = [
                        'package_price' => $package_total_price_customer_including_tax,
                        'discount_package_price' => $package_total_discount_price_customer_including_tax,
                    ];
                    
                    // Create quote product
                    $quote_product = QuoteProduct::create([
                        'name' => str_replace(' - ' . $this->show_type, '', $product->name),
                        'purchase_price_excluding_tax' => $product->purchase_price_excluding_tax,
                        'purchase_price_including_tax' => $product->purchase_price_including_tax,
                        'price_customer_excluding_tax' => round($package_total_price_customer_including_tax / (1 + ($product->tax_percentage / 100)), 2),
                        'price_customer_including_tax' => $package_total_price_customer_including_tax,
                        'amount' => 1,
                        'total_price_customer_excluding_tax' => round($package_total_price_customer_including_tax / (1 + ($product->tax_percentage / 100)), 2),
                        'total_price_customer_including_tax' => $package_total_price_customer_including_tax,
                        'tax_percentage' => $product->tax_percentage,
                        'description' => $product->description,
                        'quote_id' => $quote->id,
                        'show_product_images' => 1,
                        'order' => $order_iteration++,
                        'highlight_text' => '',
                        'discount_price_customer_excluding_tax' => round($package_total_discount_price_customer_including_tax / (1 + ($product->tax_percentage / 100)), 2),
                        'discount_price_customer_including_tax' => $package_total_discount_price_customer_including_tax,
                        'total_discount_price_customer_excluding_tax' => round($package_total_discount_price_customer_including_tax / (1 + ($product->tax_percentage / 100)), 2),
                        'total_discount_price_customer_including_tax' => $package_total_discount_price_customer_including_tax,
                        'use_discount_prices' => ($package_name == 'brons' ? 0 : 1),
                    ]);

                    // Copy media
                    foreach ($product->media as $media_item) {
                        $media_item->copy($quote_product, 'product_images');
                    }
                    
                    // Reset product
                    $product = null;

                    // Reset quote_product
                    $quote_product = null;
                }

                // Add upper floor toeslag if applicable
                if ($this->upper_floor_elevator_available == true && $this->party_on_upper_floor == true) {
                    $product = $products->where('name', 'Toeslag feest op verdieping')->first();

                    $quote_product = QuoteProduct::create([
                        'name' => $product->name,
                        'purchase_price_excluding_tax' => $product->purchase_price_excluding_tax,
                        'purchase_price_including_tax' => $product->purchase_price_including_tax,
                        'price_customer_excluding_tax' => $product->price_customer_excluding_tax,
                        'price_customer_including_tax' => $product->price_customer_including_tax,
                        'amount' => 1,
                        'total_price_customer_excluding_tax' => $product->price_customer_excluding_tax,
                        'total_price_customer_including_tax' => $product->price_customer_including_tax,
                        'tax_percentage' => $product->tax_percentage,
                        'description' => $product->description,
                        'quote_id' => $quote->id,
                        'show_product_images' => 1,
                        'order' => $order_iteration++,
                        'highlight_text' => '',
                        'discount_price_customer_excluding_tax' => $product->discount_price_customer_excluding_tax ?? 0,
                        'discount_price_customer_including_tax' => $product->discount_price_customer_including_tax ?? 0,
                        'total_discount_price_customer_excluding_tax' => $product->discount_price_customer_excluding_tax ?? 0,
                        'total_discount_price_customer_including_tax' => $product->discount_price_customer_including_tax ?? 0,
                        'use_discount_prices' => $product->use_discount_prices ?? 0,
                    ]);

                    // Copy media
                    foreach ($product->media as $media_item) {
                        $media_item->copy($quote_product, 'product_images');
                    }

                    $product = null;
                    $quote_product = null;
                }

                if ($this->location == 'De plaats staat niet in de lijst') {
                    $product = $products->where('name', 'Toeslag reistijd (per minuut)')->first();

                    $quote_product = QuoteProduct::create([
                        'name' => $product->name,
                        'purchase_price_excluding_tax' => $product->purchase_price_excluding_tax,
                        'purchase_price_including_tax' => $product->purchase_price_including_tax,
                        'price_customer_excluding_tax' => $product->price_customer_excluding_tax,
                        'price_customer_including_tax' => $product->price_customer_including_tax,
                        'amount' => 1,
                        'total_price_customer_excluding_tax' => $product->price_customer_excluding_tax,
                        'total_price_customer_including_tax' => $product->price_customer_including_tax,
                        'tax_percentage' => $product->tax_percentage,
                        'description' => $product->description,
                        'quote_id' => $quote->id,
                        'show_product_images' => 1,
                        'order' => $order_iteration++,
                        'highlight_text' => '',
                        'discount_price_customer_excluding_tax' => $product->discount_price_customer_excluding_tax ?? 0,
                        'discount_price_customer_including_tax' => $product->discount_price_customer_including_tax ?? 0,
                        'total_discount_price_customer_excluding_tax' => $product->discount_price_customer_excluding_tax ?? 0,
                        'total_discount_price_customer_including_tax' => $product->discount_price_customer_including_tax ?? 0,
                        'use_discount_prices' => $product->use_discount_prices ?? 0,
                    ]);

                    // Copy media
                    foreach ($product->media as $media_item) {
                        $media_item->copy($quote_product, 'product_images');
                    }

                    $product = null;
                    $quote_product = null;
                }

                // Create package image
                // Fetch and copy default image
                $template_package_image_file = Storage::disk('templates')->get('prijsopgave/default_package_image_' . ($this->party_type == 'Bruiloft' ? 'wedding' : 'feest') . '.png');
                Storage::disk('local')->put('package_image_' . $quote->id . '.png', $template_package_image_file);

                
                $package_image = Image::load(Storage::disk('local')->path('package_image_' . $quote->id . '.png'));
                // Add DJ Show
                $package_image->text($this->show_type, 18, '#000000', ($this->show_type == 'Premium DJ Show' ? 63 : ($this->show_type == 'Deluxe DJ Show' ? 69 : 97)), 125);


                // Add brons package prices as text
                $package_image->text('€' . number_format($package_pricing_array['brons']['package_price'], 0,',','.'), 24, '#000000', 365, 706);

                // Add zilver package prices as text
                    // Package price
                    $package_image->text('€' . number_format($package_pricing_array['zilver']['package_price'], 0,',','.'), 24, '#000000', (
                        $package_pricing_array['zilver']['package_price'] > 1000 ? 
                            ($package_pricing_array['zilver']['discount_package_price'] > 1000 ? 570 : 582) : 
                            ($package_pricing_array['zilver']['discount_package_price'] > 1000 ? 550 : 594))
                    , 706);
                    
                    // Discount price
                    $package_image->text('€' . number_format($package_pricing_array['zilver']['discount_package_price'], 0,',','.'), 24, '#FF0000', (
                        $package_pricing_array['zilver']['package_price'] > 1000 ? 
                        ($package_pricing_array['zilver']['discount_package_price'] > 1000 ? 658 : 670) : 
                        ($package_pricing_array['zilver']['discount_package_price'] > 1000 ? 661 : 658)
                    ), 706);
                    
                    // Doorstreep
                    $package_image->text($package_pricing_array['zilver']['package_price'] > 1000 ? '___' : '__' , 50, '#000000', (
                        $package_pricing_array['zilver']['package_price'] > 1000 ? 
                            ($package_pricing_array['zilver']['discount_package_price'] > 1000 ? 564 : 576) : 
                            ($package_pricing_array['zilver']['discount_package_price'] > 1000 ? 586 : 592)
                        ), 689);
                    
                    // Bespaar tekst
                    $package_image->text('Bespaar €' . number_format($package_pricing_array['zilver']['package_price'] - $package_pricing_array['zilver']['discount_package_price'], 0,',','.'), 24, '#000000', 578, 739);

                // Add goud package prices as text
                $package_image->text('€' . number_format($package_pricing_array['goud']['package_price'], 0,',','.'), 24, '#000000', 822, 706);
                $package_image->text('€' . number_format($package_pricing_array['goud']['discount_package_price'], 0,',','.'), 24, '#FF0000', 910, 706);
                $package_image->text('___', 50, '#000000', 814, 689);
                $package_image->text('Bespaar €' . number_format($package_pricing_array['goud']['package_price'] - $package_pricing_array['goud']['discount_package_price'], 0,',','.'), 24, '#000000', 829, 739);

                // Save image
                $package_image->save();

                // Add image to media collection
                $quote->addMediaFromDisk('package_image_' . $quote->id . '.png', 'local')->toMediaCollection('packages');

                // Attach extra opties
                $product_groups_array = [
                    'Extra opties',
                    'Extra optie Dansvloer'
                ];
                $product_group_ids_array = ProductGroup::whereIn('name', $product_groups_array)->select('id')->get()->pluck('id')->toArray();
                $quote->product_groups()->sync($product_group_ids_array);
                
                
                // Mail quote to customer
                Mail::to($this->email)
                    ->send((new QuoteMail($quote->id)));

                // Mail new request to business email
                Mail::to(Setting::where('name','business_email')->first()->value ?? 'dj@t-fooh.nl')
                    ->send((new PrijsopgaveMailConfirmation($quote->id)));

                // Remove prijsopgave
                Prijsopgave::destroy($this->prijsopgave_id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Er is een fout opgetreden!',
                'text' => 'Probeer het opnieuw of neem contact op.' . $e->getMessage(),
            ]);
        }

        $this->currentStep = 5;

        return $this->cleanVars();
    }

    public function render()
    {
        return view('frontend.prijsopgave.livewire.form');
    }

    private function cleanVars()
    {
        $this->prijsopgave_id = null;
        $this->name = null;
        $this->email = null;
        $this->phone = null;
        $this->party_date = null;
        $this->location = "";
        $this->party_type = "";
        $this->start_time = null;
        $this->end_time = null;
        $this->guest_amount = null;
        $this->party_on_upper_floor = false;
        $this->upper_floor_elevator_available = false;
        $this->party_date_available = null;
        $this->show_type = "";
        $this->party_duration = 0;
        $this->show_type_array = [
            'Premium DJ Show',
            'Deluxe DJ Show',
            'Alleen DJ',
        ];
        Cache::put($this->cacheKey, 0, 0);
        $this->cacheKey = null;
    }
}
