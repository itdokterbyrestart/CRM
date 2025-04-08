@extends('layouts/contentLayoutMaster')

@section('title', 'Informatie')

@section('content')
<!-- Order informatie -->
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Belangrijk voor het werken met opdrachten</h4>
	</div>
	<div class="card-body">
		<p>
			Alle bedragen zijn exclusief BTW, behalve offertes en facturen.
		</p>
		<p>
			Een opdracht bestaat uit opdracht informatie, uren en producten.
		</p>
		<ul>
			<li>
				<p>
					Opdracht informatie
				</p>
				<p>
					Elke opdracht vereist een titel, een klant en een status. Eventueel kun je een beschrijving invullen.
				</p>
				<ul>
					<li>
						<p>De titel beschrijft wat de opdracht inhoudt.</p>
					</li>
					<li>
						<p>De klant is de persoon waaraan de opdracht gekoppeld is.</p>
					</li>
					<li>
						<p>De status laat zien waar in het proces de opdracht zich bevindt. Daarnaast laat het zien door wie actie ondernomen moet worden.<br>
							<a href="#kleuren-informatie">Bij elke status hoort een kleur, de uitleg daarvan kun je vinden onder "Betekenis status kleuren".</a>
						</p>
					</li>
				</ul>
			</li>
			<li>
				<p>
					Uren
				</p>
				<p>
					Wanneer je uren maakt voor een opdracht kun je deze via "Uren" invullen.<br>
					Elke rij met uren vereist een uurtype, gemaakt door, klantprijs, datum, starttijd, eindtijd en beschrijving. Het totaal aantal uren en de kosten voor de klant worden automatisch berekend. Optioneel is het invullen van kilometers en reistijd (in minuten).
				</p>
				<ul>
					<li>
						<p>
							Het uurtype bepaalt de klantprijs. Voor uren die gerekend moeten worden aan de klant kies je "Standaard", voor uren die gemaakt zijn voor een product (alleen wanneer deze ook is toegevoegd in sectie producten) kies je "Product".<br>
							Wanneer je een nieuwe uursoort kiest past de klantprijs zich automatisch aan en worden de kosten voor de klant opnieuw berekend.
						</p>
					</li>
					<li>
						<p>
							De gemaakt door invoer bepaalt door wie de uren gemaakt zijn.<br>
							Zorg ervoor dat deze kolom goed ingevuld is, als de verkeerde persoon gekozen is kloppen de uren die uitbetaald moeten worden niet.
						</p>
					</li>
					<li>
						<p>
							De klantprijs bepaalt de prijs die aan de klant wordt doorgerekend.
						</p>
					</li>
					<li>
						<p>
							De datum bepaalt wanneer de uren gemaakt zijn.<br>
							De uren die uitbetaald moeten worden worden gesorteerd op deze datum, bij verkeerde invoer komen deze in de verkeerde maand terecht.
						</p>
					</li>
					<li>
						<p>
							De starttijd is de tijd wanneer de uren gestart zijn zonder reistijd, reistijd dient ingevuld te worden in het veld reistijd (in minuten).
						</p>
					</li>
					<li>
						<p>
							De eindtijd is de tijd wanneer de uren ten einde zijn zonder reistijd, reistijd dient ingevuld te worden in het veld reistijd (in minuten).
						</p>
					</li>
					<li>
						<p>
						 	Het veld aantal uren wordt automatisch berekend en laat zien hoe lang er gewerkt is.
						</p>
					</li>
					<li>
						<p>
						 	Het veld kosten klant wordt automatisch berekend en laat zien wat de kosten voor de klant zijn voor deze uren.
						</p>
					</li>
					<li>
						<p>
						 	Het veld kilometers bevat het aantal gereden kilometers die bij een bepaald uur horen.
						</p>
					</li>
					<li>
						<p>
							Het veld reistijd (in minuten) bevat het aantal gereden minuten die bij een bepaald uur horen. Let op: reken bij uren alleen de uren die daadwerkelijk bij de klant gemaakt zijn, en declareer de gereden minuten in het veld reistijd (in minuten).
						</p>
					</li>
					<li>
						<p>
						 	Het veld beschrijving bevat informatie wat er in de uren is gedaan.
						</p>
					</li>
				</ul>
			</li>
			<li>
				<p>
					Producten
				</p>
				<p>
					Wanneer je een offerte maakt of een product verkoopt kun je deze via "Producten" registreren.<br>
					Elke rij met een product vereist een product naam, inkoopprijs, klantprijs en aantal. De opbrengst wordt automatisch berekend.<br>
					Optionele velden voor een <b>offerte</b> om in te vullen zijn leverancier, bestelnummer en besteld door. Deze velden zijn vereist wanneer de producten daadwerkelijk <b>besteld worden</b>.<br>
					Optioneel is het invullen van een beschrijving voor het product.
				</p>
				<ul>
					<li>
						<p>
							De product naam is de naam van het product, het product moet zijn aangemaakt als product in de "Producten" tabel. 
						</p>
					</li>
					<li>
						<p>
							De leverancier is het bedrijf waar het product is aangekocht. Dit veld is optioneel, maar is vereist wanneer er een bestelnummer of besteld door is opgegeven.
						</p>
					</li>
					<li>
						<p>
							Het bestelnummer is het nummer dat is gemaakt door de leverancier over de desbetreffende bestelling. Dit veld is optioneel, maar is vereist wanneer er een leverancier of besteld door is opgegeven.
						</p>
					</li>
					<li>
						<p>
							Besteld door is de persoon die de producten heeft besteld. Een besteld product komt in de tabel met bestelde producten per maand te staan. Dit veld is optioneel, maar is vereist wanneer er een leverancier of bestelnummer is opgegeven.		
						</p>
					</li>
					<li>
						<p>
							De inkoopprijs is de (potentiële) prijs die betaald is voor het product aan de leverancier.
						</p>
					</li>
					<li>
						<p>
							De klantprijs is de prijs die we de klant rekenen.
						</p>
					</li>
					<li>
						<p>
						 	Het aantal bepaalt samen met de inkoopprijs en klantprijs de opbrengst.
						</p>
					</li>
					<li>
						<p>
						 	De opbrengst wordt automatisch berekend op basis van de inkoopprijs, klantprijs en het aantal.
						</p>
					</li>
					<li>
						<p>
						 	Het veld beschrijving bevat informatie over het product (bijvoorbeeld specificaties van een laptop).
						</p>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</div>
