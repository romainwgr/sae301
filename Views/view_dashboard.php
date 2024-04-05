<?php require_once "view_begin.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<div class="dashboard">
<h1>Tableau de Bord</h1>



<div class="content">
<div class="l1">

<canvas id="myChart2"></canvas>
<canvas id="myChart3"></canvas>
</div>

<div class="l2">
<canvas id="myBarChart" ></canvas>
</div>
</div>
</div>
<script src="Content/js/script_dashboard.js"></script>

<?php require "view_end.php"; ?>