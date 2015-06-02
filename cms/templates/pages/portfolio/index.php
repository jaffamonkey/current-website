<?php perch_layout('head'); ?>
<?php perch_layout('header'); ?>

<main role="main">

  <?php perch_content('Introduction'); ?>

  <?php
    perch_collection('Work', [
      'template'   => 'work_list.html',
      'paginate'   => 'true',
      'count'      => 5,
      'sort'       => 'date',
      'sort-order' => 'DESC'
    ]);
  ?>

</main>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>