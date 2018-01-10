<?php
/**
 * @file
 *
 * Validate and create the template compile folder.
 */

if (!defined('CIVI_SETUP')) {
  exit();
}

\Civi\Setup::dispatcher()
  ->addListener('civi.setup.checkRequirements', function (\Civi\Setup\Event\CheckRequirementsEvent $e) {
    $m = $e->getModel();

    if (empty($m->templateCompilePath)) {
      $e->addError('templateCompilePath', sprintf('The templateCompilePath is undefined.'));
    }

    $e->addMessage('templateCompilePathWritable', sprintf('The template compile dir "%s" cannot be created. Ensure the parent folder is writable.', $m->templateCompilePath), \Civi\Setup\FileUtil::isCreateable($m->templateCompilePath));
  });

\Civi\Setup::dispatcher()
  ->addListener('civi.setup.installSettings', function (\Civi\Setup\Event\InstallSettingsEvent $e) {
    $m = $e->getModel();

    if (!file_exists($m->templateCompilePath)) {
      Civi\Setup::log()->info('[TemplateCompilePath] mkdir "{path}"', [
        'path' => $m->templateCompilePath,
      ]);
      mkdir($m->templateCompilePath, 0777, TRUE);
      \Civi\Setup\FileUtil::makeWebWriteable($m->templateCompilePath);
    }
  });