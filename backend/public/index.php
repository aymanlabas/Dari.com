<?php
/**
 * L9it API — Self-contained PHP Backend
 * No Composer, no framework dependencies.
 * Uses MySQL via XAMPP.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:3000'); header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ─── Database ──────────────────────────────────────────────────────────────────

$db = new PDO('mysql:host=localhost;dbname=l9it;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
]);

// ─── Schema ────────────────────────────────────────────────────────────────────

function initSchema(PDO $db) {
    $db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        first_name TEXT NOT NULL,
        last_name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        phone TEXT NOT NULL DEFAULT '',
        password TEXT NOT NULL,
        avatar TEXT,
        city TEXT,
        role TEXT NOT NULL DEFAULT 'USER',
        is_verified INTEGER NOT NULL DEFAULT 0,
        is_blocked INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    );

    CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        icon TEXT,
        parent_id INTEGER,
        is_active INTEGER NOT NULL DEFAULT 1
    );

    CREATE TABLE IF NOT EXISTS regions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        code TEXT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS cities (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        region_id INTEGER NOT NULL REFERENCES regions(id)
    );

    CREATE TABLE IF NOT EXISTS listings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        description TEXT NOT NULL DEFAULT '',
        price REAL NOT NULL DEFAULT 0,
        currency TEXT NOT NULL DEFAULT 'MAD',
        condition TEXT NOT NULL DEFAULT 'Neuf',
        status TEXT NOT NULL DEFAULT 'draft',
        category_id INTEGER,
        seller_id INTEGER NOT NULL REFERENCES users(id),
        region_id INTEGER,
        city_id INTEGER,
        neighborhood TEXT,
        views_count INTEGER NOT NULL DEFAULT 0,
        favorites_count INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL DEFAULT (datetime('now')),
        updated_at TEXT NOT NULL DEFAULT (datetime('now'))
    );

    CREATE TABLE IF NOT EXISTS listing_images (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        listing_id INTEGER NOT NULL REFERENCES listings(id) ON DELETE CASCADE,
        url TEXT NOT NULL,
        is_cover INTEGER NOT NULL DEFAULT 0,
        sort_order INTEGER NOT NULL DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS favorites (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL REFERENCES users(id),
        listing_id INTEGER NOT NULL REFERENCES listings(id) ON DELETE CASCADE,
        created_at TEXT NOT NULL DEFAULT (datetime('now')),
        UNIQUE(user_id, listing_id)
    );

    CREATE TABLE IF NOT EXISTS conversations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        listing_id INTEGER NOT NULL REFERENCES listings(id),
        buyer_id INTEGER NOT NULL REFERENCES users(id),
        seller_id INTEGER NOT NULL REFERENCES users(id),
        updated_at TEXT NOT NULL DEFAULT (datetime('now'))
    );

    CREATE TABLE IF NOT EXISTS messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        conversation_id INTEGER NOT NULL REFERENCES conversations(id) ON DELETE CASCADE,
        sender_id INTEGER NOT NULL REFERENCES users(id),
        content TEXT NOT NULL,
        is_read INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    );

    CREATE TABLE IF NOT EXISTS reports (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        listing_id INTEGER NOT NULL REFERENCES listings(id),
        user_id INTEGER NOT NULL REFERENCES users(id),
        reason TEXT NOT NULL,
        description TEXT DEFAULT '',
        status TEXT NOT NULL DEFAULT 'pending',
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    );

    CREATE TABLE IF NOT EXISTS notifications (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL REFERENCES users(id),
        type TEXT NOT NULL,
        title TEXT NOT NULL,
        body TEXT,
        is_read INTEGER NOT NULL DEFAULT 0,
        action_url TEXT,
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    );
    ");
}

// initSchema($db); // Tables already created in MySQL

// ─── Seed Data ─────────────────────────────────────────────────────────────────

function seedDatabase(PDO $db) {
    $count = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    if ($count > 0) return; // Already seeded

    // Categories
    $cats = [
        ['Véhicules','vehicles','car',null],
        ['Voitures','voitures','car',1],['Motos','motos','motorcycle',1],['Camions','camions','truck',1],['Pièces automobiles','pieces-auto','wrench',1],
        ['Immobilier','immobilier','home',null],
        ['Appartements','appartements','home',6],['Maisons','maisons','home',6],['Villas','villas','home',6],['Terrains','terrains','map',6],['Bureaux','bureaux','building',6],
        ['Téléphones & Tablettes','telephones','smartphone',null],
        ['Smartphones','smartphones','smartphone',12],['iPhone','iphone','smartphone',12],['Samsung','samsung','smartphone',12],['Tablettes','tablettes','tablet',12],
        ['Informatique','informatique','laptop',null],
        ['PC portables','pc-portables','laptop',17],['PC de bureau','pc-bureau','desktop',17],['Composants','composants','cpu',17],['Écrans','ecrans','monitor',17],
        ['Électronique','electronique','tv',null],
        ['TV','tv','tv',22],['Consoles','consoles','gamepad',22],['Appareils photo','photo','camera',22],['Audio','audio','headphones',22],
        ['Maison','maison-deco','couch',null],
        ['Meubles','meubles','sofa',27],['Électroménager','electromenager','washing-machine',27],['Décoration','decoration','palette',27],
        ['Mode & Accessoires','mode','shirt',null],
        ['Vêtements','vetements','shirt',31],['Chaussures','chaussures','footprints',31],['Accessoires','accessoires','watch',31],
        ['Emploi & Services','emploi','briefcase',null],
        ['Loisirs','loisirs','gamepad-2',null],
        ['Animaux','animaux','paw-print',null],
        ['Autres','autres','box',null],
    ];
    $stmt = $db->prepare("INSERT INTO categories (name, slug, icon, parent_id) VALUES (?, ?, ?, ?)");
    foreach ($cats as $c) $stmt->execute($c);

    // Regions
    $regs = [
        ['Casablanca-Settat','casablanca-settat','06'],
        ['Rabat-Salé-Kénitra','rabat-salie-kenitra','07'],
        ['Marrakech-Safi','marrakech-safi','08'],
        ['Fès-Meknès','fes-meknes','03'],
        ['Tanger-Tétouan-Al Hoceïma','tanger-tetouan','01'],
        ['Oriental','oriental','02'],
        ['Souss-Massa','souss-massa','11'],
    ];
    $stmt = $db->prepare("INSERT INTO regions (name, slug, code) VALUES (?, ?, ?)");
    foreach ($regs as $r) $stmt->execute($r);

    // Cities
    $cities = [
        ['Casablanca','casablanca',1],['Mohammedia','mohammedia',1],['El Jadida','el-jadida',1],['Settat','settat',1],
        ['Rabat','rabat',2],['Salé','sale',2],['Kénitra','kenitra',2],
        ['Marrakech','marrakech',3],['Safi','safi',3],['Essaouira','essaouira',3],
        ['Fès','fes',4],['Meknès','meknes',4],
        ['Tanger','tanger',5],['Tétouan','tetouan',5],
        ['Oujda','oujda',6],
        ['Agadir','agadir',7],['Tiznit','tiznit',7],
    ];
    $stmt = $db->prepare("INSERT INTO cities (name, slug, region_id) VALUES (?, ?, ?)");
    foreach ($cities as $c) $stmt->execute($c);

    // Users (password = "password123")
    $hash = password_hash('password123', PASSWORD_BCRYPT);
    $users = [
        ['Admin','L9it','admin@l9it.ma','+212 6 00 00 00 00',$hash,'admin@l9it.ma','Casablanca','ADMIN',1],
        ['Ahmed','Benali','ahmed@example.com','+212 6 12 34 56 78',$hash,'ahmed@test.com','Casablanca','USER',1],
        ['Fatima','Hassan','fatima@example.com','+212 6 23 45 67 89',$hash,'fatima@test.com','Rabat','USER',1],
        ['Mohamed','Ouazzani','mohamed@example.com','+212 6 34 56 78 90',$hash,'mohamed@test.com','Marrakech','USER',1],
        ['Khadija','Lamrani','khadija@example.com','+212 6 45 67 89 01',$hash,'khadija@test.com','Fès','USER',1],
        ['Youssef','Cherif','youssef@example.com','+212 6 56 78 90 12',$hash,'youssef@test.com','Tanger','USER',1],
    ];
    $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, phone, password, avatar, city, role, is_verified) VALUES (?,?,?,?,?,?,?,?,?)");
    foreach ($users as $u) $stmt->execute($u);

    // Listings
    $listings = [
        ['iPhone 15 Pro 256GB','iphone-15-pro-256gb','Excellent état, iPhone 15 Pro 256GB. Aucune rayure, batterie 98%. Livré avec chargeur et boîte d\'origine.',9500,'Comme neuf',13,2,1,2,'Maârif',342,28],
        ['Samsung Galaxy S24 Ultra','samsung-galaxy-s24-ultra','Samsung Galaxy S24 Ultra 512GB, Titanium Black. Comme neuf avec garantie Samsung.',15000,'Comme neuf',14,3,1,2,'Ain Diab',218,15],
        ['Appartement F3 Hay Mohammadi','appartement-f3-hay-mohammadi','Superbe appartement F3 au Hay Mohammadi. 80m², 3 chambres, salon, cuisine équipée. Balcon avec vue.',450000,'Bon état',7,2,1,5,'Hay Mohammadi',189,22],
        ['Renault Clio 2020','renault-clio-2020','Renault Clio 5 TCe 100. 45000km, première main, tous les entretiens chez le concessionnaire.',85000,'Bon état',2,3,1,1,'Anfa',456,31],
        ['MacBook Pro M2 14"','macbook-pro-m2-14','MacBook Pro M2 14 pouces, 16GB RAM, 512GB SSD. Comme neuf, 200 cycles batterie.',18500,'Comme neuf',18,4,1,2,'Palmier',167,12],
        ['Villa avec piscine Bouskoura','villa-piscine-bouskoura','Magnifique villa 300m² avec piscine, jardin 500m². 5 chambres, 4 salles de bain. Domaine Bouskoura.',1800000,'Comme neuf',9,5,1,2,'Bouskoura',234,19],
        ['Chaussures Nike Air Max 90','nike-air-max-90','Nike Air Max 90 taille 42. Portées 3 fois, comme neuves. Couleur blanche/verte.',850,'Comme neuf',32,4,1,5,'Ryad',123,8],
        ['PC Portable HP Pavilion','hp-pavilion-15','HP Pavilion 15.6", Intel i7 12th gen, 16GB RAM, 512GB SSD, RTX 3050.',8500,'Bon état',18,5,1,8,'Centreville',201,14],
        ['Télévision LG OLED 55"','lg-oled-55','LG OLED55C2, 55 pouces 4K. Parfaite pour le gaming et les films. 2 ans d\'utilisation.',6500,'Bon état',23,6,1,11,'Atlas',178,11],
        ['Toyota Corolla 2022','toyota-corolla-2022','Toyota Corolla Hybrid. 30000km, garantie constructeur. Première main.',195000,'Comme neuf',2,3,1,5,'Agdal',312,25],
        ['Salon cuir 7 places','salon-cuir-7-places','Salon 7 places en cuir véritable. Brun, très bon état. À récupérer sur place.',12000,'Bon état',28,6,1,8,'Hay Essalam',98,7],
        ['PlayStation 5 + 2 manettes','ps5-2-manettes','PS5 avec 2 manettes DualSense. 6 jeux inclus. Vendu avec lot complet.',4500,'Bon état',24,6,1,14,'Sidi Moumen',267,18],
        ['Moto Honda CB500F','honda-cb500f','Honda CB500F 2021. 12000km, révisions à jour. Idéale pour ville et balade.',32000,'Bon état',3,2,1,13,'Bourgogne',189,13],
        ['Canapé IKEA Söderhamn','ikea-soderhamn','Canapé IKEA Söderhamn 4 places, gris. Achetez neuf 8999dh, vendu pour cause déménagement.',3500,'Bon état',28,4,1,11,'Maârif',87,5],
        ['iPhone 13 128GB','iphone-13-128gb','iPhone 13 128GB bleu. Bon état, Batterie 89%. Fonctionne parfaitement.',4800,'Bon état',13,6,1,2,'Sbata',156,9],
    ];

    $placeholders = str_repeat('?,', 12) . '?';
    $stmt = $db->prepare("INSERT INTO listings (title, slug, description, price, condition, status, category_id, seller_id, region_id, city_id, neighborhood, views_count, favorites_count) VALUES ($placeholders)");
    foreach ($listings as $l) {
        $params = array_merge([$l[0], $l[1], $l[2], $l[3], $l[4], 'published'], array_slice($l, 5));
        $stmt->execute($params);
    }

    // Listing images (using picsum)
    $imgStmt = $db->prepare("INSERT INTO listing_images (listing_id, url, is_cover, sort_order) VALUES (?, ?, ?, ?)");
    for ($i = 1; $i <= 15; $i++) {
        $imgStmt->execute([$i, "https://picsum.photos/seed/l9it{$i}a/800/600", 1, 0]);
        $imgStmt->execute([$i, "https://picsum.photos/seed/l9it{$i}b/800/600", 0, 1]);
        $imgStmt->execute([$i, "https://picsum.photos/seed/l9it{$i}c/800/600", 0, 2]);
    }

    // Favorites
    $favStmt = $db->prepare("INSERT OR IGNORE INTO favorites (user_id, listing_id) VALUES (?, ?)");
    $favs = [[2,1],[2,3],[3,1],[3,5],[4,2],[4,6],[5,7],[5,10],[6,4],[6,8]];
    foreach ($favs as $f) $favStmt->execute($f);
}

seedDatabase($db);

// ─── Helpers ───────────────────────────────────────────────────────────────────

function json_response($data, int $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function getBody() {
    $raw = file_get_contents('php://input');
    return $raw ? json_decode($raw, true) : [];
}

function getQuery($key, $default = null) {
    return $_GET[$key] ?? $default;
}

function generateToken(int $userId, string $email, string $role): string {
    $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64_encode(json_encode([
        'sub' => $userId,
        'email' => $email,
        'role' => $role,
        'iat' => time(),
        'exp' => time() + 86400,
    ]));
    $sig = base64_encode(hash_hmac('sha256', "$header.$payload", 'l9it-secret-key-change-in-prod', true));
    return "$header.$payload.$sig";
}

function authenticateUser(PDO $db): ?array {
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!preg_match('/^Bearer\s+(.+)$/i', $auth, $m)) return null;

    $parts = explode('.', $m[1]);
    if (count($parts) !== 3) return null;

    $payload = json_decode(base64_decode($parts[1]), true);
    if (!$payload || ($payload['exp'] ?? 0) < time()) return null;

    $user = $db->prepare("SELECT id, first_name, last_name, email, phone, avatar, city, role, is_verified, is_blocked, created_at FROM users WHERE id = ?");
    $user->execute([$payload['sub']]);
    $user = $user->fetch();
    if (!$user || $user['is_blocked']) return null;

    return $user;
}

function generateSlug(string $title): string {
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $title), '-'));
    return $slug ?: 'listing-' . time();
}

// ─── Router ────────────────────────────────────────────────────────────────────

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('#^/api#', '', $path);
$path = rtrim($path, '/');
$method = $_SERVER['REQUEST_METHOD'];

// GET /listings — list with filters
if ($method === 'GET' && $path === '/listings') {
    $where = ["l.status = 'published'"];
    $params = [];

    if ($kw = getQuery('keyword')) {
        $where[] = "l.title LIKE ?";
        $params[] = "%$kw%";
    }
    if ($cat = getQuery('category')) {
        $where[] = "c.slug = ?";
        $params[] = $cat;
    }
    if ($city = getQuery('city')) {
        $where[] = "ci.slug = ?";
        $params[] = $city;
    }
    if ($minP = getQuery('minPrice')) {
        $where[] = "l.price >= ?";
        $params[] = (float)$minP;
    }
    if ($maxP = getQuery('maxPrice')) {
        $where[] = "l.price <= ?";
        $params[] = (float)$maxP;
    }
    if ($cond = getQuery('condition')) {
        $where[] = "l.condition = ?";
        $params[] = $cond;
    }

    $sort = getQuery('sort', 'recent');
    $order = match($sort) {
        'price_asc'  => 'l.price ASC',
        'price_desc' => 'l.price DESC',
        'views'      => 'l.views_count DESC',
        default      => 'l.created_at DESC',
    };

    $page = max(1, (int)getQuery('page', 1));
    $perPage = 20;
    $offset = ($page - 1) * $perPage;

    $whereSql = implode(' AND ', $where);

    // Count
    $countSql = "SELECT COUNT(*) FROM listings l
        LEFT JOIN categories c ON l.category_id = c.id
        LEFT JOIN cities ci ON l.city_id = ci.id
        WHERE $whereSql";
    $total = $db->prepare($countSql);
    $total->execute($params);
    $total = $total->fetchColumn();

    // Data
    $sql = "SELECT l.*, c.name as category_name, c.slug as category_slug,
            u.first_name as seller_first_name, u.last_name as seller_last_name,
            u.email as seller_email, u.avatar as seller_avatar, u.is_verified as seller_verified,
            ci.name as city_name, ci.slug as city_slug,
            r.name as region_name, r.slug as region_slug
        FROM listings l
        LEFT JOIN categories c ON l.category_id = c.id
        LEFT JOIN users u ON l.seller_id = u.id
        LEFT JOIN cities ci ON l.city_id = ci.id
        LEFT JOIN regions r ON l.region_id = r.id
        WHERE $whereSql
        ORDER BY $order
        LIMIT $perPage OFFSET $offset";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    // Attach images
    $imgStmt = $db->prepare("SELECT * FROM listing_images WHERE listing_id = ? ORDER BY sort_order");
    foreach ($rows as &$row) {
        $imgStmt->execute([$row['id']]);
        $row['images'] = $imgStmt->fetchAll();
        $row['category'] = ['id' => $row['category_id'], 'name' => $row['category_name'], 'slug' => $row['category_slug']];
        $row['seller'] = ['id' => $row['seller_id'], 'firstName' => $row['seller_first_name'], 'lastName' => $row['seller_last_name'], 'email' => $row['seller_email'], 'avatar' => $row['seller_avatar'], 'isVerified' => (bool)$row['seller_verified']];
        $row['city'] = ['id' => $row['city_id'], 'name' => $row['city_name'], 'slug' => $row['city_slug']];
        $row['region'] = ['id' => $row['region_id'], 'name' => $row['region_name'], 'slug' => $row['region_slug']];
        unset($row['category_name'], $row['category_slug'], $row['seller_first_name'], $row['seller_last_name'], $row['seller_email'], $row['seller_avatar'], $row['seller_verified'], $row['city_name'], $row['city_slug'], $row['region_name'], $row['region_slug']);
    }

    $totalPages = (int)ceil($total / $perPage);
    json_response([
        'success' => true,
        'data' => $rows,
        'message' => 'Listings retrieved successfully',
        'meta' => [
            'currentPage' => $page,
            'pageSize' => $perPage,
            'totalItems' => (int)$total,
            'totalPages' => $totalPages,
            'hasNextPage' => $page < $totalPages,
            'hasPreviousPage' => $page > 1,
        ],
    ]);
}

// GET /listings/{slug} — single listing
if ($method === 'GET' && preg_match('#^/listings/([^/]+)$#', $path, $m)) {
    $slug = $m[1];
    $stmt = $db->prepare("SELECT l.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon,
        u.id as uid, u.first_name, u.last_name, u.email as uemail, u.avatar as uavatar, u.is_verified as uverified, u.city as ucity,
        ci.name as city_name, ci.slug as city_slug,
        r.name as region_name, r.slug as region_slug
        FROM listings l
        LEFT JOIN categories c ON l.category_id = c.id
        LEFT JOIN users u ON l.seller_id = u.id
        LEFT JOIN cities ci ON l.city_id = ci.id
        LEFT JOIN regions r ON l.region_id = r.id
        WHERE l.slug = ?");
    $stmt->execute([$slug]);
    $row = $stmt->fetch();

    if (!$row) json_response(['success' => false, 'message' => 'Annonce introuvable'], 404);

    // Increment views
    $db->prepare("UPDATE listings SET views_count = views_count + 1 WHERE slug = ?")->execute([$slug]);
    $row['views_count']++;

    // Images
    $imgStmt = $db->prepare("SELECT * FROM listing_images WHERE listing_id = ? ORDER BY sort_order");
    $imgStmt->execute([$row['id']]);
    $row['images'] = $imgStmt->fetchAll();

    $row['category'] = ['id' => $row['category_id'], 'name' => $row['category_name'], 'slug' => $row['category_slug'], 'icon' => $row['category_icon']];
    $row['seller'] = ['id' => $row['uid'], 'firstName' => $row['first_name'], 'lastName' => $row['last_name'], 'email' => $row['uemail'], 'avatar' => $row['uavatar'], 'isVerified' => (bool)$row['uverified'], 'city' => $row['ucity']];
    $row['city'] = ['id' => $row['city_id'], 'name' => $row['city_name'], 'slug' => $row['city_slug']];
    $row['region'] = ['id' => $row['region_id'], 'name' => $row['region_name'], 'slug' => $row['region_slug']];

    unset($row['category_name'], $row['category_slug'], $row['category_icon'], $row['first_name'], $row['last_name'], $row['uemail'], $row['uavatar'], $row['uverified'], $row['uid'], $row['ucity'], $row['city_name'], $row['city_slug'], $row['region_name'], $row['region_slug']);

    json_response(['success' => true, 'data' => $row, 'message' => 'Listing retrieved']);
}

// GET /categories
if ($method === 'GET' && $path === '/categories') {
    $rows = $db->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY id")->fetchAll();
    json_response(['success' => true, 'data' => $rows, 'message' => 'OK']);
}

// GET /locations
if ($method === 'GET' && $path === '/locations') {
    $regions = $db->query("SELECT * FROM regions ORDER BY name")->fetchAll();
    $cities = $db->query("SELECT ci.*, r.name as region_name FROM cities ci JOIN regions r ON ci.region_id = r.id ORDER BY ci.name")->fetchAll();
    foreach ($cities as &$c) {
        $c['region'] = ['id' => $c['region_id'], 'name' => $c['region_name']];
        unset($c['region_name']);
    }
    json_response(['success' => true, 'data' => ['regions' => $regions, 'cities' => $cities], 'message' => 'OK']);
}

// POST /auth/register
if ($method === 'POST' && $path === '/auth/register') {
    $body = getBody();
    $required = ['firstName', 'lastName', 'email', 'password'];
    foreach ($required as $f) {
        if (empty($body[$f])) json_response(['success' => false, 'message' => "Field '$f' is required"], 422);
    }

    $exists = $db->prepare("SELECT id FROM users WHERE email = ?");
    $exists->execute([$body['email']]);
    if ($exists->fetch()) json_response(['success' => false, 'message' => 'Email already registered'], 409);

    $hash = password_hash($body['password'], PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, phone, password, city) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$body['firstName'], $body['lastName'], $body['email'], $body['phone'] ?? '', $hash, $body['city'] ?? '']);
    $userId = (int)$db->lastInsertId();

    $token = generateToken($userId, $body['email'], 'USER');
    $user = $db->prepare("SELECT id, first_name as firstName, last_name as lastName, email, phone, avatar, city, role, is_verified as isVerified, is_blocked, created_at FROM users WHERE id = ?");
    $user->execute([$userId]);
    $user = $user->fetch();

    json_response([
        'success' => true,
        'data' => ['token' => $token, 'user' => $user],
        'message' => 'Account created',
    ], 201);
}

// POST /auth/login
if ($method === 'POST' && $path === '/auth/login') {
    $body = getBody();
    if (empty($body['email']) || empty($body['password']))
        json_response(['success' => false, 'message' => 'Email and password required'], 400);

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$body['email']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($body['password'], $user['password']))
        json_response(['success' => false, 'message' => 'Invalid credentials'], 401);

    if ($user['is_blocked'])
        json_response(['success' => false, 'message' => 'Account is blocked'], 403);

    $token = generateToken($user['id'], $user['email'], $user['role']);
    unset($user['password']);

    json_response([
        'success' => true,
        'data' => [
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'firstName' => $user['first_name'],
                'lastName' => $user['last_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'avatar' => $user['avatar'],
                'city' => $user['city'],
                'role' => $user['role'],
                'isVerified' => (bool)$user['is_verified'],
                'isBlocked' => (bool)$user['is_blocked'],
                'createdAt' => $user['created_at'],
            ],
        ],
        'message' => 'Login successful',
    ]);
}

// POST /auth/logout
if ($method === 'POST' && $path === '/auth/logout') {
    json_response(['success' => true, 'message' => 'Logged out']);
}

// GET /auth/me
if ($method === 'GET' && $path === '/auth/me') {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);
    json_response(['success' => true, 'data' => $user]);
}

// POST /favorites
if ($method === 'POST' && $path === '/favorites') {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);

    $body = getBody();
    if (empty($body['listingId'])) json_response(['success' => false, 'message' => 'listingId required'], 422);

    $stmt = $db->prepare("INSERT OR IGNORE INTO favorites (user_id, listing_id) VALUES (?, ?)");
    $stmt->execute([$user['id'], $body['listingId']]);

    $db->prepare("UPDATE listings SET favorites_count = favorites_count + 1 WHERE id = ?")->execute([$body['listingId']]);

    json_response(['success' => true, 'message' => 'Added to favorites']);
}

// DELETE /favorites/{listingId}
if ($method === 'DELETE' && preg_match('#^/favorites/(\d+)$#', $path, $m)) {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);

    $stmt = $db->prepare("DELETE FROM favorites WHERE user_id = ? AND listing_id = ?");
    $stmt->execute([$user['id'], $m[1]]);
    if ($stmt->rowCount() > 0) {
        $db->prepare("UPDATE listings SET favorites_count = MAX(0, favorites_count - 1) WHERE id = ?")->execute([$m[1]]);
    }

    json_response(['success' => true, 'message' => 'Removed from favorites']);
}

// GET /favorites
if ($method === 'GET' && $path === '/favorites') {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);

    $stmt = $db->prepare("SELECT l.*, c.name as category_name, c.slug as category_slug,
        ci.name as city_name, ci.slug as city_slug,
        u.first_name as seller_first_name, u.last_name as seller_last_name, u.is_verified as seller_verified
        FROM favorites f
        JOIN listings l ON f.listing_id = l.id
        LEFT JOIN categories c ON l.category_id = c.id
        LEFT JOIN cities ci ON l.city_id = ci.id
        LEFT JOIN users u ON l.seller_id = u.id
        WHERE f.user_id = ?
        ORDER BY f.created_at DESC");
    $stmt->execute([$user['id']]);
    $rows = $stmt->fetchAll();

    foreach ($rows as &$row) {
        $row['category'] = ['id' => $row['category_id'], 'name' => $row['category_name'], 'slug' => $row['category_slug']];
        $row['city'] = ['id' => $row['city_id'], 'name' => $row['city_name'], 'slug' => $row['city_slug']];
        $row['seller'] = ['id' => $row['seller_id'], 'firstName' => $row['seller_first_name'], 'lastName' => $row['seller_last_name'], 'isVerified' => (bool)$row['seller_verified']];
        $row['images'] = [];
        unset($row['category_name'], $row['category_slug'], $row['city_name'], $row['city_slug'], $row['seller_first_name'], $row['seller_last_name'], $row['seller_verified']);
    }

    json_response(['success' => true, 'data' => $rows, 'message' => 'OK']);
}

// GET /conversations
if ($method === 'GET' && $path === '/conversations') {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);

    $stmt = $db->prepare("SELECT cv.*, l.title as listing_title
        FROM conversations cv
        JOIN listings l ON cv.listing_id = l.id
        WHERE cv.buyer_id = ? OR cv.seller_id = ?
        ORDER BY cv.updated_at DESC");
    $stmt->execute([$user['id'], $user['id']]);
    $rows = $stmt->fetchAll();

    json_response(['success' => true, 'data' => $rows, 'message' => 'OK']);
}

// POST /conversations
if ($method === 'POST' && $path === '/conversations') {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);

    $body = getBody();
    $listingId = $body['listingId'] ?? null;
    if (!$listingId) json_response(['success' => false, 'message' => 'listingId required'], 422);

    $listing = $db->prepare("SELECT seller_id FROM listings WHERE id = ?");
    $listing->execute([$listingId]);
    $listing = $listing->fetch();
    if (!$listing) json_response(['success' => false, 'message' => 'Listing not found'], 404);
    if ($listing['seller_id'] == $user['id']) json_response(['success' => false, 'message' => 'Cannot message yourself'], 400);

    $exists = $db->prepare("SELECT id FROM conversations WHERE listing_id = ? AND buyer_id = ?");
    $exists->execute([$listingId, $user['id']]);
    if ($exists->fetch()) json_response(['success' => false, 'message' => 'Conversation already exists'], 409);

    $stmt = $db->prepare("INSERT INTO conversations (listing_id, buyer_id, seller_id) VALUES (?, ?, ?)");
    $stmt->execute([$listingId, $user['id'], $listing['seller_id']]);

    json_response(['success' => true, 'data' => ['id' => (int)$db->lastInsertId()], 'message' => 'Conversation created'], 201);
}

// POST /messages
if ($method === 'POST' && $path === '/messages') {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);

    $body = getBody();
    $convId = $body['conversationId'] ?? null;
    $content = $body['content'] ?? '';
    if (!$convId || !$content) json_response(['success' => false, 'message' => 'conversationId and content required'], 422);

    $stmt = $db->prepare("INSERT INTO messages (conversation_id, sender_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$convId, $user['id'], $content]);
    $db->prepare("UPDATE conversations SET updated_at = datetime('now') WHERE id = ?")->execute([$convId]);

    json_response(['success' => true, 'data' => ['id' => (int)$db->lastInsertId()], 'message' => 'Message sent'], 201);
}

// POST /reports
if ($method === 'POST' && $path === '/reports') {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);

    $body = getBody();
    if (empty($body['listingId']) || empty($body['reason']))
        json_response(['success' => false, 'message' => 'listingId and reason required'], 422);

    $stmt = $db->prepare("INSERT INTO reports (listing_id, user_id, reason, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$body['listingId'], $user['id'], $body['reason'], $body['description'] ?? '']);

    json_response(['success' => true, 'message' => 'Report submitted'], 201);
}

// GET /notifications
if ($method === 'GET' && $path === '/notifications') {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);

    $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
    $stmt->execute([$user['id']]);
    json_response(['success' => true, 'data' => $stmt->fetchAll(), 'message' => 'OK']);
}

// Dashboard
if ($method === 'GET' && $path === '/dashboard') {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);

    $userId = $user['id'];
    $active = $db->prepare("SELECT COUNT(*) FROM listings WHERE seller_id = ? AND status = 'published'");
    $active->execute([$userId]);
    $active = $active->fetchColumn();

    $views = $db->prepare("SELECT COALESCE(SUM(views_count), 0) FROM listings WHERE seller_id = ?");
    $views->execute([$userId]);
    $views = $views->fetchColumn();

    $favs = $db->prepare("SELECT COUNT(*) FROM favorites WHERE listing_id IN (SELECT id FROM listings WHERE seller_id = ?)");
    $favs->execute([$userId]);
    $favs = $favs->fetchColumn();

    $msgs = $db->prepare("SELECT COUNT(DISTINCT cv.id) FROM conversations cv WHERE cv.buyer_id = ? OR cv.seller_id = ?");
    $msgs->execute([$userId, $userId]);
    $msgs = $msgs->fetchColumn();

    $recent = $db->prepare("SELECT l.*, c.name as category_name, ci.name as city_name
        FROM listings l LEFT JOIN categories c ON l.category_id = c.id LEFT JOIN cities ci ON l.city_id = ci.id
        WHERE l.seller_id = ? ORDER BY l.created_at DESC LIMIT 5");
    $recent->execute([$userId]);
    $recentRows = $recent->fetchAll();
    foreach ($recentRows as &$r) {
        $r['category'] = ['id' => $r['category_id'], 'name' => $r['category_name']];
        $r['city'] = ['id' => $r['city_id'], 'name' => $r['city_name']];
        $r['images'] = [];
        unset($r['category_name'], $r['city_name']);
    }

    json_response(['success' => true, 'data' => [
        'activeListings' => (int)$active,
        'totalViews' => (int)$views,
        'totalFavorites' => (int)$favs,
        'totalMessages' => (int)$msgs,
        'recentListings' => $recentRows,
    ]]);
}

// Admin stats
if ($method === 'GET' && $path === '/admin/stats') {
    $totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalListings = $db->query("SELECT COUNT(*) FROM listings")->fetchColumn();
    $published = $db->query("SELECT COUNT(*) FROM listings WHERE status = 'published'")->fetchColumn();
    $pending = $db->query("SELECT COUNT(*) FROM listings WHERE status = 'pending'")->fetchColumn();
    $reported = $db->query("SELECT COUNT(*) FROM reports WHERE status = 'pending'")->fetchColumn();
    $sold = $db->query("SELECT COUNT(*) FROM listings WHERE status = 'sold'")->fetchColumn();
    $views = $db->query("SELECT COALESCE(SUM(views_count), 0) FROM listings")->fetchColumn();

    json_response(['success' => true, 'data' => [
        'stats' => [
            'totalUsers' => (int)$totalUsers,
            'totalListings' => (int)$totalListings,
            'publishedListings' => (int)$published,
            'pendingListings' => (int)$pending,
            'reportedListings' => (int)$reported,
            'soldListings' => (int)$sold,
            'totalViews' => (int)$views,
        ],
    ]]);
}

// POST /listings
if ($method === 'POST' && $path === '/listings') {
    $user = authenticateUser($db);
    if (!$user) json_response(['success' => false, 'message' => 'Not authenticated'], 401);

    $body = getBody();
    $title = $body['title'] ?? '';
    $desc = $body['description'] ?? '';
    $price = (float)($body['price'] ?? 0);
    $condition = $body['condition'] ?? 'Neuf';
    $categoryId = (int)($body['categoryId'] ?? 0);
    $regionId = (int)($body['regionId'] ?? 0);
    $cityId = (int)($body['cityId'] ?? 0);
    $neighborhood = $body['neighborhood'] ?? '';

    if (!$title) json_response(['success' => false, 'message' => 'Title required'], 422);

    $slug = generateSlug($title);
    // Unique slug check
    $check = $db->prepare("SELECT id FROM listings WHERE slug = ?");
    $check->execute([$slug]);
    if ($check->fetch()) $slug .= '-' . time();

    $stmt = $db->prepare("INSERT INTO listings (title, slug, description, price, condition, status, category_id, seller_id, region_id, city_id, neighborhood) VALUES (?, ?, ?, ?, ?, 'published', ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $slug, $desc, $price, $condition, $categoryId ?: null, $user['id'], $regionId ?: null, $cityId ?: null, $neighborhood]);

    json_response(['success' => true, 'data' => ['id' => (int)$db->lastInsertId(), 'slug' => $slug], 'message' => 'Listing created'], 201);
}

// Catch-all 404
json_response(['success' => false, 'message' => 'Endpoint not found: ' . $method . ' ' . $path], 404);

