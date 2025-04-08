<?php

namespace App\Mail;

use App\Models\{
    Customer,
    Setting,
};
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApkReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $customer, $service, $link_to_logo_image, $link_to_website, $link_to_contact_page, $email_template_color, $business_kvk, $final_reminder, $email_header, $link_to_apk_page, $business_email, $business_phone, $business_whatsapp_link;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customer_id, $final_reminder)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->customer = Customer::query()
            ->whereHas('services', function ($q) {
                $q->whereIn('name',['APK op afstand','APK aan huis']);
            })->with(['services' => function ($q) {
                $q->whereIn('name',['APK op afstand','APK aan huis']);
            }])
            ->findOrFail($customer_id);

        $utm_code = '?utm_source=crm&utm_medium=email&utm_campaign=apk_reminder_invitation';
        $this->service = $this->customer->services->first();
        $this->final_reminder = ($final_reminder == 1 ? 1 : 0);
        $this->email_header = $this->final_reminder == 0 ? 'Reminder: Plan de jaarlijkse APK in' : 'Laatste reminder om de jaarlijkse APK te plannen';
        $this->link_to_logo_image = Setting::where('name','link_to_logo_image')->first()->value;
        $this->link_to_website = Setting::where('name','link_to_website')->first()->value . $utm_code;
        $this->link_to_contact_page = Setting::where('name','link_to_contact_page')->first()->value . $utm_code;
        $this->email_template_color = Setting::where('name','email_template_color')->first()->value;
        $this->business_kvk = Setting::where('name','business_kvk')->first()->value;
        $this->link_to_apk_page = Setting::where('name','link_to_apk_page')->first()->value . $utm_code;
        $this->business_email = Setting::where('name','business_email')->first()->value;
        $this->business_phone = Setting::where('name','business_phone')->first()->value;
        $this->business_whatsapp_link = Setting::where('name','business_whatsapp_link')->first()->value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->email_header)->markdown('mail.apk_reminder');
    }
}
