<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Setting,
};

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::firstOrCreate(
            ['name' => 'quote_text'],
            ['value' => "Hallo ['first_name'],<br>
            <br>
            <br>
            Bedankt voor je interesse in een ['quote_title']. Hierbij ontvang je een offerte op maat.<br>
            Hieronder vind je de basisprijs voor elk product. Om het product volledig bij jouw wensen aan te laten sluiten zijn er een aantal keuzes te maken.<br>
            Als je vragen hebt mag je altijd even <a href='tel:0031 6 82041651'>bellen</a>, <a href='mailto:info@deitdokter.nl'>mailen</a> of <a href='https://api.whatsapp.com/send?phone=31682041651' target='_blank'>whatsappen</a>. Uiteraard kun je ook een afspraak maken via mijn <a href='http://deitdokter.nl/afspraak-maken.html' target='_blank'>website</a>.<br>
            <br>
            Ik hoor graag van je, bedankt vast!<br>
            <br>
            <br>
            Met vriendelijke groet,<br>
            Thomas van Hooft<br>
            <span class='text-primary'>['company']</span>",
        ]);

        Setting::firstOrCreate([
            'name' => 'direct_accept_quote_text'],
            ['value' => 'Als je de offerte geopend hebt en alles duidelijk en naar wens is, dan kun je de offerte ook direct online accepteren, handig! Dan ga ik onmiddellijk aan de slag voor je en wordt je product zo snel mogelijk geleverd.',
        ]);

        Setting::firstOrCreate([
            'name' => 'link_to_logo_image'],
            ['value' => 'https://deitdokter.nl/assets/images/Logo_De_IT_Dokter.png',
        ]);

        Setting::firstOrCreate([
            'name' => 'link_to_website'],
            ['value' => 'https://deitdokter.nl',
        ]);

        Setting::firstOrCreate([
            'name' => 'business_email'],
            ['value' => 'info@deitdokter.nl',
        ]);

        Setting::firstOrCreate([
            'name' => 'information_contact_text'],
            ['value' => 'Als je vragen hebt mag je altijd even <a rel="noopener" href="tel:0031 6 82041651" target="_blank">bellen</a>, <a rel="noopener" href="mailto:info@deitdokter.nl?subject=Vraag%20over%20offerte&amp;body=" target="_blank">mailen</a> of <a rel="noopener" href="https://api.whatsapp.com/send?phone=31682041651" target="_blank">whatsappen</a>.<br />Uiteraard kun je ook een afspraak inplannen via mijn <a rel="noopener" href="http://deitdokter.nl" target="_blank">website</a>.',
        ]);

        Setting::firstOrCreate([
            'name' => 'link_to_contact_page'],
            ['value' => 'https://deitdokter.nl/contact.html',
        ]);

        Setting::firstOrCreate([
            'name' => 'email_template_color'],
            ['value' => '#0f70b7',
        ]);

        Setting::firstOrCreate([
            'name' => 'business_phone'],
            ['value' => '0031&nbsp;6&nbsp;82041651',
        ]);

        Setting::firstOrCreate([
            'name' => 'business_iban'],
            ['value' => 'NL&nbsp;84&nbsp;INGB&nbsp;0675&nbsp;3897&nbsp;20',
        ]);

        Setting::firstOrCreate([
            'name' => 'business_bank'],
            ['value' => 'ING',
        ]);

        Setting::firstOrCreate([
            'name' => 'business_vat_number'],
            ['value' => 'NL004087316B24',
        ]);

        Setting::firstOrCreate([
            'name' => 'business_kvk'],
            ['value' => '85378526',
        ]);

        Setting::firstOrCreate([
            'name' => 'business_address'],
            ['value' => 'Stationsstraat&nbsp;32a<br>5461&nbsp;JV&nbsp;&nbsp;Veghel',
        ]);

        Setting::firstOrCreate([
            'name' => 'invoice_comments_text'],
            ['value' => 'Ik verzoek u vriendelijk het verschuldigde bedrag binnen 14 dagen te betalen via de betaallink in deze factuur of door deze over te maken op bovenstaand rekeningnummer onder vermelding van het factuurnummer.',
        ]);

        Setting::firstOrCreate([
            'name' => 'invoice_text'],
            ['value' => 'Bij deze stuur ik je de factuur voor de geleverde diensten en/of producten toe. Hopelijk is de factuur duidelijk.<br>Mocht je nog vragen hebben dan hoor ik ze graag. En anders alvast bedankt voor de betaling!',
        ]);

        Setting::firstOrCreate([
            'name' => 'quote_mail_accepted_button_text'],
            ['value' => 'Bestelling bekijken'
        ]);

        Setting::firstOrCreate([
            'name' => 'quote_mail_accepted_text_block_one_title'],
            ['value' => 'Levering'
        ]);

        Setting::firstOrCreate([
            'name' => 'quote_mail_accepted_text_block_one_text'],
            ['value' => 'Ik ga aan de slag om de producten zo snel mogelijk te leveren. Zodra ik de producten binnen heb neem ik contact met je op.'
        ]);

        Setting::firstOrCreate([
            'name' => 'quote_mail_accepted_text_block_two_title'],
            ['value' => 'Vragen'
        ]);

        Setting::firstOrCreate([
            'name' => 'quote_mail_accepted_text_block_two_text'],
            ['value' => 'Mocht je in de tussentijd vragen of opmerkingen hebben mag je altijd even <a rel="noopener" href="tel:0031 6 82041651" target="_blank">bellen</a>, <a rel="noopener" href="mailto:info@deitdokter.nl?subject=Vraag%20over%20offerte&amp;body=" target="_blank">mailen</a> of <a rel="noopener" href="https://api.whatsapp.com/send?phone=31682041651" target="_blank">whatsappen</a>.<br />Uiteraard kun je ook een afspraak inplannen via mijn <a rel="noopener" href="http://deitdokter.nl" target="_blank">website</a>.'
        ]);

        Setting::firstOrCreate([
            'name' => 'invoice_paid_text'],
            ['value' => 'Bedankt voor de betaling, deze is in goede orde ontvangen!<br>
            Mocht je in de toekomst problemen of vragen hebben, aarzel dan niet om contact op te nemen. Ik ben je graag van dienst!<br><br><br>'
        ]);

        Setting::firstOrCreate([
            'name' => 'invoice_reminder_text'],
            ['value' => 'Bij deze stuur ik je een herinnering omdat je bijgaande factuur nog niet betaald hebt.<br><br>Zou je het bedrag binnen 7 dagen willen overmaken?<br>Alvast bedankt!'
        ]);

        Setting::firstOrCreate([
            'name' => 'show_company_in_customer_list'],
            ['value' => 1,
        ]);

        Setting::firstOrCreate([
            'name' => 'scheduled_send_order_hour_mail'],
            ['value' => 1,
        ]);

        Setting::firstOrCreate([
            'name' => 'schedule_create_new_orders_from_services'],
            ['value' => 1,
        ]);

        Setting::firstOrCreate([
            'name' => 'schedule_change_order_status_before_show'],
            ['value' => 0,
        ]);

        Setting::firstOrCreate([
            'name' => 'schedule_change_order_status_after_show'],
            ['value' => 0,
        ]);

        Setting::firstOrCreate([
            'name' => 'send_email_reminder_quote'],
            ['value' => 1,
        ]);

        Setting::firstOrCreate([
            'name' => 'email_reminder_quote_period'],
            ['value' => '2, 7, 15',
        ]);

        Setting::firstOrCreate([
            'name' => 'terms_and_services_link'],
            ['value' => 'https://deitdokter.nl/assets/files/Algemene-Voorwaarden.pdf',
        ]);

        Setting::firstOrCreate([
            'name' => 'amount_of_days_expired_invoice_viewable'],
            ['value' => '45',
        ]);

        Setting::firstOrCreate([
            'name' => 'transaction_costs_invoice'],
            ['value' => '0.39',
        ]);
        
        Setting::firstOrCreate([
            'name' => 'link_to_scheduling_page'],
            ['value' => 'https://t-fooh.nl/afspraak-plannen.html',
        ]);

        Setting::firstOrCreate([
            'name' => 'schedule_appointment_text'],
            ['value' => 'Over ongeveer drie weken is het zo ver!<br>Laten we alle muziek- en feestwensen doorspreken.<br>Via onderstaande knop kun je de afspraak inplannen.',
        ]);

        Setting::firstOrCreate([
            'name' => 'enable_deposit'],
            ['value' => 0,
        ]);

        Setting::firstOrCreate([
            'name' => 'default_show_amount_and_total_for_quote'],
            ['value' => 1,
        ]);

        Setting::firstOrCreate([
            'name' => 'send_show_invitation_reminder_mail_period'],
            ['value' => 0,
        ]);

        Setting::firstOrCreate([
            'name' => 'send_apk_reminder_mail_period'],
            ['value' => '7, 14',
        ]);

        Setting::firstOrCreate(
            ['name' => 'enable_prijsopgave_module'],
            ['value' => 0]);

        Setting::firstOrCreate(
            ['name' => 'enable_party_fields'],
            ['value' => 0]);

        Setting::firstOrCreate(
            ['name' => 'party_pricing_array'],
            ['value' => 
            json_encode([
                1 => [
                    'alleen_dj_off_season' => 350,
                    'premium_dj_show_off_season' => 450,
                    'deluxe_dj_show_off_season' => 550,
                    'alleen_dj_high_season' => 500,
                    'premium_dj_show_high_season' => 550,
                    'deluxe_dj_show_high_season' => 650,
                    'alleen_dj_off_season_wedding' => 450,
                    'premium_dj_show_off_season_wedding' => 550,
                    'deluxe_dj_show_off_season_wedding' => 650,
                    'alleen_dj_high_season_wedding' => 550,
                    'premium_dj_show_high_season_wedding' => 650,
                    'deluxe_dj_show_high_season_wedding' => 750,
                ],
                2 => [
                    'alleen_dj_off_season' => 350,
                    'premium_dj_show_off_season' => 475,
                    'deluxe_dj_show_off_season' => 575,
                    'alleen_dj_high_season' => 350,
                    'premium_dj_show_high_season' => 575,
                    'deluxe_dj_show_high_season' => 675,
                    'alleen_dj_off_season_wedding' => 450,
                    'premium_dj_show_off_season_wedding' => 575,
                    'deluxe_dj_show_off_season_wedding' => 675,
                    'alleen_dj_high_season_wedding' => 550,
                    'premium_dj_show_high_season_wedding' => 675,
                    'deluxe_dj_show_high_season_wedding' => 775,
                ],
                3 => [
                    'alleen_dj_off_season' => 350,
                    'premium_dj_show_off_season' => 500,
                    'deluxe_dj_show_off_season' => 600,
                    'alleen_dj_high_season' => 500,
                    'premium_dj_show_high_season' => 600,
                    'deluxe_dj_show_high_season' => 700,
                    'alleen_dj_off_season_wedding' => 450,
                    'premium_dj_show_off_season_wedding' => 600,
                    'deluxe_dj_show_off_season_wedding' => 700,
                    'alleen_dj_high_season_wedding' => 550,
                    'premium_dj_show_high_season_wedding' => 700,
                    'deluxe_dj_show_high_season_wedding' => 800,
                ],
                4 => [
                    'alleen_dj_off_season' => 350,
                    'premium_dj_show_off_season' => 525,
                    'deluxe_dj_show_off_season' => 625,
                    'alleen_dj_high_season' => 500,
                    'premium_dj_show_high_season' => 625,
                    'deluxe_dj_show_high_season' => 725,
                    'alleen_dj_off_season_wedding' => 450,
                    'premium_dj_show_off_season_wedding' => 625,
                    'deluxe_dj_show_off_season_wedding' => 725,
                    'alleen_dj_high_season_wedding' => 550,
                    'premium_dj_show_high_season_wedding' => 725,
                    'deluxe_dj_show_high_season_wedding' => 825,
                ],
                5 => [
                    'alleen_dj_off_season' => 400,
                    'premium_dj_show_off_season' => 550,
                    'deluxe_dj_show_off_season' => 650,
                    'alleen_dj_high_season' => 500,
                    'premium_dj_show_high_season' => 650,
                    'deluxe_dj_show_high_season' => 750,
                    'alleen_dj_off_season_wedding' => 500,
                    'premium_dj_show_off_season_wedding' => 650,
                    'deluxe_dj_show_off_season_wedding' => 750,
                    'alleen_dj_high_season_wedding' => 600,
                    'premium_dj_show_high_season_wedding' => 750,
                    'deluxe_dj_show_high_season_wedding' => 850,
                ],
                6 => [
                    'alleen_dj_off_season' => 500,
                    'premium_dj_show_off_season' => 650,
                    'deluxe_dj_show_off_season' => 750,
                    'alleen_dj_high_season' => 600,
                    'premium_dj_show_high_season' => 750,
                    'deluxe_dj_show_high_season' => 850,
                    'alleen_dj_off_season_wedding' => 600,
                    'premium_dj_show_off_season_wedding' => 750,
                    'deluxe_dj_show_off_season_wedding' => 850,
                    'alleen_dj_high_season_wedding' => 700,
                    'premium_dj_show_high_season_wedding' => 850,
                    'deluxe_dj_show_high_season_wedding' => 950,
                ],
                7 => [
                    'alleen_dj_off_season' => 600,
                    'premium_dj_show_off_season' => 750,
                    'deluxe_dj_show_off_season' => 850,
                    'alleen_dj_high_season' => 700,
                    'premium_dj_show_high_season' => 850,
                    'deluxe_dj_show_high_season' => 950,
                    'alleen_dj_off_season_wedding' => 700,
                    'premium_dj_show_off_season_wedding' => 850,
                    'deluxe_dj_show_off_season_wedding' => 950,
                    'alleen_dj_high_season_wedding' => 800,
                    'premium_dj_show_high_season_wedding' => 950,
                    'deluxe_dj_show_high_season_wedding' => 1050,
                ],
                8 => [
                    'alleen_dj_off_season' => 700,
                    'premium_dj_show_off_season' => 850,
                    'deluxe_dj_show_off_season' => 950,
                    'alleen_dj_high_season' => 800,
                    'premium_dj_show_high_season' => 950,
                    'deluxe_dj_show_high_season' => 1050,
                    'alleen_dj_off_season_wedding' => 800,
                    'premium_dj_show_off_season_wedding' => 950,
                    'deluxe_dj_show_off_season_wedding' => 1050,
                    'alleen_dj_high_season_wedding' => 900,
                    'premium_dj_show_high_season_wedding' => 1050,
                    'deluxe_dj_show_high_season_wedding' => 1150,
                ]
            ])]);
        Setting::firstOrCreate(
            ['name' => 'package_zilver_feest_product_name_and_amount_array'],
            ['value' => json_encode([
                'Stijlvolle Photobooth' => 1,
                'TV (43 inch)' => 1,
                'Foamsticks (per stuk)' => 50,
            ])]);
        
        Setting::firstOrCreate(
            ['name' => 'package_goud_feest_product_name_and_amount_array'],
            ['value' => json_encode([
                'Stijlvolle Photobooth' => 1,
                'TV (43 inch)' => 1,
                'Foamsticks (per stuk)' => 100,
                'Sfeervolle zaalverlichting (Uplights)' => 1,
                'Sparkular vonken fontein (per 2 stuks)' => 1,
                'Confetti kanon (per 5 stuks)' => 1,
            ])]);

        Setting::firstOrCreate(
            ['name' => 'package_zilver_wedding_product_name_and_amount_array'],
            ['value' => json_encode([
                'Stijlvolle Photobooth' => 1,
                'Sparkular vonken fontein (per 2 stuks)' => 1,
                'Foamsticks (per stuk)' => 50,
            ])]);
        
        Setting::firstOrCreate(
            ['name' => 'package_goud_wedding_product_name_and_amount_array'],
            ['value' => json_encode([
                'Stijlvolle Photobooth' => 1,
                'Sparkular vonken fontein (per 2 stuks)' => 1,
                'Foamsticks (per stuk)' => 100,
                'Sfeervolle zaalverlichting (Uplights)' => 1,
                'TV (43 inch)' => 1,
                'Confetti kanon (per 5 stuks)' => 1,
            ])]);
    
        Setting::firstOrCreate(
            ['name' => 'discount_price_package_zilver_feest'],
            ['value' => '125']);

        Setting::firstOrCreate(
            ['name' => 'discount_price_package_goud_feest'],
            ['value' => '400']);

        Setting::firstOrCreate(
            ['name' => 'discount_price_package_zilver_wedding'],
            ['value' => '150']);

        Setting::firstOrCreate(
            ['name' => 'discount_price_package_goud_wedding'],
            ['value' => '400']);

        Setting::firstOrCreate(
            ['name' => 'blocked_dates'],
            ['value' => "0"]);

        Setting::firstOrCreate(
            ['name' => 'price_index_next_year'],
            ['value' => "50"]);

        Setting::firstOrCreate(
            ['name' => 'deposit_percentage_amount'],
            ['value' => "25"]);

        Setting::firstOrCreate(
            ['name' => 'business_whatsapp_link'],
            ['value' => "https://api.whatsapp.com/send?phone=31682041651"]);

        Setting::firstOrCreate(
            ['name' => 'link_to_apk_page'],
            ['value' => "https://deitdokter.nl/apk.html"]);
    }
}
