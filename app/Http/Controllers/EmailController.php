<?php

namespace App\Http\Controllers;

use App\Mail\{
	OrderHoursMail,
	InvoiceMail,
	QuoteStatusConfirmation,
	ApkInvitationMail,
    ApkReminderMail,
    ServicesReminderMail,
	InvoicePaidMail,
    InvoiceReminderMail,
    QuoteMail,
    QuoteMailConfirmation,
	QuoteReminderMail,
	ScheduleAppointmentMail,
	PrijsopgaveMailConfirmation,
    PrijsopgaveReminderMail,
	PrijsopgaveContactInfoToBusinessMail,
};

class EmailController extends Controller
{
	public function order_hours_user_report_email($user_id)
	{
		return new OrderHoursMail($user_id);
	}

	public function invoice_email($invoice_id)
	{
		return new InvoiceMail($invoice_id);
	}

	public function quote_email_confirmation($quote_id)
	{
		return new QuoteStatusConfirmation($quote_id, '000.000.000.000');
	}

	public function quote_email_accepted($quote_id)
	{
		return new QuoteMailConfirmation($quote_id);
	}

	public function quote_email_refused($quote_id)
	{
		return new QuoteMailConfirmation($quote_id);
	}

	public function quote_mail($quote_id)
	{
		return new QuoteMail($quote_id);
	}

	public function apk_invitation_email($customer_id)
	{
		return new ApkInvitationMail($customer_id);
	}

    public function services_reminder()
    {
        return new ServicesReminderMail();
    }

	public function invoice_mail($invoice_id)
	{
		return new InvoiceMail($invoice_id);
	}

	public function invoice_paid_mail($invoice_id, $payment_id)
	{
		return new InvoicePaidMail($invoice_id, $payment_id);
	}

	public function invoice_reminder_mail($invoice_id)
	{
		return new InvoiceReminderMail($invoice_id);
	}

	public function quote_reminder_mail($quote_id)
	{
		return new QuoteReminderMail($quote_id);
	}

	public function schedule_appointment_mail($order_id, $reminder, $appointment_type)
	{
		return new ScheduleAppointmentMail($order_id, $reminder, $appointment_type);
	}

	public function apk_reminder_mail($customer_id, $final_reminder)
	{
		return new ApkReminderMail($customer_id, $final_reminder);
	}

	public function prijsopgave_mail_confirmation($quote_id)
	{
		return new PrijsopgaveMailConfirmation($quote_id);
	}

	public function prijsopgave_reminder_mail($prijsopgave_id, $reminder)
	{
		return new PrijsopgaveReminderMail($prijsopgave_id, $reminder);
	}

	public function prijsopgave_contact_info_to_business_mail($prijsopgave_id)
	{
		return new PrijsopgaveContactInfoToBusinessMail($prijsopgave_id);
	}
}
