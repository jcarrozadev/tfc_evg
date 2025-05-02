<?php
    $hoy = new DateTime();
    $dia_semana = date('N', $hoy->getTimestamp());

    $diferencia_lunes = ($dia_semana == 1) ? 0 : (1 - $dia_semana);
    $lunes_obj = $hoy->modify("$diferencia_lunes day");
    $lunes_formateado = $lunes_obj->format('d/m/Y');

    $hoy = new DateTime();
    $diferencia_viernes = (5 - date('N', $hoy->getTimestamp()));
    $viernes_obj = $hoy->modify("+$diferencia_viernes day");
    $viernes_formateado = $viernes_obj->format('d/m/Y');

    $rango_fechas_semanal = "$lunes_formateado - $viernes_formateado";
?>

<div class="text-white p-3 px-3 mb-3 bg-custom rounded">
    <h1 class="fw-bold fs-3">{{ $title }} - {{ $rango_fechas_semanal }}</h1>
</div>