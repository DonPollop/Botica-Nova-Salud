# Sistema de Gestión de Inventario y Ventas - Botica Nova Salud

## Descripción
Este proyecto es un software web desarrollado para la botica "Nova Salud", diseñado para gestionar de manera eficiente el inventario, las ventas y las alertas de stock bajo. 
Resuelve problemas de gestión manual, como desabastecimientos, errores en registros y largos tiempos de espera para los clientes, mediante la automatización y una interfaz amigable.

El sistema incluye módulos para:
- Gestión de inventario (CRUD de productos).
- Registro de ventas con actualización de stock en tiempo real.
- Alertas automáticas para productos con stock bajo, con opción de actualizar stock directamente.
- Autenticación de usuarios para proteger el acceso.

## Tecnologías Utilizadas
- **Frontend**: HTML, CSS, Bootstrap 5.3.3, FontAwesome 6.5.2.
- **Backend**: PHP 7.4+.
- **Base de Datos**: MySQL.
- **Otros**: JavaScript (para cálculos dinámicos y AJAX en alertas).

## Estructura del Proyecto
BOTICA_NOVA_SALUD/
├── php/
│   ├── conexion_db.php        # Conexión a la base de datos
│   ├── dashboard.php          # Panel principal
│   ├── login.php              # Lógica de autenticación
│   ├── logout.php             # Cierre de sesión
│   ├── inventario.php         # Gestión de inventario
│   ├── add_inventario.php     # Añadir productos
│   ├── edit_inventario.php    # Editar productos
│   ├── delete_inventariot.php # Eliminar productos
│   ├── alerts.php             # Alertas de stock bajo
│   ├── venta.php              # Registro de ventas
│   ├── add_venta.php          # Procesamiento de ventas
│   ├── registrar_venta.php    # Venta rápida
└── inicio.php                 # Página de inicio de sesión

## Requisitos
- Servidor web con soporte para PHP (por ejemplo, XAMPP, WAMP).
- POSTGRESQL para la base de datos.
- Navegador moderno (Chrome, Brave, etc.).

## Instrucciones de Instalación
1. **Clonar o descargar el repositorio**:
   ```bash
   git clone [URL del repositorio]      

