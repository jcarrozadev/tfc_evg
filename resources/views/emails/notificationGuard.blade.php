<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Gestión de guardia</title>
    </head>
    <body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
        <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 2px 8px rgba(0,0,0,0.1);">
            
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Virgen de Guadalupe" style="max-height: 80px;">
            </div>

            <h2 style="color: #2c3e50;">Hola {{ $name }},</h2>

            <p style="font-size: 16px; color: #333333;">
                {{ $body }}
            </p>

            <hr style="margin: 20px 0;">

            <p style="font-size: 14px; color: #888888;">
                Este mensaje ha sido enviado automáticamente por el sistema de gestión de guardias del centro educativo 
                <strong>Virgen de Guadalupe</strong>.
            </p>
        </div>
    </body>
</html>
