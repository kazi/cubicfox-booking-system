# Cubicfox Booking System

Kedves Cubicfox!

A repositoryban megtaláljátok a hotelszoba foglalási rendszer API backendjét.
Az alkalmazás indításához:

```
git clone https://github.com/kazi/cubicfox-booking-system.git .
docker-compose up -d
```

A foglalási rendszer egy Nginx alatt futó, MySQL-t használó Laravel alkalmazás.

##Config

###Adatbázis
Az alkalmazás beállításait a .env fileban láthatjátok, ebből az adatbázis kapcsolathoz a következőkre lesz szükségetek, ha egy adatbázis kezelővel kapcsolódni szeretnétek:
- DB_HOST=database
- DB_PORT=3306
- DB_DATABASE=booking 
- DB_USERNAME=cubicfoxmysql 
- DB_PASSWORD=cubicfoxmysql

###Seeding
Az alkalmazás tartalmaz seeder file-okat, amik a containerbe belépve a  
```
php aritsan migrate --seed
```
paranccsal futtathatók. Ezek létrehoznak 3 hotelt (Hotel model, hotels tábla) szokákkal (Room model, rooms tábla), és egy hónapra előre generált ajánlatokkal (Offer model, offers tábla).

###Defaults
További beállítási lehetőségekhez (pl. port mapping) a megfelelő Dockerfile-ok a /docker könyvtáron belül találhatók.

##Felépítés, működés

A foglalási rendszerben ajánlatokat listázhatunk hotelekre és szobákre, megadott időszakokra. Ezt bejelentkezés nélkül tehetjük. A szobák elérhetőségeit az offers táblában találjátok az egyes napokra. Itt naponként változtatjatjátok az árat, amit az ajánlatokban szummázva szerepel majd az adott időszakra.

A listázások felül a további műveletek bejelentkezés kötelesek. A rendszerben egy felhasználó fel van véve az alábbi email cím / jelszó kombinációval:

```
pbooking@example.com / 123456 
```

A bejelentkezést követő válaszban egy tokent kaptok, a további műveletekhez ezt Bearer tokenként csatolnotok kell. A foglalás létrehozás, listázás és törlés a token küldésével együtt lehetséges. 

A létrehozott foglalásokat (Reservation) a reservations táblában látjátok. Sikeres foglalás esetén az adott szobák az érintett napokra már nem foglalhatók,
és a foglalás ára sem változtatható a foglalt szobák árainak szerkesztésével.

##Endpointok

Az endpointok tesztelését Postman-nel végeztem, innét exportáltam egy collection-t, amit segítségetekre lehet. 

```
/postman/CubicFox Booking.postman_collection.json
```

Az endpointok alapvetően az OpenAPI ajánlás szerint készültek, így ahol keresés lehetséges, ott a megfelelő szűrési feltételek az alábbi logika szerint állíthatók elő:

```
http/:/...ENDPOINT...?FILTER[OPERATOR]=VALUE
```

A filter az adott szűrési mezők nevei, az operatorok jellemzően az [eq, gt, gte, lt, lte] értékek, a value pedig maga az érték, amire szűrni szeretnéket.

### GET /api/v1/offers

Példa request:

```
GET http://127.0.0.1:8080/api/v1/offers?firstDay[gte]=2023-02-20&lastDay[lte]=2023-02-24
```

Az összes ajánlatot listázzuk a 2023-02-20 - 2023-02-24 közti időszakra. A JSON válaszban az ajánlatok lapozható listáját találjátok. 

Példa válasz, ahol látjátok a hotel és a szoba nevét, valamint az árat is:

