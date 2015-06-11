
  <?php if (!empty($description)): ?>
  <section class="description">
    <?= $description ?>
  </section>
  <?php endif ?>

  <?php if (!empty($repairInstructions)): ?>
  <section class="repairInstructions">
    <h2>How To Fix It</h2>
    <?= $repairInstructions ?>
  </section>
  <?php endif ?>

  <?php if (!empty($excerpt)): ?>
    <?= $excerpt ?>
  <?php endif ?>

  <?php if (!empty($trace)): ?>
    <h2>Trace</h2>
    <?= $trace ?>
  <?php endif ?>


