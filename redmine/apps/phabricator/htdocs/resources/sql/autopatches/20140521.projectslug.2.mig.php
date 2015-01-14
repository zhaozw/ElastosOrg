<?php

$project_table = new PhabricatorProject();
$table_name = $project_table->getTableName();
$conn_w = $project_table->establishConnection('w');
$slug_table_name = id(new PhabricatorProjectSlug())->getTableName();
$time = time();

echo "Migrating project phriction slugs...\n";
foreach (new LiskMigrationIterator($project_table) as $project) {
  $id = $project->getID();

  echo "Migrating project {$id}...\n";
  $phriction_slug = rtrim($project->getPhrictionSlug(), '/');
  $slug = id(new PhabricatorProjectSlug())
    ->loadOneWhere('slug = %s', $phriction_slug);
  if ($slug) {
    echo "Already migrated {$id}... Continuing.\n";
    continue;
  }
  queryfx(
    $conn_w,
    'INSERT INTO %T (projectPHID, slug, dateCreated, dateModified) '.
    'VALUES (%s, %s, %d, %d)',
    $slug_table_name,
    $project->getPHID(),
    $phriction_slug,
    $time,
    $time);
  echo "Migrated {$id}.\n";
}

echo "Done.\n";