```
{
    "data": [
        {
            "roomId": 1,
            "name": "Elnöki szoba",
            "hotel": "Palatinus Grand Hotel",
            "totalPrice": "5000.00",
            "arrivalDate": "2023-02-20",
            "departureDate": "2023-02-24",
            "isAvailableForReservation": true
        },
        {
            "roomId": 2,
            "name": "Királyi szoba",
            "hotel": "Palatinus Grand Hotel",
            "totalPrice": "10000.00",
            "arrivalDate": "2023-02-20",
            "departureDate": "2023-02-24",
            "isAvailableForReservation": true
        },
        {
            "roomId": 3,
            "name": "Arany szoba",
            "hotel": "Hotel Kikelet",
            "totalPrice": "15000.00",
            "arrivalDate": "2023-02-20",
            "departureDate": "2023-02-24",
            "isAvailableForReservation": true
        },
        {
            "roomId": 4,
            "name": "Ezüst szoba",
            "hotel": "Hotel Kikelet",
            "totalPrice": "20000.00",
            "arrivalDate": "2023-02-20",
            "departureDate": "2023-02-24",
            "isAvailableForReservation": true
        },
        {
            "roomId": 5,
            "name": "Bronz szoba",
            "hotel": "Hotel Kikelet",
            "totalPrice": "25000.00",
            "arrivalDate": "2023-02-20",
            "departureDate": "2023-02-24",
            "isAvailableForReservation": true
        },
        {
            "roomId": 6,
            "name": "Vas szoba",
            "hotel": "Hotel Kikelet",
            "totalPrice": "30000.00",
            "arrivalDate": "2023-02-20",
            "departureDate": "2023-02-24",
            "isAvailableForReservation": true
        },
        {
            "roomId": 7,
            "name": "Luxus szoba",
            "hotel": "Fenyves Hotel***",
            "totalPrice": "35000.00",
            "arrivalDate": "2023-02-20",
            "departureDate": "2023-02-24",
            "isAvailableForReservation": true
        },
        {
            "roomId": 8,
            "name": "VIP szoba",
            "hotel": "Fenyves Hotel***",
            "totalPrice": "40000.00",
            "arrivalDate": "2023-02-20",
            "departureDate": "2023-02-24",
            "isAvailableForReservation": true
        },
        {
            "roomId": 9,
            "name": "Prémium szoba",
            "hotel": "Fenyves Hotel***",
            "totalPrice": "45000.00",
            "arrivalDate": "2023-02-20",
            "departureDate": "2023-02-24",
            "isAvailableForReservation": true
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8080/api/v1/offers?firstDay%5Bgte%5D=2023-02-20&lastDay%5Blte%5D=2023-02-24&page=1",
        "last": "http://127.0.0.1:8080/api/v1/offers?firstDay%5Bgte%5D=2023-02-20&lastDay%5Blte%5D=2023-02-24&page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8080/api/v1/offers?firstDay%5Bgte%5D=2023-02-20&lastDay%5Blte%5D=2023-02-24&page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8080/api/v1/offers",
        "per_page": 15,
        "to": 9,
        "total": 9
    }
}
```

### POST /api/auth/login

A bejelentkezéshez használható endpoint.

```
 POST http://127.0.0.1:8080/api/auth/login
```

A request body az alábbi JSON legyen:

```
{
    "email": "booking@example.com",
    "password": "123456"
}
```

Szintén fontos, hogy a kérésben az Accept header értéke 'application/json' legyen. 

Sikeres bejelentkezés esetén az alábbi példa választ kell, hogy kapjátok:

```
{
"status": true,
"message": "Sucessfully logged in.",
"token": "2|wFFWsPt3rmDZJOvngGiDxY9QJo3TNHMhn9C8MnxN"
}
```

A továbbiakban a tokenre szükség lesz, és Bearer tokenként kell továbbítsátok a kérésekkel!
Új login request esetén új token keletkezik.

### GET /api/v1/reservations

A bejelentkezett user a saját foglalásait listázhatja vele. Pl:

```
GET 127.0.0.1:8080/api/v1/reservations
```

Példa válasz:

```
{
    "data": [
        {
            "id": 2,
            "userId": 1,
            "roomId": 1,
            "arrivalDate": "2023-02-20",
            "departureDate": "2023-02-24",
            "price": "5000.00",
            "room": {
                "id": 1,
                "name": "Elnöki szoba",
                "hotel": {
                    "id": 1,
                    "name": "Palatinus Grand Hotel"
                }
            }
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8080/api/v1/reservations?page=1",
        "last": "http://127.0.0.1:8080/api/v1/reservations?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8080/api/v1/reservations?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8080/api/v1/reservations",
        "per_page": 15,
        "to": 1,
        "total": 1
    }
}
```

### POST /api/v1/reservations

A bejelentkezést követően szabad szobát foglalhatunk egy adott időszakra. 
Fontos, hogy az adott szobának legyen érvényes ajánlati rekordja az offers táblában az adott időszak összes napjára.
A kérésben - ahogy a többi hasonló esetben is - továbbítani kell a bearer tokent, és az accept headernek application/json típusúnak kell lennie.

Példa request: 

```
 POST 127.0.0.1:8080/api/v1/reservations
```

A request body az alábbiak szerint kell, hogy kinézzen:

```
{
    "roomId": 1,
    "arrivalDate": "2023-02-20",
    "departureDate": "2023-02-24"
}
```

Sikeres foglalás esetén a válaszban szerepelnek a foglalás részletei: 

```
{
    "data": {
        "id": 2,
        "userId": 1,
        "roomId": 1,
        "arrivalDate": "2023-02-20",
        "departureDate": "2023-02-24",
        "price": 5000
    }
}
```

Sikertelen foglalás esetén a hibaüzenetet a válasz JSON message attribútumában találjátok.

### DELELTE /api/v1/reservations

Adott foglalás törlésére használható endpoint.

Példa request:

```
 DELETE 127.0.0.1:8080/api/v1/reservations
```

A request body tartalmazza a foglalás ID-ját:

```
{
    "reservationId": 1
}
```

Sikeres kérés esetén a válasz üres lesz, 200 OK státusszal.

##Unit testek

Az alkalmazás 17 tesztet tartalmaz 25 assertionnel. A teszteket dockerben található PHP 8.0.5 alatt futtattam.
