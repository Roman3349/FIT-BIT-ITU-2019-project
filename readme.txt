# ITU

Půjčovna sportovního vybavení

## Instalace

### 1. Stažení repozitáře

Pokud již nemáte stažené zdrojové kódy, tak je můžete stáhnout pomocí příkazu níže.

```
git clone --depth 1 https://gitlab.com/Roman3349/itu.git
```

### 2. Stažení composeru

Pro instalaci potřebných závislotí je potřeba stáhnout správce balíčků Composer. A to uděláte pomocí příkazů, které nalezenete níže.

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

### 3. Stažení závislostí

Pomocí příkazu níže stáhnete potřebné závislosti.

```
php composer.phar install --no-dev --optimize-autoloader
```

### 4. Nastavení databáze

V souboru `app/config/common.neon` upravte nastavení databáze:

```
parameters:
	database:
		host: DATABÁZOVÝ_SERVER
		dbname: JMÉNO_DATABÁZE
		user: UŽIVATEL
		password: HESLO
```

### 5. Inicializujte databázi

Databázi inicializujete pomocí dvou příkazů, které naleznete níže a které se vás budou ptát, zda chcete provést změny. A tyto hlášení potvrdíte stisknutím kláves `y` a `Enter`.

```
./bin/console migrations:migrate
./bin/console doctrine:fixtures:load
```

### 6. Spusťte webserver

Pokud aplikaci chcete pouze vyzkoušet na svém počítači, tak nejjednodušší způsob spuštění webserveru je pomocí příkazu

```
php -S [::]:8080 -t www/
```
a poté v prohlížeči navštivte stránku http://localhost:8080.


## Použité komponenty

- [Nette framework](https://nette.org/) - PHP framework pro tvorbu webových aplikací
- [Bootstrap 4](https://getbootstrap.com/) - CSS/JS framework pro tvorbu webových aplikací
- [FontAwesome](https://fontawesome.com/) - sada vektorových ikon pro webové aplikace
- [Doctrine](https://www.doctrine-project.org/) - ORM framework pro PHP
- [Contributte DataGrid](https://contributte.org/packages/contributte/datagrid/) - DataGridy pro Nette framework
- [Contributte Live form validation](https://contributte.org/packages/contributte/live-form-validation.html) - validace formulářů na straně klienta
- [Contributte Transtation](https://contributte.org/packages/contributte/translation.html) - překlady do více jazyků pro Nette framework
