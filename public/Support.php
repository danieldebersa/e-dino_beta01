<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte Técnico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            background-color: #0078D7;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .manual-list {
            list-style: none;
            padding: 0;
        }
        .manual-list li {
            margin: 1rem 0;
            padding: 1rem;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .manual-list a {
            color: #0078D7;
            text-decoration: none;
            font-weight: bold;
        }
        .manual-list a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Soporte Técnico</h1>
    </header>
    <div class="container">
        <h1>Manuales y Recursos</h1>
        <p>Aquí puedes encontrar los manuales en formato PDF para ayudarte a resolver tus dudas.</p>
        <ul class="manual-list">
            <li>
                <span>Manual de Usuario</span>
                <a href="manuales/manual_usuario.pdf" target="_blank">Descargar</a>
            </li>
            <li>
                <span>Guía de Instalación</span>
                <a href="manuales/guia_instalacion.pdf" target="_blank">Descargar</a>
            </li>
            <li>
                <span>Solución de Problemas</span>
                <a href="manuales/solucion_problemas.pdf" target="_blank">Descargar</a>
            </li>
        </ul>
    </div>
</body>
</html>
