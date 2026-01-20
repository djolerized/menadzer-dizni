=== Izbor Dizni ===
Contributors: Your Name
Tags: nozzle, agriculture, interactive, presentation
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Interaktivna prezentacija izbora dizni po kulturama sa drag & drop interfejsom.

== Description ==

Plugin omogućava interaktivan prikaz izbora dizni po kulturama, vizuelno identičan PowerPoint prezentaciji.

**Karakteristike:**

* Custom Post Type za dizne sa tehničkim podacima
* Taxonomy za kulture (useve)
* Drag & drop interfejs u adminu za pozicioniranje dizni na slikama kultura
* Frontend prikaz sa interaktivnim popup-ima
* Responsive dizajn
* Shortcode podrška

**Zahtevi:**

* WordPress 5.0+
* Advanced Custom Fields (ACF) plugin ili ACF Pro

== Installation ==

1. Uploadujte `izbor-dizni` folder u `/wp-content/plugins/` direktorijum
2. Instalirajte i aktivirajte Advanced Custom Fields (ACF) plugin
3. Aktivirajte Izbor Dizni plugin kroz 'Plugins' meni u WordPress-u
4. Plugin će automatski registrovati CPT i taksonomiju

== Usage ==

**Admin:**

1. Dodajte nove dizne kroz "Dizne" meni
   - Unesite naziv dizne
   - Postavite sliku dizne (Featured Image)
   - Popunite tehnička polja (primena, vreme primene, materijal, itd.)

2. Dodajte kulture kroz "Dizne > Kulture"
   - Kreirajte novu kulturu (npr. Kukuruz, Pšenica)
   - Dodajte sliku kulture u fazama rasta
   - Koristite Drag & Drop interfejs za pozicioniranje dizni na slici

**Drag & Drop interfejs:**

1. Na edit stranici kulture, nakon što dodate sliku, videćete drag & drop interfejs
2. Kliknite "Dodaj diznu" i izaberite diznu iz liste
3. Ikonica dizne će se pojaviti na slici
4. Prevucite ikonicu na željeno mesto
5. Pozicije se automatski čuvaju kada sačuvate kulturu

**Frontend:**

Koristite shortcode za prikaz:

[izbor_dizne]

Ili direktno prikazati specifičnu kulturu:

[izbor_dizne kultura="kukuruz"]

**Alternativno:**

Možete koristiti URL parametar:

yoursite.com/page/?kultura_id=123

== Frequently Asked Questions ==

= Da li plugin radi bez ACF? =

Ne, plugin zahteva Advanced Custom Fields (ACF) plugin da bi radio.

= Kako mogu da promenim poziciju dizne? =

Jednostavno prevucite ikonicu dizne na novu poziciju u drag & drop interfejsu na edit stranici kulture.

= Mogu li koristiti više dizni na jednoj kulturi? =

Da, možete dodati neograničen broj dizni na svaku kulturu.

== Changelog ==

= 1.0.0 =
* Inicijalno izdanje
* CPT za dizne
* Taxonomy za kulture
* Drag & drop interfejs
* Frontend shortcode
* Responsive dizajn

== Upgrade Notice ==

= 1.0.0 =
Inicijalno izdanje.
