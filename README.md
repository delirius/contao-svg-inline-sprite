# SVG Inline / SVG Sprite

Das Inhaltselement ermöglicht die Ausgabe von Inline-SVG und SVG Sprite (Symbol). Dabei wird das SVG direkt in das HTML-Markup geschrieben und kann mit CSS angepasst werden.

## Contao
Contao: ^5.0<br>
PHP:  ^8.1<br>

## Screenshot

![Alt text](docs/svg-sprite.png?raw=true "Eingabemaske SVG Sprite")


## SVG Inline

Der Inhalt der SVG Datei wird gelesen und als HTML-Markup ausgegeben.

`<svg><path d="M6 12h36v4.031h-36v-4.031zM6 25.969v-3.938h36v3.938h-36zM6 36v-4.031h36v4.031h-36z"></path></svg>`


## SVG Sprite (Symbol)

Ein SVG Sprite beinhaltet mehrere Symbole. Der Inhalt der SVG Datei wird gelesen und es kann ein Symbol ausgewählt werden. [svg-sprite-example.svg](docs/svg-sprite-example.svg)


Bei der Ausgabe wird das Symbol sowie das komplette SVG-Sprite in das HTML-Markup geschrieben. Das Symbol wird mit `use` verlinkt. Das SVG-Sprite wird am Ende der Seite in einen unsichbarem Element platziert. Das SVG-Sprite wird nur einmal ausgegeben, auch wenn mehrere Symbole auf einer Seite verwendet werden. 

**Symbol**

`<svg><use href="#icon-menu"></use></svg>`

**Am Ende der Seite**

```
<svg>
<defs>
<symbol id="icon-menu" viewBox="0 0 32 32">
<path d="M4 24v-2.222h24v2.222h-24zM4 17.111v-2.222h24v2.222h-24zM4 10.222v-2.222h24v2.222h-24z"></path>
</symbol>
<symbol id="icon-close" viewBox="0 0 32 32">
<path d="M8.378 25.178l-1.555-1.555 7.622-7.622-7.622-7.622 1.555-1.555 7.622 7.622 7.622-7.622 1.555 1.555-7.622 7.622 7.622 7.622-1.555 1.555-7.622-7.622-7.622 7.622z"></path>
</symbol>
...
</defs>
</svg>
```

## Gut zu wissen

**SVG Sprite Generatoren**

[icomoon.io](https://icomoon.io)

[svgsprit.es](https://svgsprit.es)

Die Farbe wird in CSS mit `fill:#336699` oder `stroke:#336699` definiert. Wenn im SVG die Farbe mit `currentColor` definiert ist, übernimmt es die Farbe des übergeordnetem Element und wird mit `color:#336699` definiert.

Die meisten Browser verhindern das laden einer SVG-Datei von einer lokalen Festplatte aus (file://, C:\\). Wird das SVG direkt in das HTML-Markup geschrieben, entsteht dieses Problem nicht.

