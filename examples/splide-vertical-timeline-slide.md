# Beispiel für Splide Timeline Slide  
Es werden mehrere Inhaltselemente aneinander gereiht. Diese können über Pfeiltasten und Pagination vor und zurück bewegt werden. Die Inhalte werden vertikal anstatt horizontal verschoben. Statt der Pagination werden Jahreszahlen dargestellt.  

## Voraussetzungen  
### Installation Contao HTML Attributes Bundle  
Zusätzlich wird die Installation der Erweiterung Contao HTML Attributes Bundle vorausgesetzt.  
[Contao HTML Attributes Bundle](https://github.com/berecont/contao-html-attributes-bundle)
Die Erweiterung kann über  
`require berecont/contao-html-attributes-bundle`  
über den Composer oder über den Contao Manager über die Paketsuche installiert werden.  
Diese Erweiterung ermöglicht es den Slides (den einzelnen Inhaltselementen) eine `data-year`-Attribut mitzugeben. Dieses HTML Attribut ist für die Erkennung und Weiterverarbeitung in der `splide-init.js` notwendig.  

### Einträge HTML Attribute  
In den Inhaltselementen werden als data-Attribute die Jahreszahlen eingetragen  
Attribut | Wert  
--- | ---  
data-year | 1996   

Ein Einträge pro Inhaltselement. Aus diesen Attributen werden im Frontend die "Menüpunkte" für die Timeline.   

### Zusätzliche Slider-Klasse `splide--timeline`  
Mit diesem Eintrag im Feld `Zusätzliche Slider-Klassen` wird das JS initialisiert.  
Der Eintrag, die Klasse muss `splide--timeline` lauten.

### Anpassung der `splide-init.js`  
Es ist notwendig folgende Anpassung in der `splide-init.js` durchzuführen:  
ab Zeile 112 einfügen  
``` javascript
    /*
    * staRt timeline extension
    */

    if (element.classList.contains('splide--timeline')) {
        splide.on('pagination:mounted', (data) => {
            data.list.classList.add(
                'splide__pagination--timeline'
            );

            data.items.forEach((item) => {
                const slide =
                    splide.Components.Slides.getAt(
                        item.page
                    );

                if (!slide) {
                    return;
                }

                const yearElement =
                    slide.slide.querySelector(
                        '[data-year]'
                    );

                const year =
                    yearElement?.dataset.year;

                if (!year) {
                    return;
                }

                item.button.textContent = year;
                item.button.setAttribute(
                    'aria-label',
                    `Zum Jahr ${year}`
                );
            });
        });
    }        

    /*
    * stop timeline extension
    */
```  


## Einstellungen   

Feld | Eintrag
--- | ---
Übergang | Slide  
Slide-Intervall | 0  
Übergangsgeschwindigkeit | 400  
Slide-Versatz | 0  
Kontinuierlich | &#10003;  
Aktiven Slide zentrieren |  
Slides pro ansicht | 1  
Slides pro Wechsel | 1  
Abstand zwischen Slides |   
AutoScroll aktivieren |   
AutoScroll-Geschwindigkeit |   
Bei Hover pausieren |   
Bei Tastaturfokus pausieren |   
Navigationspfad ausblenden |   
Pagination ausblenden |   
Autoplay-Steuerung anzeigen |  
zusätzliche Slider-Klassen | splide--timeline  
Breakpoints |   
Zusätzliche Slide-Optionen | <pre>{<br>  "direction": "ttb",<br>  "height": "30rem",<br>  "wheel": true<br>}</pre>  

## Inhaltselemente  
In den Kindelementen werden zum Beispiel 7 einzelne Inhaltselemente vom Typ _Gruppenelement_ eingefügt. Je _Gruppenelement_ wird ein HTML-Attribut gesetzt.   

## Frontend  
Im Frontend wird ein Slider angezeigt, dessen Inhalte über die eingeblendeten Pfeiltasten und die Pagination bedient werden können.  
Die Pfeiltasten werden oben und unten angeführt. Mit der Pagination werden die Jahreszahlen aus _data-year_ dargestellt und dienen als Menüpunkte.  
Tipp: CSS hilft beim Stylen.

### weitere Infos  
[Splide](https://splidejs.com/) – externe Weiterleitung zur Webseite  
[Github - HTML Attributes](https://github.com/berecont/contao-html-attributes-bundle)

