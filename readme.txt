=== SMM Bar ===
Contributors: sergej.mueller
Tags: social, media, marketing, admin bar
Donate link: http://flattr.com/profile/sergej.mueller
Requires at least: 3.3
Tested up to: 3.4
Stable tag: trunk



Monitoring für Social Media Marketing: Verbreitung der einzelnen Blogseiten als Kennzahlen innerhalb der Admin Bar.


== Description ==

= Monitoring =
Das Plugin *SMM Bar* (SMM = Social Media Monitoring / Social Media Marketing) ermittelt die Anzahl der Shares für Google+, Facebook, Twitter und präsentiert die Werte übersichtlich in der WordPress Admin Bar. Zielsetzung: Erfolge der Social Media Optimization (SMO) ablesen.

= Funktionsweise =
Beim Aufruf eines Artikels im Browser fragt das Plugin die Schnittstellen der Social-Dienste ab und bildet die Zahlen in der Admin Bar ab. Es spielt dabei keine Rolle, ob der Administrator die Seite im Back- oder Frontend eines Blogs aufgerufen hat: Die aktivierte Admin Bar ist der Schlüssel für die Anzeige der Metriken.

Die dargestellten Kennzahlen werden für 1 Stunde im WordPress-Cache aufbewahrt, um die Performance der Aufrufe zu schonen.

Auf welchen Seiten ist die *SMM Bar* aktiv? **Veröffentlichte** Artikel, statische Seite und Custom Post Types profitieren vom Plugin. Andere Seitenarten werden nicht berücksichtigt.

Übrigens kommt das Plugin für Social Media ganz ohne Einstellungen aus. Installieren, aktivieren, bereit. Beim Aufruf der Blogseiten im Browser erscheinen die farbigen Etiketten mit Social Media Diensten und entsprechenden Zählern innerhalb der Admin Bar.

= Video =
[vimeo https://vimeo.com/40609217]

= Hooks =
Da *SMM Bar* die Werte intern zwischenspeichert, kann das aktive WordPress Theme auf diese Daten zugreifen und an der gewünschten Stelle im Theme-Template ausgeben (z.B. um ein Link oder Badge zu gestalten). Dafür verfügt das Social Media Tool über folgende Ausgabe-Hooks:

* Für Twitter
`<?php do_action('smmbar-data-count', 'twitter') ?>`

* Für Google+
`<?php do_action('smmbar-data-count', 'gplus') ?>`

* Für Facebook
`<?php do_action('smmbar-data-count', 'facebook') ?>`

Über den Filter `smmbar-data-item` können zusätzliche Dienste zur Anzeige hinzugefügt und vorhandene Services editiert werden.

= Systemanforderungen =
* PHP 5.2.0
* WordPress 3.3

= Autor =
* [Google+](https://plus.google.com/110569673423509816572 "Google+")
* [Portfolio](http://ebiene.de "Portfolio")



== Changelog ==

= 0.0.1 =
* Live auf wordpress.org



== Screenshots ==

1. SMM Bar Optionen