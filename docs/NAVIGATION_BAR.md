# Menú de navegació

El menú Off-Canvas (accessible via hamburger icon) es configura de la següent forma:

## Afegir entrada al menú

- Taula: menus
- Cal afegir una entrada a aquesta taula per cada apartat de menú
- Per fer el canvi permanent s'ha de modificar a **helpers.php** funció **initialize_menus**
- Les icones: https://material.io/tools/icons/?style=baseline
- Per aplicar canvis: 

```php
php artisan migrate:tenant:fresh --seed iesebre -v
```

Exemple:

```
 Menu::firstOrCreate([
            'icon' => 'build',
            'text' => 'Incidències',
            'href' => '/incidents'
        ]);
```

