PROJECT: Izbor dizni – interaktivna PPT prezentacija (WordPress plugin)
TARGET: Codex – kompletna funkcionalna specifikacija

==================================================
1. OPIS PROJEKTA
==================================================
Cilj plugin-a je da omogući interaktivan prikaz izbora dizni po kulturama,
vizuelno identičan PowerPoint prezentaciji.

Plugin NE vrši kalkulacije i NE donosi automatske odluke.
Sve veze između kultura i dizni definiše administrator (agronom).

Korisnik:
- bira kulturu
- vidi sliku kulture u fazama rasta
- vidi slike dizni postavljene iznad slike kulture
- klikom na diznu dobija popup sa tehničkim podacima (tabela)

==================================================
2. ENTITETI I STRUKTURA PODATAKA
==================================================

----------------------------------
2.1 CPT: DIZNA
----------------------------------
Slug: dizna
Public: true
Supports:
- Title (naziv dizne)
- Featured image (slika dizne)

Jedna dizna može biti korišćena na više kultura.

----------------------------------
ACF POLJA – DIZNA
----------------------------------

GRUPA: Osnovni podaci
- primena (taxonomy ili select)
  Primer: Tretman herbicidima
- vreme_primene (text)
  Primer: na crno; od 2 lista do cvetanja
- materijal (text)
  Primer: Delrin
- sema_mlaza (image)

GRUPA: Preporučeni radni parametri
- radni_pritisak (text)
  Primer: 2–5 bar
- brzina_hoda (text)
  Primer: 8–12 km/h
- protok (text)
  Primer: 140–200 l/ha

==================================================
2.2 TAXONOMY: KULTURA
==================================================
Slug: kultura
Type: hierarchical

Primeri:
- Kukuruz
- Pšenica
- Soja
- Suncokret
- Šećerna repa

----------------------------------
ACF POLJA – KULTURA
----------------------------------

- slika_kulture_faze (image)
  Jedna velika slika sa svim fazama rasta kulture

REPEATER: pozicionirane_dizne
Svaki red = jedna dizna postavljena iznad slike

Polja:
- dizna (Post Object → CPT dizna)
- pozicija_x (number, %)
- pozicija_y (number, %)

==================================================
3. ADMIN WORKFLOW
==================================================

1. Administrator kreira dizne (CPT Dizna)
2. Popunjava sve tehničke podatke dizne
3. Kreira kulturu (taxonomy Kultura)
4. Uploaduje sliku kulture u fazama rasta
5. Dodaje dizne iznad slike:
   - bira diznu
   - unosi X/Y poziciju u procentima
6. Snima promene

Napomena:
- ista dizna može biti dodata na više kultura
- dizne se pozicioniraju manuelno
- drag & drop je opcija za Phase 2

==================================================
4. FRONTEND FUNKCIONALNOST
==================================================

----------------------------------
4.1 Početni ekran – izbor kulture
----------------------------------
Shortcode:
[izbor_dizne]

Prikazuje:
- listu dostupnih kultura

----------------------------------
4.2 Ekran kulture
----------------------------------
Prikazuje:
- sliku kulture u fazama rasta
- slike dizni iznad slike (absolute positioning)
- link ili dugme „Odabir kulture“

----------------------------------
4.3 Popup dizne
----------------------------------
Klik na diznu otvara popup koji sadrži:

- naziv dizne
- primenu
- sliku dizne
- vreme primene
- materijal
- šemu mlaza
- tabelu preporučenih radnih parametara

==================================================
5. SHORTCODE API
==================================================

Glavni prikaz:
[izbor_dizne]

Direktna kultura:
[izbor_dizne kultura="kukuruz"]

==================================================
6. TEHNIČKE NAPOMENE
==================================================

- Plugin je nezavisan od teme
- Responsive (desktop + mobile)
- JavaScript za popup i pozicioniranje
- Bez kalkulacija i automatike
- Struktura 1:1 sa PowerPoint prezentacijom

==================================================
7. FAZA 2 (OPCIONO)
==================================================

- Drag & drop pozicioniranje dizni u adminu
- Vizuelni preview u admin panelu
- Više slika po kulturi
- Multilingual support

==================================================
KRAJ SPECIFIKACIJE
==================================================
