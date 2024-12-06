<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Médicos</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="asetts/back-white.png" alt="Botón de regreso" class="back-button">
            <h1>Conocé la lista de médicos</h1>
        </div>
        <div class="info-banner">
            <p>ASEVI+65 no resuelve emergencias</p>
            <button class="info-button">i</button>
        </div>
    </div>
    <div class="list-container">
        <h2>Lista de médicos</h2>
        <div class="medicos">
            <?php
            require 'connection.php';

            $sql = "SELECT nombre, apellido, matricula, especialidad FROM medicos";
            $result = $pdo->query($sql);

            $medicos = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($medicos) {
                foreach ($medicos as $row) {
                    echo "<div class='doctor-card'>";
                    echo "<img src='asetts/user.png' alt='Foto del médico' class='doctor-image'>";
                    echo "<div class='doctor-info'>";
                    echo "<p class='doctor-name'><strong>Dr. </strong> " . htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellido']) . "</p>";
                    echo "<p class='doctor-details'><strong>Matrícula:</strong> " . htmlspecialchars($row['matricula']) . "</p>";
                    echo "<p class='doctor-specialty'><strong>Especialidad:</strong> " . htmlspecialchars($row['especialidad']) . "</p>";
                    echo "</div>";
                    

                    // Crear el formulario para enviar los datos del médico    
                    echo "<form action='pacientes.php' method='POST' class='boton-formulario'>";
                    echo "<input type='hidden' name='nombre' value='" . htmlspecialchars($row['nombre']) . "'>";
                    echo "<input type='hidden' name='apellido' value='" . htmlspecialchars($row['apellido']) . "'>";
                    echo "<input type='hidden' name='matricula' value='" . htmlspecialchars($row['matricula']) . "'>";
                    echo "<input type='hidden' name='especialidad' value='" . htmlspecialchars($row['especialidad']) . "'>";
                    echo "<div class='arrow-button-container'>";
                    echo "<button type='submit' class='invisible-arrow-button'><img src='asetts/go.png' alt='Botón de flecha' class='arrow-icon'>";
                    echo "</button>";
                    
                    echo "</div>";
                    echo "</form>";

                    echo "</div>";

                }
            } else {
                echo "<p>No se encontraron médicos</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios@1.4.1/dist/axios.min.js"></script>
</body>

</html>