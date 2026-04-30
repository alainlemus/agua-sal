<?php

declare(strict_types=1);

namespace App\Enums;

enum BlockType: string
{
    case Hero = 'hero';
    case FeaturedProducts = 'featured_products';
    case PromotionsCarousel = 'promotions_carousel';
    case AboutSection = 'about_section';
    case BbqSection = 'bbq_section';
    case TaqueriaSection = 'taqueria_section';
    case AhumadoSection = 'ahumado_section';
    case ReviewsSection = 'reviews_section';
    case ContactMapSection = 'contact_map_section';
    case ContactForm = 'contact_form';
    case CtaBanner = 'cta_banner';
    case Faq = 'faq';
    case Pricing = 'pricing';

    public function label(): string
    {
        return match($this) {
            self::Hero => 'Hero',
            self::FeaturedProducts => 'Productos Destacados',
            self::PromotionsCarousel => 'Carrusel de Promociones',
            self::AboutSection => 'Sección Nosotros',
            self::BbqSection => 'Sección BBQ',
            self::TaqueriaSection => 'Sección Taquería',
            self::AhumadoSection => 'Sección Ahumado',
            self::ReviewsSection => 'Reseñas',
            self::ContactMapSection => 'Mapa de Contacto',
            self::ContactForm => 'Formulario de Contacto',
            self::CtaBanner => 'Banner CTA',
            self::Faq => 'Preguntas Frecuentes',
            self::Pricing => 'Precios',
        };
    }
}