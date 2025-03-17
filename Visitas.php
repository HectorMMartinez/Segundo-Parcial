<?php
require("Libreria\Motor.php");
Plantilla::aplicar();
?>
<div class="box">
    <h1 class="title has-text-centered">Bienvenido al formulario de visitas médicas</h1>

    <form action="Procesar.php" method="post">
        <div class="field">
            <label class="label">Nombre</label>
            <div class="control">
                <input type="text" name="nombre" placeholder="Ingrese su Nombre" class="input is-fullwidth" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Apellido</label>
            <div class="control">
                <input type="text" name="apellido" placeholder="Ingrese su Apellido" class="input is-fullwidth" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Teléfono</label>
            <div class="control">
                <input type="text" name="telefono" placeholder="Ingrese su Teléfono" class="input is-fullwidth" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Email</label>
            <div class="control">
                <input type="email" name="correo" placeholder="Ingrese su Email" class="input is-fullwidth" required>
            </div>
        </div>

        <div class="field has-text-centered">
            <button class="button is-link is-medium">Enviar</button>
        </div>
    </form>
</div>