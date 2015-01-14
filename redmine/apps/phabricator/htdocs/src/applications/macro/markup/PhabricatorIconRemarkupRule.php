<?php

final class PhabricatorIconRemarkupRule extends PhutilRemarkupRule {

  public function getPriority() {
    return 200.0;
  }

  public function apply($text) {
    return preg_replace_callback(
      '@{icon\b((?:[^}\\\\]+|\\\\.)*)}@m',
      array($this, 'markupIcon'),
      $text);
  }

  public function markupIcon($matches) {
    $engine = $this->getEngine();
    $text_mode = $engine->isTextMode();
    $mail_mode = $engine->isHTMLMailMode();

    if (!$this->isFlatText($matches[0]) || $text_mode || $mail_mode) {
      return $matches[0];
    }

    $extra = idx($matches, 1);

    // We allow various forms, like these:
    //
    //   {icon}
    //   {icon camera}
    //   {icon,camera}
    //   {icon camera color=red}
    //   {icon, camera, color=red}

    $extra = ltrim($extra, ", \n");
    $extra = preg_split('/[\s,]+/', $extra, 2);

    // Choose some arbitrary default icon so that previews render in a mostly
    // reasonable way as you're typing the syntax.
    $icon = idx($extra, 0, 'paw');

    $defaults = array(
      'color' => null,
    );

    $options = idx($extra, 1, '');
    $parser = new PhutilSimpleOptions();
    $options = $parser->parse($options) + $defaults;

    // NOTE: We're validating icon and color names to prevent users from
    // adding arbitrary CSS classes to the document. Although this probably
    // isn't dangerous, it's safer to validate.

    static $icon_names;
    if (!$icon_names) {
      $icon_names = array_fuse(PHUIIconView::getFontIcons());
    }

    static $color_names;
    if (!$color_names) {
      $color_names = array_fuse(PHUIIconView::getFontIconColors());
    }

    if (empty($icon_names['fa-'.$icon])) {
      $icon = 'paw';
    }

    $color = $options['color'];
    if (empty($color_names[$color])) {
      $color = null;
    }

    $icon_view = id(new PHUIIconView())
      ->setIconFont('fa-'.$icon, $color);


    return $this->getEngine()->storeText($icon_view);
  }

}
