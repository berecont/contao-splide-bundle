# Contao Splide Bundle  

## ** Diese Erweiterung ist in der Entwicklung.<br>** Könnte Fehler beinhalten.<br>** Noch nicht für den produktiven Einsatz freigegeben.

## Übersicht

Das Bundle stellt ein verschachteltes Contao-Inhaltselement auf Basis von [Splide](https://github.com/Splidejs/splide) bereit.  
Mehrere Slider pro Seite werden unterstützt. Assets werden pro Seite nur einmal geladen.

## Backend-Felder

### Übergang und Ablauf

| Feld | Beschreibung |
|---|---|
| Übergang | Auswahl des Übergangs. |
| Slide-Intervall | Millisekunden zwischen automatischen Slides.<br>`0` deaktiviert Autoplay.<br>Bei AutoScroll ohne Wirkung. |
| Übergangsgeschwindigkeit | Dauer des Slide-Wechsels in Millisekunden |
| Slide-Versatz | Startslide (erste Position = 0) |
| Kontinuierlich | Aktiviert den Endlosbetrieb (`loop`) |
| Aktiven Slide zentrieren | Zentriert den aktiven Slide.<br>Nicht bei `Fade`|

### Darstellung

-   Slides pro Ansicht
-   Slides pro Wechsel
-   Abstand zwischen Slides (z.B. `1rem`, `16px`, `2%`)

### AutoScroll

-   AutoScroll aktivieren
-   Geschwindigkeit (`0.5`, `-0.5`, `1`, `-1`)

AutoScroll verwendet intern:

-   `type = loop`
-   `drag = free`
-   normales Autoplay wird deaktiviert
-   `pauseOnHover` und `pauseOnFocus` werden übernommen

### Verhalten

-   Bei Hover pausieren
-   Bei Tastaturfokus pausieren

### Navigation

-   Pfeile ausblenden
-   Pagination ausblenden
-   Autoplay-Schaltfläche anzeigen

### Barrierefreiheit

ARIA-Label für Screenreader.

## Breakpoints

Beispiel:

``` json
{
  "992": {
    "perPage": 3
  },
  "768": {
    "perPage": 2
  },
  "480": {
    "perPage": 1,
    "gap": "0.5rem"
  }
}
```

## Zusätzliche Splide-Optionen  

Beispiel:  

``` json
{
  "wheel": true,
  "releaseWheel": true,
  "keyboard": "focused",
  "rewindSpeed": 800
}
```  

**Hinweis:** `autoScroll` wird ausschließlich über die Backend-Option aktiviert und gehört nicht in dieses JSON.

## Übergänge

### Slide

-   beliebig viele sichtbare Slides
-   unterstützt `perPage`
-   unterstützt `perMove`
-   unterstützt `focus: center`
-   unterstützt Loop

### Fade

Fade unterstützt technisch nur einen sichtbaren Slide.

Das Bundle erzwingt automatisch:

-   `perPage = 1`
-   `perMove = 1`

Backendwerte werden hierbei ignoriert.

### Zoom

Der Zoom-Übergang unterstützt dieselben Layoutoptionen wie der normale Slide.

### AutoScroll

AutoScroll verwendet immer den normalen Slide-Übergang.

Dabei werden automatisch gesetzt:

-   `type = loop`
-   `drag = free`

Normales Autoplay wird deaktiviert.

## Hinweise

-   Mehrere Slider pro Seite werden unterstützt.
-   CSS und JavaScript werden nur einmal eingebunden.
-   AutoScroll wird nur bei den entsprechenden Slidern gemountet.
-   Expertenoptionen überschreiben Standardoptionen, ausgenommen
    technische Einschränkungen wie Fade oder AutoScroll.

## Splide Guides  

[Splide Guides](https://splidejs.com/guides/)  

## Erweiterungen  

[Auto Scroll](https://splidejs.com/extensions/auto-scroll/) - ist bereits integriert  
[Intersection](https://splidejs.com/extensions/intersection/) - maybe coming soon
[Grid](https://splidejs.com/extensions/grid/) - maybe coming soon  
[Video](https://splidejs.com/extensions/video/)  - maybe coming soon  
[URL Hash](https://splidejs.com/extensions/url-hash/) - maybe coming soon  

## Themes  

Es stehen unterschiedliche Themes zur Verfügung. Diese sind nicht Teil des Bundles.
[Themes](https://splidejs.com/guides/themes/) - maybe coming soon