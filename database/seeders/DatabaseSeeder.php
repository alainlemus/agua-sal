<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ContactSubmission;
use App\Models\Menu;
use App\Models\MenuSection;
use App\Models\Page;
use App\Models\Product;
use App\Models\ReviewCampaign;
use App\Models\ReviewSubmission;
use App\Models\SiteInfo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Limpiar tablas respetando foreign keys ──────────────────────────
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();
        Role::query()->delete();
        Permission::query()->delete();

        $isMysql = DB::getDriverName() === 'mysql';
        if ($isMysql) DB::statement('SET FOREIGN_KEY_CHECKS=0');
        MenuSection::truncate();
        Menu::truncate();
        Product::truncate();
        Category::truncate();
        ReviewCampaign::truncate();
        ReviewSubmission::truncate();
        ContactSubmission::truncate();
        Page::truncate();
        SiteInfo::truncate();
        User::truncate();
        if ($isMysql) DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ── Usuario admin ───────────────────────────────────────────────────
        User::factory()->create([
            'name'     => 'Admin User',
            'email'    => 'admin@donpapi.test',
            'password' => bcrypt('password'),
        ]);

        // ────────────────────────────────────────────────────────────────────
        // CATEGORÍAS
        // ────────────────────────────────────────────────────────────────────
        $cats = [
            'Cortes'       => Category::create(['name' => 'Cortes Al Grill',           'slug' => 'cortes',        'is_active' => true, 'description' => 'Angus, Wagyu y opciones premium.']),
            'Hamburguesas' => Category::create(['name' => 'Hamburguesas',               'slug' => 'hamburguesas',  'is_active' => true, 'description' => 'Acompañadas de papas gajo.']),
            'Para Arrancar' => Category::create(['name' => 'Para Arrancar',             'slug' => 'para-arrancar', 'is_active' => true, 'description' => 'Perfectos para iniciar tu experiencia BBQ.']),
            'Caldosos'     => Category::create(['name' => 'Caldosos',                   'slug' => 'caldosos',      'is_active' => true, 'description' => 'Sopas y caldos.']),
            'Pastas'       => Category::create(['name' => 'Pastas',                     'slug' => 'pastas',        'is_active' => true, 'description' => 'Pastas con el toque de la casa.']),
            'Ensaladas'    => Category::create(['name' => 'Ensaladas',                  'slug' => 'ensaladas',     'is_active' => true, 'description' => 'Frescas y deliciosas.']),
            'Licores'      => Category::create(['name' => 'Vinos y Licores',            'slug' => 'licores',       'is_active' => true, 'description' => 'Champagne, Whiskys, Rones y Destilados.']),
            'Charolas'     => Category::create(['name' => 'Charolas Gabachonas',        'slug' => 'charolas',      'is_active' => true, 'description' => 'Acompañadas por pan brioche y guarniciones.']),
            'Especialidad' => Category::create(['name' => 'Especialidad de la Casa',    'slug' => 'especialidad',  'is_active' => true, 'description' => 'Por cada 250g se incluye pan y guarnición.']),
            'Huercos'      => Category::create(['name' => 'Para Los Huercos (Kids)',    'slug' => 'huercos',       'is_active' => true, 'description' => 'Menú infantil.']),
            'Bebidas'      => Category::create(['name' => 'Bebidas y Coctelería',       'slug' => 'bebidas',       'is_active' => true, 'description' => 'Cervezas y bebidas preparadas.']),
        ];

        // ────────────────────────────────────────────────────────────────────
        // PRODUCTOS
        // ────────────────────────────────────────────────────────────────────
        $products = [
            // Cortes Al Grill
            ['cat' => 'Cortes', 'name' => 'Rib-Eye (Angus High Choice)',    'price' => 690,   'desc' => '480 a 520 grs',                                                                      'feat' => true],
            ['cat' => 'Cortes', 'name' => 'Porter House (Angus High Choice)','price' => 1500, 'desc' => '950 a 1050 grs'],
            ['cat' => 'Cortes', 'name' => 'T-Bone (Angus High Choice)',     'price' => 1300,  'desc' => '950 a 1050 grs'],
            ['cat' => 'Cortes', 'name' => 'New York (Angus High Choice)',   'price' => 670,   'desc' => '480 a 520 grs'],
            ['cat' => 'Cortes', 'name' => 'Mega Cow Boy (Angus Prime)',     'price' => 1700,  'desc' => '1000 a 1100 grms'],
            ['cat' => 'Cortes', 'name' => 'Tomahawk (Angus Prime)',         'price' => 1900,  'desc' => '1000 a 1100 grs',                                                                    'feat' => true],
            ['cat' => 'Cortes', 'name' => 'Rib-Eye (Carne Uruguaya)',       'price' => 1350,  'desc' => 'Precio por 500gr.'],
            ['cat' => 'Cortes', 'name' => 'New York (Carne Uruguaya)',      'price' => 1250,  'desc' => 'Precio por 500gr.'],
            ['cat' => 'Cortes', 'name' => 'Picaña (Carne Uruguaya)',        'price' => 1500,  'desc' => 'Precio por kilo.'],
            ['cat' => 'Cortes', 'name' => 'Rib Eye 9+ (Wagyu Australiano)', 'price' => 2250,  'desc' => 'Precio por 500gr. Full Blood'],
            ['cat' => 'Cortes', 'name' => 'New-York 9+ (Wagyu Australiano)','price' => 2105,  'desc' => 'Precio por 500gr. Full Blood'],
            ['cat' => 'Cortes', 'name' => 'Picaña 5-6 (Wagyu Australiano)', 'price' => 1400,  'desc' => 'Precio por kilo. Full Blood'],
            ['cat' => 'Cortes', 'name' => 'Rib-Eye A5 (Wagyu Arita Japonés)','price' => 7500, 'desc' => 'Precio $7.50 el gramo',                                                              'feat' => true],
            ['cat' => 'Cortes', 'name' => 'New-York A5 (Wagyu Arita Japonés)','price' => 7000,'desc' => 'Precio $7.00 el gramo'],

            // Hamburguesas
            ['cat' => 'Hamburguesas', 'name' => 'Clásica',      'price' => 195, 'desc' => '300 grms carne, aderezo, queso americano, gouda, tocino y cebolla caramelizada.'],
            ['cat' => 'Hamburguesas', 'name' => 'Brisket',      'price' => 295, 'desc' => 'Pan brioche, 200gr de brisket ahumado, coleslaw y pepinillos.',                     'feat' => true],
            ['cat' => 'Hamburguesas', 'name' => 'Pulled Pork',  'price' => 230, 'desc' => 'Pan brioche, 200gr de pulled pork, coleslaw y pepinillos.'],

            // Para Arrancar
            ['cat' => 'Para Arrancar', 'name' => 'Tlacoyo de Picaña con Salsa de Huitlacoche', 'price' => 130, 'desc' => '2 piezas rellenos de frijol, 150g picaña',          'feat' => true],
            ['cat' => 'Para Arrancar', 'name' => 'Papa al Horno',                               'price' => 100, 'desc' => 'Papa horneada con mix de quesos'],
            ['cat' => 'Para Arrancar', 'name' => 'Sopes de Cola de Res',                        'price' => 90,  'desc' => 'Orden de 3 sopecitos con cremoso de aguacate'],
            ['cat' => 'Para Arrancar', 'name' => 'Molleja de Res al Grill',                     'price' => 180, 'desc' => '300gr de molleja asada directo al grill'],
            ['cat' => 'Para Arrancar', 'name' => 'Guacamole',                                   'price' => 150, 'desc' => 'Tradicional mexicano'],
            ['cat' => 'Para Arrancar', 'name' => 'Guacamole con Chicharrón Ramos',              'price' => 250, 'desc' => '200g de auténtico chicharrón del norte',             'feat' => true],
            ['cat' => 'Para Arrancar', 'name' => 'Tuétano con Arrachera',                       'price' => 210, 'desc' => 'Tuétano con arrachera cremosa, aguacate, rábano y cilantro'],
            ['cat' => 'Para Arrancar', 'name' => 'Sopes Ramos',                                 'price' => 100, 'desc' => 'Orden de 3 sopecitos de chicharrón regio'],
            ['cat' => 'Para Arrancar', 'name' => 'Costillas de Elote',                          'price' => 130, 'desc' => 'Acompañadas de salsa morita o mayonesa chiltepín'],

            // Caldosos
            ['cat' => 'Caldosos', 'name' => 'Frijoles Puercos',  'price' => 100, 'desc' => 'Jamón, chorizo y chile cuaresmeño, salchicha y carne de puerco.'],
            ['cat' => 'Caldosos', 'name' => 'Sopa de Tortilla',  'price' => 100, 'desc' => 'Caldillo base jitomate con sazón de la casa, crema, aguacate, chicharrón.'],
            ['cat' => 'Caldosos', 'name' => 'Jugo de Carne',     'price' => 130, 'desc' => 'Sabor tenue picosito, acompañado de cebolla y cilantro.'],

            // Pastas
            ['cat' => 'Pastas', 'name' => 'Pasta 3 Quesos',          'price' => 225, 'desc' => 'Gouda, manchego y parmesano. Acompañada de 100g de brisket.'],
            ['cat' => 'Pastas', 'name' => 'Pasta Pomodoro',           'price' => 225, 'desc' => 'Con queso parmesano y 100 g de brisket.'],
            ['cat' => 'Pastas', 'name' => 'Mac and Cheese con Brisket','price' => 230, 'desc' => 'Base 3 quesos y 100gr de brisket ahumado.',                  'feat' => true],

            // Ensaladas
            ['cat' => 'Ensaladas', 'name' => 'Ensalada Mediterránea', 'price' => 130, 'desc' => 'Mix de lechugas, jitomates rostizados, aceitunas, cebolla, pistachos, queso feta.'],
            ['cat' => 'Ensaladas', 'name' => 'Ensalada Don Papi',     'price' => 150, 'desc' => 'Higos caramelizados, jamón serrano, queso de cabra, fresas.'],

            // Vinos y Licores — Champagne
            ['cat' => 'Licores', 'name' => 'Dom Perignon (Botella)',    'price' => 11000, 'desc' => 'Champagne'],
            ['cat' => 'Licores', 'name' => 'Moet Ice (Botella)',        'price' => 3400,  'desc' => 'Champagne'],
            ['cat' => 'Licores', 'name' => 'Moet Rose (Botella)',       'price' => 3400,  'desc' => 'Champagne'],
            ['cat' => 'Licores', 'name' => 'Moet Ice Rose (Botella)',   'price' => 4100,  'desc' => 'Champagne'],
            ['cat' => 'Licores', 'name' => '24K Gold (Botella)',        'price' => 2000,  'desc' => 'Champagne'],
            ['cat' => 'Licores', 'name' => 'Freixenet Black (Botella)', 'price' => 1500,  'desc' => 'Champagne'],
            // Vinos Tintos
            ['cat' => 'Licores', 'name' => 'Calixa Ojos Negros',              'price' => 900,  'desc' => 'Vino Tinto'],
            ['cat' => 'Licores', 'name' => 'Sangre de Toro',                  'price' => 800,  'desc' => 'Vino Tinto'],
            ['cat' => 'Licores', 'name' => 'Tablas',                          'price' => 1100, 'desc' => 'Vino Tinto'],
            ['cat' => 'Licores', 'name' => 'Monte Xanit',                     'price' => 1900, 'desc' => 'Vino Tinto'],
            ['cat' => 'Licores', 'name' => 'Marqués de Cáceres Reserva',      'price' => 1900, 'desc' => 'Vino Tinto'],
            ['cat' => 'Licores', 'name' => 'Amicorum',                        'price' => 1950, 'desc' => 'Vino Tinto'],
            ['cat' => 'Licores', 'name' => 'Casillero del Diablo Carnaval',   'price' => 700,  'desc' => 'Vino Tinto'],
            ['cat' => 'Licores', 'name' => 'Marqués de Cáceres Crianza',      'price' => 1000, 'desc' => 'Vino Tinto'],
            // Whiskys
            ['cat' => 'Licores', 'name' => 'Black & White',            'price' => 550,  'desc' => 'Whisky (Copeo $55)'],
            ['cat' => 'Licores', 'name' => 'Red Label',                'price' => 700,  'desc' => 'Whisky (Copeo $70)'],
            ['cat' => 'Licores', 'name' => 'Buchanan\'s',              'price' => 1650, 'desc' => 'Whisky (Copeo $165)'],
            ['cat' => 'Licores', 'name' => 'Jack Daniel\'s Black',     'price' => 950,  'desc' => 'Whisky (Copeo $95)'],
            ['cat' => 'Licores', 'name' => 'Jack Daniels',             'price' => 1200, 'desc' => 'Whisky (Copeo $120)'],
            ['cat' => 'Licores', 'name' => 'Jack Daniels Honey / Apple','price' => 1200,'desc' => 'Whisky (Copeo $120)'],
            ['cat' => 'Licores', 'name' => 'Glenfidich 12',            'price' => 2900, 'desc' => 'Whisky (Copeo $290)'],
            ['cat' => 'Licores', 'name' => 'Glenfidich 15',            'price' => 4100, 'desc' => 'Whisky'],
            ['cat' => 'Licores', 'name' => 'Macallan 15',              'price' => 8000, 'desc' => 'Whisky'],
            ['cat' => 'Licores', 'name' => 'Macallan 12',              'price' => 3800, 'desc' => 'Whisky (Copeo $380)'],
            ['cat' => 'Licores', 'name' => 'Gentleman Jack',           'price' => 1700, 'desc' => 'Whisky (Copeo $170)'],
            ['cat' => 'Licores', 'name' => '1792',                     'price' => 1700, 'desc' => 'Whisky (Copeo $170)'],
            ['cat' => 'Licores', 'name' => 'Woodford Reserve',         'price' => 1700, 'desc' => 'Whisky (Copeo $170)'],
            ['cat' => 'Licores', 'name' => 'Chivas Regal 12',          'price' => 1900, 'desc' => 'Whisky (Copeo $190)'],
            ['cat' => 'Licores', 'name' => 'Old Parr 12',              'price' => 1700, 'desc' => 'Whisky (Copeo $170)'],
            ['cat' => 'Licores', 'name' => 'Buchana\'s Deluxe 12',     'price' => 1900, 'desc' => 'Whisky (Copeo $190)'],
            ['cat' => 'Licores', 'name' => 'Buchana\'s Master',        'price' => 2700, 'desc' => 'Whisky (Copeo $270)'],
            ['cat' => 'Licores', 'name' => 'Buchana\'s 18',            'price' => 5300, 'desc' => 'Whisky (Copeo $530)'],
            ['cat' => 'Licores', 'name' => 'J. Walker Red',            'price' => 800,  'desc' => 'Whisky (Copeo $80)'],
            ['cat' => 'Licores', 'name' => 'J. Walker Black Label',    'price' => 2400, 'desc' => 'Whisky (Copeo $240)'],
            ['cat' => 'Licores', 'name' => 'J.Walker Double Black',    'price' => 2800, 'desc' => 'Whisky (Copeo $280)'],
            ['cat' => 'Licores', 'name' => 'J.Walker Gold Label',      'price' => 3600, 'desc' => 'Whisky (Copeo $360)'],
            ['cat' => 'Licores', 'name' => 'J.Walker Green Label',     'price' => 4500, 'desc' => 'Whisky (Copeo $450)'],
            ['cat' => 'Licores', 'name' => 'J.Walker Blue Label',      'price' => 10900,'desc' => 'Whisky (Copeo $1090)'],
            // Ginebras
            ['cat' => 'Licores', 'name' => 'Bombay Sapphire', 'price' => 1000, 'desc' => 'Ginebra (Copeo $100)'],
            ['cat' => 'Licores', 'name' => 'Hendrick\'s',     'price' => 1800, 'desc' => 'Ginebra (Copeo $180)'],
            ['cat' => 'Licores', 'name' => 'Bulldog',         'price' => 1600, 'desc' => 'Ginebra (Copeo $160)'],
            ['cat' => 'Licores', 'name' => 'Tanqueray Ten',   'price' => 2100, 'desc' => 'Ginebra (Copeo $210)'],
            // Rones
            ['cat' => 'Licores', 'name' => 'Bacardi Blanco',     'price' => 700, 'desc' => 'Ron (Copeo $70)'],
            ['cat' => 'Licores', 'name' => 'Bacardi Añejo',      'price' => 850, 'desc' => 'Ron (Copeo $85)'],
            ['cat' => 'Licores', 'name' => 'Bacardi Coco',       'price' => 800, 'desc' => 'Ron (Copeo $80)'],
            ['cat' => 'Licores', 'name' => 'Bacardi Raspberry',  'price' => 800, 'desc' => 'Ron (Copeo $80)'],
            ['cat' => 'Licores', 'name' => 'Havana Club 7',      'price' => 900, 'desc' => 'Ron (Copeo $90)'],
            ['cat' => 'Licores', 'name' => 'Matusalem Clásico',  'price' => 600, 'desc' => 'Ron (Copeo $60)'],
            ['cat' => 'Licores', 'name' => 'Matusalem Platino',  'price' => 700, 'desc' => 'Ron (Copeo $70)'],
            // Brandy
            ['cat' => 'Licores', 'name' => 'Torres 5',        'price' => 700,  'desc' => 'Brandy (Copeo $70)'],
            ['cat' => 'Licores', 'name' => 'Torres 10',       'price' => 880,  'desc' => 'Brandy (Copeo $88)'],
            ['cat' => 'Licores', 'name' => 'Torres 15',       'price' => 1400, 'desc' => 'Brandy (Copeo $140)'],
            ['cat' => 'Licores', 'name' => 'Torres 20',       'price' => 2400, 'desc' => 'Brandy (Copeo $240)'],
            ['cat' => 'Licores', 'name' => 'Torres Alta Luz', 'price' => 1300, 'desc' => 'Brandy (Copeo $130)'],

            // Charolas Gabachonas
            ['cat' => 'Charolas', 'name' => 'Charola Para 2 Personas', 'price' => 740, 'desc' => 'Pulled pork, brisket, costilla de res y salchicha'],

            // Especialidad de la Casa
            ['cat' => 'Especialidad', 'name' => 'Brisket Wagyu', 'price' => 1530, 'desc' => 'Nuestra máxima especialidad ahumada.', 'feat' => true],

            // Para Los Huercos
            ['cat' => 'Huercos', 'name' => 'Mini Clásica con Papas', 'price' => 105, 'desc' => 'Mini hamburguesa de res, queso, papas gajo.'],

            // Bebidas y Coctelería
            ['cat' => 'Bebidas', 'name' => 'Cerveza de Barril 1L', 'price' => 100, 'desc' => 'De barril bien fría.'],
            ['cat' => 'Bebidas', 'name' => 'Cerveza XX Lager',     'price' => 40,  'desc' => 'Media'],
        ];

        foreach ($products as $p) {
            Product::create([
                'category_id' => $cats[$p['cat']]->id,
                'name'        => $p['name'],
                'slug'        => Str::slug($p['name']),
                'description' => $p['desc'],
                'price'       => $p['price'],
                'is_active'   => true,
                'is_featured' => $p['feat'] ?? false,
            ]);
        }

        // ────────────────────────────────────────────────────────────────────
        // MENÚ PRINCIPAL
        // ────────────────────────────────────────────────────────────────────
        $menu = Menu::create([
            'name'        => 'Menu 1',
            'slug'        => 'menu-1',
            'description' => 'Menu de comida',
            'schedule'    => 'Lunes - Domingo 11:00am - 08:00pm',
            'is_active'   => true,
            'sort_order'  => 1,
        ]);

        // Orden real del SQLite: Charolas(8), Cortes(1), Hamburguesas(2),
        // Para Arrancar(3), Caldosos(4), Pastas(5), Ensaladas(6),
        // Especialidad(9), Huercos(10), Bebidas(11), Licores(7)
        $menuSectionOrder = [
            'Charolas', 'Cortes', 'Hamburguesas', 'Para Arrancar',
            'Caldosos', 'Pastas', 'Ensaladas', 'Especialidad',
            'Huercos', 'Bebidas', 'Licores',
        ];

        $orden = 1;
        foreach ($menuSectionOrder as $key) {
            MenuSection::create([
                'menu_id'     => $menu->id,
                'category_id' => $cats[$key]->id,
                'label'       => null,
                'sort_order'  => $orden++,
            ]);
        }

        // ────────────────────────────────────────────────────────────────────
        // CAMPAÑA DE RESEÑAS
        // ────────────────────────────────────────────────────────────────────
        $campaign = ReviewCampaign::create([
            'name'             => 'Enero',
            'slug'             => 'enero-2026',
            'is_active'        => true,
            'max_uses'         => 500,
            'gift_title'       => '¡Bebida gratis!',
            'gift_code_prefix' => 'DON-PAPI',
            'gift_description' => 'Valido para una bebida de tu elección, no aplica en destilados',
        ]);

        ReviewSubmission::create(['review_campaign_id' => $campaign->id, 'customer_name' => 'Carlos Martínez',  'customer_email' => 'carlos@example.com',  'rating' => 5, 'comment' => '¡Excelente comida! Todo estaba delicioso y el servicio fue de primera. Definitivamente vuelvo.',       'gift_code' => 'DON-PAPI-EJ01', 'ip_address' => '127.0.0.1', 'created_at' => now()->subDays(5)]);
        ReviewSubmission::create(['review_campaign_id' => $campaign->id, 'customer_name' => 'Ana Sofía Garza',  'customer_email' => 'ana@example.com',     'rating' => 5, 'comment' => 'El Brisket Wagyu es de otro nivel. El ambiente es muy agradable y el trato del personal excelente.',        'gift_code' => 'DON-PAPI-EJ02', 'ip_address' => '127.0.0.1', 'created_at' => now()->subDays(3)]);
        ReviewSubmission::create(['review_campaign_id' => $campaign->id, 'customer_name' => 'Roberto Sánchez',  'customer_email' => 'roberto@example.com', 'rating' => 4, 'comment' => 'Muy buena experiencia. Los cortes a su punto y las cervezas bien frías. Volveremos pronto.',               'gift_code' => 'DON-PAPI-EJ03', 'ip_address' => '127.0.0.1', 'created_at' => now()->subDays(2)]);
        ReviewSubmission::create(['review_campaign_id' => $campaign->id, 'customer_name' => 'Fernanda López',   'customer_email' => 'fernanda@example.com','rating' => 5, 'comment' => 'El Tomahawk es espectacular. El humo, el sabor, la presentación… todo 10/10.',                           'gift_code' => 'DON-PAPI-EJ04', 'ip_address' => '127.0.0.1', 'created_at' => now()->subDays(1)]);
        ReviewSubmission::create(['review_campaign_id' => $campaign->id, 'customer_name' => 'Diego Hernández',  'customer_email' => 'diego@example.com',   'rating' => 4, 'comment' => 'Muy rico todo. Las salsas caseras son espectaculares y las hamburguesas de brisket son mi favoritas.',    'gift_code' => 'DON-PAPI-EJ05', 'ip_address' => '127.0.0.1', 'created_at' => now()->subHours(6)]);

        // ────────────────────────────────────────────────────────────────────
        // SITE INFO — Datos reales de Asados Don Papi
        // ────────────────────────────────────────────────────────────────────
        SiteInfo::create([
            'site_name'       => 'Asados Don Papi',
            'tagline'         => null,
            'serves_cuisine'  => null,
            'hero_heading'    => 'EL AUTÉNTICO SABOR DEL ASADO',
            'hero_subheading' => 'Fuego, humo y técnica BBQ americana.',
            'about_text'      => 'En Don Papi Asados nuestra pasión es el fuego. Utilizamos las mejores maderas y técnicas de ahumado tradicional para llevar a tu mesa cortes premium con un sabor incomparable.',
            'address'         => 'C. Violeta sn, San José Caltengo, 43628 Tulancingo, Hgo.',
            'phone'           => '+527752538154',
            'whatsapp'        => '+527752538154',
            'email'           => 'contacto@donpapi.mx',
            'site_logo'       => 'images/logo.png',
            'favicon'         => 'favicons/01KPF8CH3RTS3VFC12FEERCCD3.png',
            'schedules'       => [
                ['days' => 'Lunes a Jueves',    'hours' => '11:00 AM - 01:00 AM'],
                ['days' => 'Viernes a Sábado',  'hours' => '11:00 AM - 01:00 AM'],
                ['days' => 'Domingo',           'hours' => '11:00 AM - 01:00 AM'],
            ],
            'map_embed_url'   => '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6508.340280685612!2d-98.3727792!3d20.0951904!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d057b2455f3277%3A0xb07d627d1cdb0319!2sAsados%20Don%20papi!5e1!3m2!1ses!2smx!4v1775539308168!5m2!1ses!2smx" width="800" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'social_links'    => [
                ['platform' => 'facebook',  'label' => 'Asados Don Papi - Facebook',  'url' => 'https://www.facebook.com/asadosdonpapi'],
                ['platform' => 'instagram', 'label' => 'Asados Don Papi - Instagram', 'url' => 'https://www.instagram.com/don_papi_asados/'],
                ['platform' => 'tiktok',    'label' => 'Asados Don Papi - TikTok',    'url' => 'https://www.tiktok.com/@asados.don.papi'],
            ],
            'og_type'                => 'website',
            'twitter_card'           => 'summary_large_image',
            'privacy_policy_title'   => 'Aviso de Privacidad',
            'privacy_policy_content' => '<p>En <strong>Asados Don Papi</strong>, con domicilio en Tulancingo, Hidalgo, México, somos responsables del tratamiento de sus datos personales, los cuales serán protegidos conforme a lo dispuesto en la <em>Ley Federal de Protección de Datos Personales en Posesión de los Particulares</em> y demás normativa aplicable.</p><h2>¿Qué datos recopilamos?</h2><p>Recopilamos únicamente los datos que usted nos proporciona de forma voluntaria a través de nuestros formularios de contacto y de reseñas: nombre, correo electrónico y el contenido del mensaje.</p><h2>¿Para qué usamos sus datos?</h2><ul><li>Responder a sus solicitudes de información o reservaciones.</li><li>Gestionar las reseñas y opiniones sobre nuestros servicios.</li><li>Mejorar la experiencia en nuestro sitio web.</li></ul><h2>Cookies</h2><p>Este sitio utiliza cookies técnicas para su correcto funcionamiento. No utilizamos cookies de seguimiento de terceros sin su consentimiento.</p><h2>Derechos ARCO</h2><p>Usted tiene derecho a <strong>Acceder, Rectificar, Cancelar u Oponerse</strong> al tratamiento de sus datos personales. Para ejercer estos derechos, contáctenos a través de nuestro formulario de contacto o al correo electrónico indicado en este sitio.</p><h2>Cambios al aviso</h2><p>Nos reservamos el derecho de actualizar este aviso en cualquier momento. Cualquier cambio será publicado en esta misma página.</p><p><em>Última actualización: 19 de abril de 2026</em></p>',
        ]);

        // ────────────────────────────────────────────────────────────────────
        // PÁGINAS CMS
        // ────────────────────────────────────────────────────────────────────

        // Página de Inicio
        Page::create([
            'title'           => 'Inicio',
            'slug'            => 'home',
            'is_published'    => true,
            'show_in_nav'     => true,
            'nav_label'       => 'Nosotros',
            'nav_icon'        => '🔥',
            'nav_order'       => 0,
            'builder_content' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'hero_heading'      => 'EL AUTÉNTICO SABOR DEL ASADO',
                        'hero_subheading'   => 'Fuego, humo y técnica BBQ americana.',
                        'hero_image'        => null,
                        'hero_video'        => 'videos/hero.mp4',
                        'hero_side_image'   => 'images/papi.png',
                        'hero_badge_text_1' => 'CALIDAD Y SABOR',
                        'hero_badge_text_2' => 'EN CADA CORTE',
                    ],
                ],
                [
                    'type' => 'featured_products',
                    'data' => [
                        'heading'     => 'Nuestras Especialidades',
                        'subtitle'    => 'Descubre lo mejor de nuestra cocina',
                        'product_ids' => ['16', '90'],
                    ],
                ],
                [
                    'type' => 'promotions_carousel',
                    'data' => [
                        'heading' => 'Noticias y Promociones',
                        'items'   => [
                            ['image' => 'images/promo1.jpg', 'title' => '2x1 en Cervezas Artesanales',   'link' => null],
                            ['image' => 'images/promo2.jpg', 'title' => 'Nuevo Brisket Wagyu, ¡Pruébalo!','link' => null],
                            ['image' => 'images/promo3.jpg', 'title' => 'Música en vivo este Viernes',    'link' => null],
                        ],
                    ],
                ],
                [
                    'type' => 'about_section',
                    'data' => [
                        'heading'     => 'La Pasión por el Fuego',
                        'description' => 'En Don Papi Asados nuestra pasión es el fuego. Utilizamos las mejores maderas y técnicas de ahumado tradicional para llevar a tu mesa cortes premium.',
                        'image'       => 'images/hamburguesa.jpg',
                    ],
                ],
                [
                    'type' => 'ahumado_section',
                    'data' => [
                        'heading'     => 'El Arte del Ahumado',
                        'description' => 'Paciencia, leña seleccionada y humo. Nuestro proceso de ahumado toma entre 12 y 16 horas para desatar la jugosidad extrema y lograr el afamado \'Smoke Ring\'.',
                        'video_url'   => '<iframe width="560" height="315" src="https://www.youtube.com/embed/gSl4o8XR-0c?si=-nllnBQxWJcATNqZ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                        'mosaic_items' => [
                            ['image' => 'images/ahumado1.jpg'],
                            ['image' => 'images/promo2.jpg'],
                            ['image' => 'images/promo1.jpg'],
                            ['image' => 'images/promo3.jpg'],
                        ],
                    ],
                ],
                [
                    'type' => 'reviews_section',
                    'data' => ['heading' => 'Lo Que Dicen Nuestros Clientes'],
                ],
                [
                    'type' => 'contact_map_section',
                    'data' => [
                        'heading'     => 'Nuestra Ubicación',
                        'description' => 'Visítanos y prueba el verdadero sabor BBQ de la ciudad. El fuego está ardiendo.',
                    ],
                ],
            ],
        ]);

        // Página Ahumados
        Page::create([
            'title'           => 'ahumados',
            'slug'            => 'ahumados',
            'is_published'    => true,
            'show_in_nav'     => true,
            'nav_label'       => 'Ahumados',
            'nav_icon'        => '🥩',
            'nav_order'       => 0,
            'builder_content' => [],
        ]);

        // Página Taquería
        Page::create([
            'title'           => 'Taqueria',
            'slug'            => 'taqueria',
            'is_published'    => true,
            'show_in_nav'     => true,
            'nav_label'       => 'Taqueria',
            'nav_icon'        => '🌮',
            'nav_order'       => 0,
            'builder_content' => [
                [
                    'type' => 'taqueria_section',
                    'data' => [
                        'heading'          => 'La Taquería de Don Papi',
                        'subheading'       => 'De noche, somos taquería.',
                        'description'      => null,
                        'schedule'         => 'Lun – Sáb | 7pm – 12am',
                        'background_image' => '01KPHACABJXFHBPZYCVJC27QTY.jpg',
                        'tacos'            => [],
                    ],
                ],
                [
                    'type' => 'contact_map_section',
                    'data' => ['heading' => 'Ubicación'],
                ],
                [
                    'type' => 'social_feed',
                    'data' => [
                        'heading'     => 'Síguenos en Redes',
                        'description' => null,
                        'posts'       => [
                            [
                                'platform' => 'tiktok',
                                'url'      => 'https://www.tiktok.com/@asados.don.papi/video/7602098668249877781',
                                'caption'  => '🔥🪵 Nada de esto nació como un plan. Nació del fuego, del tiempo… y de hacer las cosas con el corazón.  Esto es Asados, don Papi. Y apenas estamos comenzando. @Juan Soto',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        // Página de Contacto
        Page::create([
            'title'           => 'Contacto',
            'slug'            => 'contacto',
            'is_published'    => true,
            'show_in_nav'     => true,
            'nav_label'       => 'Contacto',
            'nav_icon'        => '📬',
            'nav_order'       => 3,
            'builder_content' => [
                [
                    'type' => 'contact_form',
                    'data' => [
                        'heading'            => 'Escríbenos',
                        'description'        => 'Raza!! quieres hacer un evento con nosotros? Escríbenos y te decimos cómo hacerlo posible. También puedes escribirnos si tienes alguna duda o sugerencia.',
                        'submit_label'       => 'Enviar Mensaje',
                        'notification_email' => 'alainttlm@gmail.com',
                        'show_captcha'       => true,
                        'success_message'    => null,
                        'fields'             => [
                            ['name' => 'nombre',   'label' => 'Nombre',   'type' => 'text',     'required' => true,  'is_name' => false, 'is_email' => false, 'placeholder' => null],
                            ['name' => 'telefono', 'label' => 'Teléfono', 'type' => 'tel',      'required' => true,  'is_name' => false, 'is_email' => false, 'placeholder' => null],
                            ['name' => 'mensaje',  'label' => 'Mensaje',  'type' => 'textarea', 'required' => true,  'is_name' => false, 'is_email' => false, 'placeholder' => null],
                        ],
                    ],
                ],
                [
                    'type' => 'contact_map_section',
                    'data' => ['heading' => 'Nuestra Ubicación'],
                ],
            ],
        ]);

        // ── Mensajes de contacto de ejemplo ────────────────────────────────
        ContactSubmission::create([
            'sender_name' => 'María González',
            'fields_data' => [
                'nombre'   => 'María González',
                'telefono' => '55 9876 5432',
                'mensaje'  => 'Quisiera reservar una mesa para 6 personas el próximo sábado.',
            ],
            'is_attended' => true,
            'attended_at' => now()->subDays(3),
            'attended_by' => 'Admin User',
            'admin_notes' => 'Se confirmó la reserva para el sábado a las 2pm. Mesa #5.',
        ]);

        ContactSubmission::create([
            'sender_name' => 'Luis Ramírez',
            'fields_data' => [
                'nombre'   => 'Luis Ramírez',
                'telefono' => '55 1111 2222',
                'mensaje'  => 'Tengo una consulta sobre el menú para eventos corporativos.',
            ],
            'is_attended' => false,
        ]);

        // ── Roles y permisos ─────────────────────────────────────────────────
        $this->call(RolesAndPermissionsSeeder::class);
    }
}
