
  <?php if (!empty($title)): ?>
    <h1><?php print $title ?></h1>
  <?php endif ?>

  <?php if (!empty($description)): ?>
    <section class="description">
      <?= $description ?>
    </section>
  <?php endif ?>

  <?php if (!empty($excerpt)): ?>
    <?= $excerpt ?>
  <?php endif ?>

  <?php if (!empty($exception)): ?>
    <h2>Exception details</h2>
    <dl>
      <dt>Message</dt>
        <dd><?php print $exception->getMessage() ?></dd>
      <dt>File</dt>
        <dd><?php print $exception->getFile() ?></dd>
      <dt>Line</dt>
        <dd><?php print $exception->getLine() ?></dd>
    </dl>
  <?php endif ?>

  <?php if (!empty($trace)): ?>
    <h2>Trace</h2>
    <?= $trace ?>
  <?php endif ?>


