<?php
require 'connection.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de los pacientes</title>
    <link rel="stylesheet" href="pacientes.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <button onclick="window.location.href='index.php'" type='submit'>
                <img src="asetts/back-black.png" alt="Botón de regreso" class="back-button"></button>
            <h1>Datos de los pacientes:</h1>
        </div>
    </div>
    <div class="list-container">
        <?php
        if (isset($_POST['nombre'], $_POST['apellido'], $_POST['matricula'], $_POST['especialidad'])) {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $matricula = $_POST['matricula'];
            $especialidad = $_POST['especialidad'];

            $sql = "SELECT id FROM medicos 
                    WHERE nombre = :nombre 
                      AND apellido = :apellido 
                      AND matricula = :matricula 
                      AND especialidad = :especialidad";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
            $stmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);
            $stmt->bindParam(':especialidad', $especialidad, PDO::PARAM_STR);
            $stmt->execute();

            $medico = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($medico) {
                $medico_id = $medico['id'];


                $sql_pacientes = "SELECT rela_paciente FROM medico_pacientes WHERE rela_medico = :medico_id";
                $stmt_pacientes = $pdo->prepare($sql_pacientes);
                $stmt_pacientes->bindParam(':medico_id', $medico_id, PDO::PARAM_INT);
                $stmt_pacientes->execute();
                $ids_pacientes = $stmt_pacientes->fetchAll(PDO::FETCH_COLUMN);

                if ($ids_pacientes) {
                    $sql_datos_pacientes = "SELECT nombre, apellido, fecha_nacimiento, celular FROM pacientes WHERE id IN (" . implode(',', array_fill(0, count($ids_pacientes), '?')) . ")";
                    $stmt_datos_pacientes = $pdo->prepare($sql_datos_pacientes);
                    $stmt_datos_pacientes->execute($ids_pacientes);
                    $datos_pacientes = $stmt_datos_pacientes->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($datos_pacientes as $paciente) {
                        echo "<div class='patient-card'>";
                        echo "<p class='patient-index'></p>";
                        echo "<div class='patient-info'>";
                        echo "<p class='patient-name'><strong></strong>" . htmlspecialchars($paciente['nombre']) . " " . htmlspecialchars($paciente['apellido']) . "</p>";
                        echo "<p class='patient-details'><strong>Fecha de nacimiento:</strong> <span class='detail'></span>" . htmlspecialchars($paciente['fecha_nacimiento']) . "</p>";
                        echo "<p class='patient-details'><strong>Celular:</strong> <span class='detail'></span>" . htmlspecialchars($paciente['celular']) . "</p>";
                        echo "</div>";

                        // Crear el formulario para enviar los datos del paciente   
                        echo "<form action='medicamentos.php' method='POST' class='boton-formulario'>";
                        echo "<input type='hidden' name='nombre' value='" . htmlspecialchars($paciente['nombre']) . "'>";
                        echo "<input type='hidden' name='apellido' value='" . htmlspecialchars($paciente['apellido']) . "'>";
                        echo "<input type='hidden' name='fecha_nacimiento' value='" . htmlspecialchars($paciente['fecha_nacimiento']) . "'>";
                        echo "<input type='hidden' name='celular' value='" . htmlspecialchars($paciente['celular']) . "'>";
                        echo "<div class='arrow-button-container'>";
                        echo "<button type='submit' class='invisible-arrow-button'><img src='asetts/go.png' alt='Botón de flecha' class='arrow-icon'>";
                        echo "</button>";
                        echo "</div>";
                        echo "</form>";

                        echo "</div>";
                        echo "<hr>";
                    }
                } else {
                    echo "<p>No se encontraron pacientes para el médico especificado.</p>";
                }
            } else {
                echo "<p>No se encontró el médico con los datos proporcionados.</p>";
            }
        }
        ?>

    </div>
</body>

</html>