<!DOCTYPE html>
<html lang="en">
  <head>
    <title>ATTENTION</title>
    <?php if (!empty($css)): ?>
    <style><?= $css ?></style>
    <?php endif ?>
  </head>
  <body>

  <?php if (!empty($title)): ?>
    <h1><?= $title ?></h1>
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
      <dd><?= $exception->getMessage() ?></dd>
      <dt>File</dt>
      <dd><?= $exception->getFile() ?></dd>
      <dt>Line</dt>
      <dd><?= $exception->getLine() ?></dd>
    </dl>
  <?php endif ?>

  <?php if (!empty($trace)): ?>
    <h2>Trace</h2>
    <?= $trace ?>
  <?php endif ?>

  </body>
</html>