<!--/ Order informatie -->

<!-- Kleuren informatie -->
<div class="card" id="kleuren-informatie">
	<div class="card-header">
		<h4 class="card-title">Betekenis status kleuren</h4>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Kleur</th>
					<th>Contextual Class</th>
					<th>Betekenis</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="table-primary"></td>
					<td>Primary</td>
					<td>
						<p>
							Er is actie vereist vanuit ons
						</p>
					</td>
				</tr>
				<tr>
					<td class="table-info"></td>
					<td>Info</td>
					<td>
						<p>
							Wachten op actie vanuit klant/bedrijf
						</p>
					</td>
				</tr>
				<tr>
					<td class="table-warning"></td>
					<td>Warning</td>
					<td>
						<p>
							Er is actie vereist vanuit één van ons
						</p>
					</td>
				</tr>
				<tr>
					<td class="table-secondary"></td>
					<td>Secondary</td>
					<td>
						<p>
							Afspraak is gemaakt
						</p>
					</td>
				</tr>
				<tr>
					<td class="table-success"></td>
					<td>Success</td>
					<td>
						<p>
							De opdracht is succesvol afgesloten
						</p>
					</td>
				</tr>
				<tr>
					<td class="table-danger"></td>
					<td>Danger</td>
					<td>
						<p>
							De opdracht is foutief afgesloten
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!--/ Kleuren informatie -->
@endsection
