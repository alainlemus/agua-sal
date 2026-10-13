<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuSection;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Menú vigente de Agua & Sal Tostadería (fuente: ficha de Google del negocio, oct 2026).
 * Respalda el menú anterior en storage/app/backups y lo desactiva (no borra nada).
 * Descripciones completas según el menú entregado por el dueño.
 */
final class MenuFromGoogleSeeder extends Seeder
{
    /** @var list<string> */
    private const FEATURED = ['Tostada Chuy', 'Burrito de Marlin', 'Ceviche Chuy', 'Tiradito de Salmón', 'Tostada de Atún Sellado', 'Aguachile de Jamaica'];

    public function run(): void
    {
        Storage::disk('local')->put(
            'backups/menu-before-google-import-'.now()->format('Ymd-His').'.json',
            json_encode(['categories' => Category::all(), 'products' => Product::all()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        );

        Product::query()->update(['is_active' => false, 'is_featured' => false]);
        Category::query()->update(['is_active' => false]);

        $menu = Menu::query()->orderBy('id')->first();
        $menu?->sections()->delete();
        $menu?->update(['schedule' => 'Martes - Domingo 11:00am - 10:00pm']);
        $order = 0;

        foreach ($this->menu() as $categoryName => $items) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName, 'is_active' => true],
            );

            if ($menu) {
                MenuSection::create(['menu_id' => $menu->id, 'category_id' => $category->id, 'sort_order' => ++$order]);
            }

            foreach ($items as [$name, $price, $description]) {
                Product::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                        'description' => $description,
                        'price' => $price,
                        'is_featured' => in_array($name, self::FEATURED, true),
                        'is_active' => true,
                    ],
                );
            }
        }
    }

    /** @return array<string, list<array{0: string, 1: float, 2: ?string}>> */
    private function menu(): array
    {
        return [
            'Aguachiles' => [
                ['Aguachile de Jamaica', 198, 'Camarón desnaturalizado bañado en una salsa de jamaica acompañado de pepino, jícama, cebolla morada, cacahuate tostado, láminas de camote frito, aguacate y un topping de poro frito.'],
                ['Aguachile Verde', 198, 'Camarón desnaturalizado bañado en salsa verde acompañado de pepino, cebolla morada, rábano y un abanico de aguacate.'],
                ['Aguachile Rojo', 190, 'Camarón desnaturalizado bañado en salsa roja acompañado de pepino, cebolla morada, rábano y un abanico de aguacate.'],
                ['Aguachile Quemado', 198, 'Camarón desnaturalizado bañado en salsa de chile habanero y piña quemada acompañado de pepino, cebolla morada, piña, aguacate, poro frito y láminas de plátano fritas.'],
            ],
            'Tostadas' => [
                ['Tostada de Aguachile Verde', 80, null],
                ['Tostada de Aguachile Rojo', 80, null],
                ['Tostada de Aguachile Quemado', 83, null],
                ['Tostada de Aguachile de Jamaica', 83, null],
                ['Tostada de Camarón Seco', 60, null],
                ['Tostada Roca', 75, null],
                ['Tostada Chuy', 85, null],
                ['Tostada La Álvarez', 85, null],
                ['Tostada Iguana', 75, null],
                ['Tostada de Marlin', 75, null],
                ['Tostada de Atún Sellado', 78, null],
                ['Tostada de Salmón', 85, null],
                ['Tostada de Camarón con Chipotle', 85, null],
                ['Tostada de Ceviche de Tamarindo', 80, null],
            ],
            'Camarones' => [
                ['Camarones Momia', 250, 'Camarones envueltos en tocino acompañados de papas a la francesa, ensalada y salsa BBQ.'],
                ['Camarones Empanizados', 225, 'Acompañados de papas a la francesa y ensalada con vinagreta de mango.'],
                ['Camarones al Ajillo', 220, 'Camarones salteados con ajo y chile guajillo acompañados de arroz blanco y ensalada con vinagreta de mango.'],
                ['Camarones a la Diabla', 220, 'Camarones salteados acompañados de arroz blanco y ensalada con vinagreta de mango.'],
                ['Camarones a los 3 Chiles', 250, 'Camarones salteados con 3 chiles secos (pasilla, morita y chiltepín), mantequilla y un toque de mayonesa, acompañados de ensalada con vinagreta de mango y arroz blanco.'],
            ],
            'Pulpos' => [
                ['Pulpo a los 3 Chiles', 250, 'Pulpo salteado con 3 chiles secos (pasilla, morita y chiltepín), mantequilla y un toque de mayonesa, acompañado de ensalada con vinagreta de mango y arroz blanco.'],
                ['Pulpo al Ajillo', 245, 'Pulpo salteado con ajo y chile guajillo acompañado de arroz blanco y ensalada con vinagreta de mango.'],
                ['Pulpo a la Diabla', 245, 'Pulpo salteado acompañado de arroz blanco y ensalada con vinagreta de mango.'],
            ],
            'Ceviches' => [
                ['Ceviche Chuy', 198, 'Atún fresco en cubos con mango, limón, pepino, cebolla morada y aguacate, bañado en un aceite de ajonjolí con un toque de cilantro y chile serrano, con poro frito.'],
                ['Ceviche de Tamarindo', 190, 'Atún fresco en cubos con pepino, aguacate, cebolla morada y ajonjolí negro tostado, acompañado de nuestra salsa de tamarindo y poro frito.'],
                ['Ceviche de Iguana', 185, 'Filete de pescado fresco acompañado de pepino, limón, cebolla morada, cilantro, chile serrano, jícama, aguacate, pepita tostada y poro frito.'],
                ['Ceviche de Salmón', 198, 'Salmón fresco en cubos acompañado de pepino, pimiento morrón y cebolla morada, bañado en una salsa de anguila con ajonjolí negro tostado y poro frito.'],
                ['Ceviche de Atún Sellado', 190, 'Atún sellado con pepino, cebolla, ajonjolí negro tostado y rodajas de chile toreado, bañado en salsa ponzu y poro frito.'],
            ],
            'Cócteles' => [
                ['Coctel de Camarón', 90, null],
                ['Coctel de Pulpo', 120, null],
                ['Coctel Mixto', 100, null],
            ],
            'Para Compartir' => [
                ['Chicharrón de Pescado', 180, 'Láminas de chicharrón de pescado con guacamole y pico de gallo.'],
                ['Fish and Chips', 160, 'Filetes rebozados acompañados de papas a la francesa y aderezo de tu preferencia (mayonesa chipotle, mayonesa cilantro o alioli).'],
                ['Mini Camarones Roca con Papas', 170, 'Mini camarones rebozados acompañados de papas a la francesa y aderezo de tu preferencia.'],
                ['Tiradito de Salmón', 200, 'Salmón en láminas bañado en una salsa ponzu, con un poco de cebollín, rodajas de chile toreado, ajonjolí y poro frito.'],
                ['Tiradito Agua & Sal', 200, 'Atún en láminas con aguacate bañado en salsa de cítricos con un toque de tamarindo, jengibre y furikake.'],
                ['Guacamole con Mariscos', 150, null],
                ['Papas a la Francesa', 50, null],
            ],
            'Tacos y Costras' => [
                ['Tacos Baja', 35, 'Tortilla con costra de queso y proteína rebozada, acompañada de un mix de ensalada de coles y zanahoria, pico de gallo, aderezo de mayonesa chipotle y alioli. (Camarón, pescado o pimiento)'],
                ['Costras', 35, 'Tortilla con costra de queso y proteína a tu elección, acompañada con mayonesa de cilantro y pico de gallo. (Pulpo, camarón o marlin)'],
                ['Taco Gober', 38, 'Tortilla de harina acompañada de queso y guiso de camarón.'],
                ['Tacos Jícama', 100, '4 láminas de jícama con camarón o pescado rebozado, acompañados de un mix de coles y zanahoria, con salsa de tamarindo y alioli. (Pescado o camarón)'],
                ['Pescadillas', 85, '3 piezas fritas sobre una cama de mix de coles con zanahoria, aderezo de mayonesa de cilantro, queso cotija y pico de gallo.'],
                ['Burrito de Marlin', 155, 'Marlin ahumado envuelto en tortilla de harina sobre un espejo de crema de pimiento, acompañado de aderezo de mayonesa de cilantro, germinado y ajonjolí negro. Con queso.'],
            ],
            'Pollo y Carne' => [
                ['Pechuga a la Plancha', 120, 'Acompañada de papas a la francesa y ensalada.'],
                ['Pechuga Empanizada', 150, 'Acompañada de papas a la francesa y ensalada.'],
                ['Arrachera', 150, 'Acompañada de papas a la francesa y ensalada.'],
            ],
            'Extras' => [
                ['Atún con Arroz Frito', 110, 'Atún fresco bañado en una mayonesa de la casa con cebollín y rodajas de chile serrano, sobre arroz botán frito con aguacate, cilantro y ajonjolí.'],
                ['Arroz', 40, null],
                ['Arroz con Camarones', 75, null],
            ],
            'Bebidas' => [
                ['Vitrolerito', 60, null],
                ['Refresco', 45, null],
                ['Naranjada', 40, null],
                ['Naranjada Mineral', 45, null],
                ['Limonada', 40, null],
                ['Limonada Mineral', 45, null],
                ['Agua Rusa', 50, null],
                ['Clamato Preparado', 65, null],
                ['Gin Tonic', 105, null],
                ['Carajillo', 95, null],
                ['Aperol Spritz', 105, null],
                ['Mezcal 400 Conejos', 85, null],
            ],
            'Cervezas' => [
                ['Ultra', 50, null],
                ['XX Lager', 50, null],
                ['Heineken', 55, null],
                ['Heineken 0.0', 50, null],
                ['Caguamita', 45, null],
                ['Bohemia Clara', 55, null],
                ['Bohemia Obscura', 55, null],
                ['Sol Obscura', 50, null],
                ['Indio', 50, null],
            ],
        ];
    }
}
