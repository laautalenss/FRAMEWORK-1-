<h1>letras</h1>

<form method="get" class="mb-3">
    <label class="form-label">Opciones</label>

    <select name="letras" class="form-select w-auto d-inline-block">

        <option value="">Todos</option>

        <option value="D"     <?= ($letra_seleccionado == 'D') ? 'selected' : '' ?>>D</option>
        <option value="I"     <?= ($letra_seleccionado == 'I') ? 'selected' : '' ?>>I</option>
    </select>

    <button class="btn btn-primary">Filtrar</button>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Letra</th>
            <th>Número</th>
            <th>Planta</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($aulas as $aula): ?>
        <tr>
            <td><?= htmlspecialchars($aula['nombre']) ?></td>
            <td><?= htmlspecialchars($aula['letra']) ?></td>
            <td><?= htmlspecialchars($aula['numero']) ?></td>
            <td><?= htmlspecialchars($aula['planta']) ?></td>
        </tr>
        <?php endforeach; ?>

    </tbody>
</table>
