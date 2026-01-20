PROJECT: Izbor dizni – interaktivna PPT prezentacija (WordPress plugin)
TARGET: Codex – kompletna funkcionalna specifikacija (DOPUNJENA – DRAG & DROP)

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

NAPOMENA:
pozicija_x i pozicija_y se NE UNOSE ručno u finalnoj verziji,
već se automatski popunjavaju kroz drag & drop interfejs u adminu.

==================================================
3. ADMIN DRAG & DROP INTERFEJS (OBAVEZNO)
==================================================

----------------------------------
3.1 Admin prikaz kulture
----------------------------------
Na edit stranici kulture mora postojati:
- preview slike kulture (slika_kulture_faze)
- overlay sloj iznad slike
- prikaz svih već dodatih dizni kao ikonice

----------------------------------
3.2 Dodavanje dizne na sliku
----------------------------------
Admin workflow:

1. Admin klikne na dugme „Dodaj diznu“
2. Otvara se modal ili dropdown sa listom svih CPT dizni
3. Admin izabere diznu
4. Ikonica dizne se pojavljuje iznad slike kulture
5. Admin PREVLAČI (drag) ikonicu dizne na željeno mesto
6. Plugin automatski:
   - računa X i Y poziciju u procentima
   - upisuje vrednosti u pozicija_x i pozicija_y
7. Pozicija se snima pri Save / Update kulture

----------------------------------
3.3 Izmena i brisanje
----------------------------------
- Svaka dizna na slici može:
  - ponovo da se prevuče (reposition)
  - da se ukloni (remove)
- Vizuelni preview uvek mora odgovarati stvarnom frontend prikazu

----------------------------------
3.4 Tehnički zahtevi (admin)
----------------------------------
- JavaScript (vanilla JS ili jQuery)
- HTML5 drag & drop ili mouse events
- Pozicioniranje u procentima (%)
- Responsive ponašanje (skaliranje slike)

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
- JavaScript za popup i drag & drop
- Bez kalkulacija i automatike
- Struktura 1:1 sa PowerPoint prezentacijom

==================================================
7. FAZA 2 (REZERVA – AKO TREBA)
==================================================

- Više slika po kulturi (različite primene)
- Zoom slike u adminu
- Copy/paste pozicija dizni između kultura
- Multilingual support

==================================================
KRAJ SPECIFIKACIJE
==================================================
