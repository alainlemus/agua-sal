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

        User::factory()->create([
            'name'     => 'Admin User',
            'email'    => 'admin@aguaysal.test',
            'password' => bcrypt('password'),
        ]);

        $cats = [
            'Tostadas'   => Category::create(['name' => 'Tostadas',            'slug' => 'tostadas',   'is_active' => true, 'description' => 'Tostadas frescas con mariscos.']),
            'Mariscos'   => Category::create(['name' => 'Mariscos',            'slug' => 'mariscos',   'is_active' => true, 'description' => 'Preparaciones con mariscos frescos.']),
            'Botanas'    => Category::create(['name' => 'Botanas',             'slug' => 'botanas',    'is_active' => true, 'description' => 'Perfectas para compartir.']),
            'Bebidas'    => Category::create(['name' => 'Bebidas',             'slug' => 'bebidas',    'is_active' => true, 'description' => 'Refrescos y más.']),
            'Cervezas'   => Category::create(['name' => 'Cervezas',            'slug' => 'cervezas',   'is_active' => true, 'description' => 'Cervezas bien frías.']),
        ];

        $products = [
            ['cat' => 'Tostadas', 'name' => 'Tostada de Camarón',  'price' => 160, 'desc' => '3 unidades',           'feat' => true],
            ['cat' => 'Tostadas', 'name' => 'Tostada de Pulpo',   'price' => 160, 'desc' => '3 unidades'],
            ['cat' => 'Tostadas', 'name' => 'Tostada de Jaiba',   'price' => 160, 'desc' => '3 unidades'],
            ['cat' => 'Tostadas', 'name' => 'Tostada Mixta',      'price' => 180, 'desc' => '3 unidades'],
            ['cat' => 'Tostadas', 'name' => 'Tostada de Atún',    'price' => 210, 'desc' => '3 unidades'],

            ['cat' => 'Mariscos', 'name' => 'Jaiba del Día',           'price' => 150, 'desc' => '2 unidades'],
            ['cat' => 'Mariscos', 'name' => 'Camarones al Ajillo',     'price' => 280, 'desc' => '250g'],
            ['cat' => 'Mariscos', 'name' => 'Pulpo al Ajillo',        'price' => 380, 'desc' => '250g',              'feat' => true],
            ['cat' => 'Mariscos', 'name' => 'Camarones a la Diabla',   'price' => 280, 'desc' => '250g'],
            ['cat' => 'Mariscos', 'name' => 'Pulpo a la Diabla',      'price' => 380, 'desc' => '250g'],
            ['cat' => 'Mariscos', 'name' => 'Camarones en Currob',    'price' => 280, 'desc' => '300g'],
            ['cat' => 'Mariscos', 'name' => 'Pulpo en Currob',        'price' => 380, 'desc' => '300g'],
            ['cat' => 'Mariscos', 'name' => 'Paquete de Camarones',   'price' => 350, 'desc' => ''],

            ['cat' => 'Botanas', 'name' => 'Guacamole',   'price' => 120, 'desc' => ''],
            ['cat' => 'Botanas', 'name' => 'Mixto',       'price' => 250, 'desc' => 'Jaiba, Pulpo, Camarón'],
            ['cat' => 'Botanas', 'name' => 'Chichen',     'price' => 120, 'desc' => ''],
            ['cat' => 'Botanas', 'name' => 'Pancita',     'price' => 160, 'desc' => ''],
            ['cat' => 'Botanas', 'name' => 'Cocktel de Camarón',  'price' => 120, 'desc' => ''],
            ['cat' => 'Botanas', 'name' => 'Cocktel de Jaiba',   'price' => 120, 'desc' => ''],
            ['cat' => 'Botanas', 'name' => 'Cocktel de Pulpo',   'price' => 150, 'desc' => ''],

            ['cat' => 'Bebidas', 'name' => 'Agua Mineral',       'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Coca-Cola',         'price' => 35,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Sidral Mundet',      'price' => 35,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Pepsi',             'price' => 35,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Lemon Postobón',     'price' => 35,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Boing Mango',       'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Boing Fresa',        'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Boing Manzana',      'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Boing Piña',        'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'H2O Limón',         'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'H2O Tamarindo',      'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Topochico',          'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Vitamina',          'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Café',              'price' => 35,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Chocomilk',         'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Arena',              'price' => 30,  'desc' => ''],
            ['cat' => 'Bebidas', 'name' => 'Tónica',            'price' => 25,  'desc' => ''],

            ['cat' => 'Cervezas', 'name' => 'Carta Blanca',    'price' => 40,  'desc' => ''],
            ['cat' => 'Cervezas', 'name' => 'Corona',           'price' => 45,  'desc' => ''],
            ['cat' => 'Cervezas', 'name' => 'Corona Light',     'price' => 45,  'desc' => ''],
            ['cat' => 'Cervezas', 'name' => 'Negra Modelo',     'price' => 50,  'desc' => ''],
            ['cat' => 'Cervezas', 'name' => 'Modelo Light',     'price' => 50,  'desc' => ''],
            ['cat' => 'Cervezas', 'name' => 'Victoria',         'price' => 40,  'desc' => ''],
            ['cat' => 'Cervezas', 'name' => 'Super Barre',      'price' => 35,  'desc' => ''],
            ['cat' => 'Cervezas', 'name' => 'Tsunami',          'price' => 50,  'desc' => ''],
            ['cat' => 'Cervezas', 'name' => 'X Lager',          'price' => 40,  'desc' => ''],
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

        $menu = Menu::create([
            'name'        => 'Menú',
            'slug'        => 'menu-1',
            'description' => 'Menú de Tostadas y Mariscos',
            'schedule'    => 'Lunes - Domingo 11:00am - 10:00pm',
            'is_active'   => true,
            'sort_order'  => 1,
        ]);

        $menuSectionOrder = ['Tostadas', 'Mariscos', 'Botanas', 'Bebidas', 'Cervezas'];
        $orden = 1;
        foreach ($menuSectionOrder as $key) {
            MenuSection::create([
                'menu_id'     => $menu->id,
                'category_id' => $cats[$key]->id,
                'label'       => null,
                'sort_order'  => $orden++,
            ]);
        }

        $campaign = ReviewCampaign::create([
            'name'             => 'Bienvenida',
            'slug'             => 'bienvenida-2026',
            'is_active'        => true,
            'max_uses'         => 500,
            'gift_title'       => '¡Bebida gratis!',
            'gift_code_prefix' => 'AGUA-SAL',
            'gift_description' => 'Válido para una bebida de tu elección.',
        ]);

        ReviewSubmission::create(['review_campaign_id' => $campaign->id, 'customer_name' => 'María González',    'customer_email' => 'maria@example.com',    'rating' => 5, 'comment' => 'Las tostadas de camarón están increíbles. El lugar es muy agradable.',        'gift_code' => 'AGUA-SAL-001', 'ip_address' => '127.0.0.1', 'created_at' => now()->subDays(5)]);
        ReviewSubmission::create(['review_campaign_id' => $campaign->id, 'customer_name' => 'Carlos Pérez',      'customer_email' => 'carlos@example.com',  'rating' => 5, 'comment' => 'El pulpo al ajillo es espectacular. Totalmente recomendado.',            'gift_code' => 'AGUA-SAL-002', 'ip_address' => '127.0.0.1', 'created_at' => now()->subDays(3)]);
        ReviewSubmission::create(['review_campaign_id' => $campaign->id, 'customer_name' => 'Ana Ramírez',        'customer_email' => 'ana@example.com',     'rating' => 4, 'comment' => 'Muy buena relación calidad-precio. El servicio es excelente.',              'gift_code' => 'AGUA-SAL-003', 'ip_address' => '127.0.0.1', 'created_at' => now()->subDays(2)]);
        ReviewSubmission::create(['review_campaign_id' => $campaign->id, 'customer_name' => 'Roberto Hernández', 'customer_email' => 'roberto@example.com', 'rating' => 5, 'comment' => 'Las mejores tostadas de Tulancingo. Volveré sin duda.',               'gift_code' => 'AGUA-SAL-004', 'ip_address' => '127.0.0.1', 'created_at' => now()->subDays(1)]);
        ReviewSubmission::create(['review_campaign_id' => $campaign->id, 'customer_name' => 'Sofia Martínez',   'customer_email' => 'sofia@example.com',   'rating' => 4, 'comment' => 'El ambiente es muy agradable y la comida llega rápido.',               'gift_code' => 'AGUA-SAL-005', 'ip_address' => '127.0.0.1', 'created_at' => now()->subHours(6)]);

        SiteInfo::create([
            'site_name'       => 'Tostadería Agua y Sal',
            'tagline'         => 'Tostadas y Mariscos Frescos',
            'serves_cuisine'  => 'Mariscos',
            'hero_heading'    => 'Tostadas y Mariscos Frescos',
            'hero_subheading' => 'El marisco más fresco, directo a tu mesa.',
            'about_text'      => 'En Tostadería Agua y Sal te invitamos a disfrutar de nuestras tostadas y mariscos preparados con los ingredientes más frescos. Calidad y sabor en cada bocado.',
            'address'         => 'Melchor Ocampo Nte. #302, Centro, 43600 Tulancingo, Hgo.',
            'phone'          => '+527757574934',
            'whatsapp'       => '+527757574934',
            'email'          => 'contacto@aguaysal.mx',
            //'site_logo'      => 'images/logo.png',
            //'favicon'       => 'favicons/01KPF8CH3RTS3VFC12FEERCCD3.png',
            'schedules'      => [
                ['days' => 'Lunes a Domingo', 'hours' => '11:00 AM - 10:00 PM'],
            ],
            'map_embed_url'  => '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1627.2122980015508!2d-98.37340138276426!3d20.082940995835916!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1ses!2smx!4v1777594636234!5m2!1ses!2smx" width="800" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'social_links'   => [
                ['platform' => 'facebook',  'label' => 'Tostadería Agua y Sal - Facebook',   'url' => 'https://www.facebook.com/AguaySalTostaderia'],
                ['platform' => 'instagram', 'label' => 'Tostadería Agua y Sal - Instagram', 'url' => 'https://www.instagram.com/aguaysaltostaderia/'],
            ],
            'og_type'                => 'website',
            'twitter_card'           => 'summary_large_image',
            'privacy_policy_title'   => 'Aviso de Privacidad',
            'privacy_policy_content' => '<p><strong>Tostadería Agua y Sal</strong>, con domicilio en Tulancingo, Hidalgo, México, es responsable del tratamiento de sus datos personales.</p><h2>¿Qué datos recopilamos?</h2><p>Recopilamos datos que usted nos proporciona voluntariamente: nombre, correo electrónico y contenido del mensaje.</p><h2>¿Para qué usamos sus datos?</h2><ul><li>Responder a sus solicitudes de información.</li><li>Gestionar las reseñas y opiniones sobre nuestros servicios.</li></ul><h2>Derechos ARCO</h2><p>Usted tiene derecho a <strong>Acceder, Rectificar, Cancelar u Oponerse</strong> al tratamiento de sus datos personales.</p><p><em>Última actualización: 30 de abril de 2026</em></p>',
        ]);

        Page::create([
            'title'           => 'Inicio',
            'slug'            => 'home',
            'is_published'    => true,
            'show_in_nav'     => true,
            'nav_label'       => 'Inicio',
            'nav_icon'        => '🏠',
            'nav_order'       => 0,
            'builder_content' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'hero_heading'      => 'TOSTADAS Y MARISCOS FRESCOS',
                        'hero_subheading'   => 'El marisco más fresco, directo a tu mesa.',
                        'hero_image'        => null,
                        'hero_video'        => null,
                        'hero_side_image'   => null,
                        'hero_badge_text_1' => null,
                        'hero_badge_text_2' => null,
                    ],
                ],
                [
                    'type' => 'featured_products',
                    'data' => [
                        'heading'     => 'Nuestras Especialidades',
                        'subtitle'    => 'Lo más pedido por nuestros clientes',
                        'product_ids' => ['1', '7'],
                    ],
                ],
                [
                    'type' => 'about_section',
                    'data' => [
                        'heading'     => 'Frescura y Sabor',
                        'description' => 'En Tostadería Agua y Sal te invitamos a disfrutar de nuestras tostadas y mariscos preparados con los ingredientes más frescos. Cada platillo es hecho con dedicación para que tengas la mejor experiencia.',
                        'image'       => null,
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
                        'description' => 'Visítanos y disfruta el mejor marisco de Tulancingo.',
                    ],
                ],
            ],
        ]);

        Page::create([
            'title'           => 'Contacto',
            'slug'            => 'contacto',
            'is_published'    => true,
            'show_in_nav'     => true,
            'nav_label'       => 'Contacto',
            'nav_icon'        => '📬',
            'nav_order'       => 2,
            'builder_content' => [
                [
                    'type' => 'contact_form',
                    'data' => [
                        'heading'            => 'Escríbenos',
                        'description'        => '¿Tienes alguna duda o sugerencia? Escríbenos y te responderemos pronto.',
                        'submit_label'       => 'Enviar Mensaje',
                        'notification_email' => 'contacto@aguaysal.mx',
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

        ContactSubmission::create([
            'sender_name' => 'Laura Sánchez',
            'fields_data' => [
                'nombre'   => 'Laura Sánchez',
                'telefono' => '55 1234 5678',
                'mensaje'  => 'Quisiera saber si ofrecen servicio a domicilio.',
            ],
            'is_attended' => true,
            'attended_at' => now()->subDays(2),
            'attended_by' => 'Admin User',
            'admin_notes' => 'Se explicó el servicio a domicilio y se pasó la información.',
        ]);

        ContactSubmission::create([
            'sender_name' => 'Miguel Torres',
            'fields_data' => [
                'nombre'   => 'Miguel Torres',
                'telefono' => '55 8765 4321',
                'mensaje'  => '¿Pueden hacer un pedido grande para evento?',
            ],
            'is_attended' => false,
        ]);

        $this->call(RolesAndPermissionsSeeder::class);
    }
}
