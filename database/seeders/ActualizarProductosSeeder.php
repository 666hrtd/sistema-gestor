<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ActualizarProductosSeeder extends Seeder
{
    public function run(): void
    {
        // número del nombre viejo => [nombre, descripcion, precio]
        $productos = [
            // Electrónica
            1  => ['Audífonos Bluetooth Sonic Pro', 'Audífonos inalámbricos con cancelación de ruido y hasta 30 horas de batería.', 899.00],
            5  => ['Mouse Inalámbrico Ergonómico', 'Mouse con diseño ergonómico, sensor óptico de 1600 DPI y conexión USB de 2.4 GHz.', 249.00],
            9  => ['Teclado Mecánico RGB', 'Teclado mecánico con switches azules e iluminación RGB personalizable.', 1099.00],
            13 => ['Cargador Rápido USB-C 65W', 'Cargador de pared compacto con carga rápida para laptop, tableta y celular.', 399.00],
            17 => ['Bocina Bluetooth Portátil', 'Bocina resistente al agua con 12 horas de reproducción y sonido estéreo.', 549.00],
            21 => ['Cámara Web Full HD 1080p', 'Cámara web con micrófono integrado y enfoque automático para clases y videollamadas.', 479.00],
            25 => ['Memoria USB 128 GB', 'Unidad flash USB 3.0 de alta velocidad con carcasa metálica resistente.', 189.00],
            29 => ['Smartwatch Fit Band 5', 'Reloj inteligente con monitor de ritmo cardíaco, contador de pasos y notificaciones.', 1299.00],

            // Hogar
            2  => ['Licuadora de Vaso 1.5 L', 'Licuadora de 600 W con 3 velocidades, vaso de vidrio y aspas de acero inoxidable.', 749.00],
            6  => ['Juego de Sábanas Matrimonial', 'Juego de 4 piezas de microfibra suave, ideal para cama matrimonial.', 459.00],
            10 => ['Lámpara de Escritorio LED', 'Lámpara con brazo flexible, tres niveles de brillo y puerto de carga USB.', 329.00],
            14 => ['Sartén Antiadherente 28 cm', 'Sartén con recubrimiento antiadherente y mango resistente al calor.', 389.00],
            18 => ['Organizador de Cocina 3 Niveles', 'Estante de metal con tres niveles para trastes, especias y utensilios.', 299.00],
            22 => ['Cafetera Eléctrica 12 Tazas', 'Cafetera programable con jarra de vidrio y filtro permanente reutilizable.', 899.00],
            26 => ['Cojín Decorativo de Terciopelo', 'Cojín de 45 x 45 cm con relleno suave y funda removible.', 149.00],
            30 => ['Set de Cuchillos de Cocina 6 Piezas', 'Juego de cuchillos de acero inoxidable con base de madera para guardarlos.', 529.00],

            // Papelería
            3  => ['Cuaderno Profesional 100 Hojas', 'Cuaderno de raya con pasta dura, cosido y hojas de 75 gramos.', 59.00],
            7  => ['Set de Bolígrafos de Gel 12 Piezas', 'Paquete de 12 bolígrafos de gel en colores surtidos con punto de 0.7 mm.', 89.00],
            11 => ['Marcatextos Pastel 6 Colores', 'Juego de 6 marcatextos de punta biselada en tonos pastel.', 79.00],
            15 => ['Carpeta Argollada Tamaño Carta', 'Carpeta de plástico con tres argollas y bolsa frontal para etiquetas.', 69.00],
            19 => ['Calculadora Científica 240 Funciones', 'Calculadora con pantalla de dos líneas, ideal para álgebra, trigonometría y estadística.', 299.00],
            23 => ['Agenda Semanal 2027', 'Agenda de vista semanal con pasta flexible, calendario y espacio para notas.', 159.00],
            27 => ['Kit de Geometría 5 Piezas', 'Regla, escuadras, transportador y compás en estuche de plástico.', 49.00],

            // Deportes
            4  => ['Balón de Fútbol No. 5', 'Balón de fútbol de cuero sintético, tamaño oficial, con cámara de aire de butilo.', 349.00],
            8  => ['Tapete de Yoga Antiderrapante', 'Tapete de 6 mm de grosor con superficie antiderrapante y correa para transportarlo.', 279.00],
            12 => ['Pesas Ajustables 10 kg', 'Par de mancuernas con discos intercambiables y barras con seguro de rosca.', 649.00],
            16 => ['Cuerda para Saltar con Contador', 'Cuerda ajustable con mangos antiderrapantes y contador digital de saltos.', 129.00],
            20 => ['Botella Deportiva 750 ml', 'Botella libre de BPA con tapa de rosca, boquilla y cierre antiderrame.', 119.00],
            24 => ['Guantes de Gimnasio', 'Guantes acolchados con soporte para la muñeca y material transpirable.', 199.00],
            28 => ['Banda Elástica de Resistencia', 'Banda de látex para ejercicio en casa, con resistencia media y 120 cm de largo.', 159.00],
        ];

        foreach ($productos as $numero => [$nombre, $descripcion, $precio]) {
            Producto::where('nombre', "Producto $numero")->update([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
            ]);
        }
    }
}