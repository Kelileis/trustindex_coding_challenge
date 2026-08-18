# Trustindex Coding Challenge

Véleménykezelő webalkalmazás, amely lehetővé teszi a felhasználók számára, hogy cégekről értékeléseket írjanak, megtekinthessék az egyes véleményeket, keressenek cégek neve alapján, valamint megtekinthessék a cégek aggregált statisztikáit.

## Technológiák

- PHP 8.2+
- Symfony 7.4
- Doctrine ORM (MySQL 8.0 / SQLite teszteléshez)
- Twig template engine
- PHPUnit 13

## Telepítés

### Előfeltételek

- PHP 8.2 vagy újabb (a következő kiterjesztésekkel: mbstring, xml, curl, zip, pdo_mysql)
- Composer
- MySQL 8.0 vagy Docker

### Parancsok

```bash
# Függőségek telepítése
composer install

# Környezeti fájl másolása
cp .env .env.local

# Adatbázis kapcsolat beállítása (.env.local)
# DATABASE_URL="mysql://felhasználó:jelszó@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"

# Adatbázis létrehozása
php bin/console doctrine:database:create

# Migrációk futtatása
php bin/console doctrine:migrations:migrate

# Fejlesztői szerver indítása
symfony serve
# vagy
php -S 127.0.0.1:8000 -t public/
```

## Alkalmazás funkciói

| Útvonal | Leírás |
|---|---|
| `/` | Kezdőlap - legújabb 20 vélemény megjelenítése, keresés cégnév alapján |
| `/new-review` | Új vélemény beküldése (cég neve, értékelés 1-5, szöveg, email) |
| `/review/{id}` | Egy vélemény részletes megjelenítése |
| `/companies` | Cégek statisztikái (értékelések száma, átlagértékelés) |

## Tesztelés

```bash
# Tesztfuttatás
php bin/phpunit

# Egyedi teszt osztály
php bin/phpunit tests/Controller/ReviewControllerTest.php
php bin/phpunit tests/Entity/ReviewTest.php
```

## Munkaidő napló

### 1. feladat: Projekt alapok és entitás tervezés (~45 perc)

### 2. feladat: Űrlap és adatkezelés (~45 perc)
### 3. feladat: Controller és útvonalak (~30 perc)
### 4. feladat: Sablonok és frontend (~60 perc)
### 5. feladat: Tesztelés (~30 perc)
### 6. feladat: Finomítás és dokumentáció (~30 perc)
