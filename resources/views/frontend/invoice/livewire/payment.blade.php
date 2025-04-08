<div class="col-12">					
    <button class="btn btn-outline-danger btn-lg mb-1" style="pointer-events: none;">Onbetaald</button>
    <br>
    <h4>Je kunt de factuur veilig online betalen met iDeal via Mollie.<br><b>LET OP:</b> Mollie brengt €{{ number_format($transaction_costs,2,",","."); }} transactiekosten in rekening bovenop de factuur.<br>Uiteraard kun je de factuur ook handmatig betalen per bank.<br><br>Wil je de factuur opslaan als PDF? Klik dan op "<a href="javascript:void(0)" onclick="window.print();">Printen</a>" en kies vervolgens de printer "Opslaan als PDF".</h4>
    <button wire:click="preparePayment" class="btn btn-primary btn-lg mt-1">
        <svg xmlns="http://www.w3.org/2000/svg" height="3em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#ffffff}</style><path d="M125.61,165.48a49.07,49.07,0,1,0,49.06,49.06A49.08,49.08,0,0,0,125.61,165.48ZM86.15,425.84h78.94V285.32H86.15Zm151.46-211.6c0-20-10-22.53-18.74-22.53H204.82V237.5h14.05C228.62,237.5,237.61,234.69,237.61,214.24Zm201.69,46V168.93h22.75V237.5h33.69C486.5,113.08,388.61,86.19,299.67,86.19H204.84V169h14c25.6,0,41.5,17.35,41.5,45.26,0,28.81-15.52,46-41.5,46h-14V425.88h94.83c144.61,0,194.94-67.16,196.72-165.64Zm-109.75,0H273.3V169h54.43v22.73H296v10.58h30V225H296V237.5h33.51Zm74.66,0-5.16-17.67H369.31l-5.18,17.67H340.47L368,168.92h32.35l27.53,91.34ZM299.65,32H32V480H299.65c161.85,0,251-79.73,251-224.52C550.62,172,518,32,299.65,32Zm0,426.92H53.07V53.07H299.65c142.1,0,229.9,64.61,229.9,202.41C529.55,389.57,448.55,458.92,299.65,458.92Zm83.86-264.85L376,219.88H392.4l-7.52-25.81Z"/></svg>
        <div class="h4 text-white"> De factuur online betalen via iDeal</div>
    </button>

    @if ($payment_status)
        <div class="alert alert-{{ ($payment_status == 'paid') ? 'success' : (($payment_status == 'canceled' || $payment_status == 'failed') ? 'danger' : ($payment_status == 'expired' ? 'warning' : ($payment_status == 'open' ? 'info' : ''))) }} mt-2 p-1" role="alert">
            <h4 class="m-0">@if ($payment_status == 'paid') De factuur is betaald, bedankt! @elseif ($payment_status == 'canceled') De betaling is geannuleerd, probeer het <a href="javascript:void(0)" wire:click="preparePayment">opnieuw</a> @elseif ($payment_status == 'failed') De betaling is niet gelukt, probeer het <a href="javascript:void(0)" wire:click="preparePayment">opnieuw</a> @elseif ($payment_status == 'expired') De betaling is verlopen, probeer het <a href="javascript:void(0)" wire:click="preparePayment">opnieuw</a> @elseif ($payment_status == 'open') De betaling staat nog open, wachten op betaling @endif</h4>
        </div>
    @endif
</div>