<?php

namespace App\Mail;

use App\Models\{
    Customer,
    Setting,
    Order,
};
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleAppointmentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order, $customer, $service, $link_to_logo_image, $link_to_website, $link_to_contact_page, $email_template_color, $business_kvk, $link_to_scheduling_page, $schedule_appointment_text, $reminder, $email_header, $business_email, $business_phone, $title, $business_whatsapp_link;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $reminder, $appointment_type)
    {
        setlocale(LC_ALL, 'nl_NL');

        if ($appointment_type == 0) {
            $this->order = Order::findOrFail($id);
            $this->customer = $this->order->customer;
            $this->reminder = ($reminder == 2 ? 2 : (($reminder == 1) ? 1 : 0));
            $this->email_header = $this->reminder == 0 ? 'BELANGRIJK: Plan een afspraak om alle feestwensen te bespreken' : ($this->reminder == 1 ? 'HERINNERING: Plan een afspraak om alle feestwensen te bespreken' : 'BELANGRIJKE HERINNERING: Plan een afspraak om alle feestwensen te bespreken');
            $this->title = 'Feestwensen doorspreken';
        } elseif ($appointment_type == 1) {
            $this->customer = Customer::findOrFail($id);
            $this->reminder = ($reminder == 1 ? 1 : 0);
            $this->email_header = 'Plan een afspraak in';
            $this->title = 'Afspraak plannen';
        } else {
            return 0;
        }
        
        $utm_code = '?utm_source=crm&utm_medium=email&utm_campaign=appointment_invitation';
        $this->link_to_logo_image = Setting::where('name','link_to_logo_image')->first()->value;
        $this->link_to_website = Setting::where('name','link_to_website')->first()->value . $utm_code;
        $this->link_to_contact_page = Setting::where('name','link_to_contact_page')->first()->value . $utm_code;
        $this->email_template_color = Setting::where('name','email_template_color')->first()->value;
        $this->business_kvk = Setting::where('name','business_kvk')->first()->value;
        $this->link_to_scheduling_page = Setting::where('name','link_to_scheduling_page')->first()->value . $utm_code;
        $this->schedule_appointment_text = Setting::where('name','schedule_appointment_text')->first()->value;
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
        return $this->subject($this->email_header)->markdown('mail.schedule_appointment');
    }
}
