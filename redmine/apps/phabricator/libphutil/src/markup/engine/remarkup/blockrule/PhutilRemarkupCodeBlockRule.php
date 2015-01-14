<?php

final class PhutilRemarkupCodeBlockRule extends PhutilRemarkupBlockRule {

  public function getMatchingLineCount(array $lines, $cursor) {
    $num_lines = 0;
    $match_ticks = null;
    if (preg_match('/^(\s{2,}).+/', $lines[$cursor])) {
      $match_ticks = false;
    } else if (preg_match('/^\s*(```)/', $lines[$cursor])) {
      $match_ticks = true;
    } else {
      return $num_lines;
    }

    $num_lines++;

    if ($match_ticks &&
        preg_match('/^\s*(```)(.*)(```)\s*$/', $lines[$cursor])) {
      return $num_lines;
    }

    $cursor++;

    while (isset($lines[$cursor])) {
      if ($match_ticks) {
        if (preg_match('/```\s*$/', $lines[$cursor])) {
          $num_lines++;
          break;
        }
        $num_lines++;
      } else {
        if (strlen(trim($lines[$cursor]))) {
          if (!preg_match('/^\s{2,}/', $lines[$cursor])) {
            break;
          }
        }
        $num_lines++;
      }
      $cursor++;
    }

    return $num_lines;
  }

  public function markupText($text, $children) {
    if (preg_match('/^\s*```/', $text)) {
      // If this is a ```-style block, trim off the backticks and any leading
      // blank line.
      $text = preg_replace('/^\s*```(\s*\n)?/', '', $text);
      $text = preg_replace('/```\s*$/', '', $text);
    }

    $lines = explode("\n", $text);
    while ($lines && !strlen(last($lines))) {
      unset($lines[last_key($lines)]);
    }

    $options = array(
      'counterexample'  => false,
      'lang'            => null,
      'name'            => null,
      'lines'           => null,
    );

    $parser = new PhutilSimpleOptions();
    $custom = $parser->parse(head($lines));
    if ($custom) {
      $valid = true;
      foreach ($custom as $key => $value) {
        if (!array_key_exists($key, $options)) {
          $valid = false;
          break;
        }
      }
      if ($valid) {
        array_shift($lines);
        $options = $custom + $options;
      }
    }

    // Normalize the text back to a 0-level indent.
    $min_indent = 80;
    foreach ($lines as $line) {
      for ($ii = 0; $ii < strlen($line); $ii++) {
        if ($line[$ii] != ' ') {
          $min_indent = min($ii, $min_indent);
          break;
        }
      }
    }

    $text = implode("\n", $lines);
    if ($min_indent) {
      $indent_string = str_repeat(' ', $min_indent);
      $text = preg_replace('/^'.$indent_string.'/m', '', $text);
    }

    if ($this->getEngine()->isTextMode()) {
      $out = array();

      $header = array();
      if ($options['counterexample']) {
        $header[] = 'counterexample';
      }
      if ($options['name'] != '') {
        $header[] = 'name='.$options['name'];
      }
      if ($header) {
        $out[] = implode(', ', $header);
      }

      $text = preg_replace('/^/m', '  ', $text);
      $out[] = $text;

      return implode("\n", $out);
    }

    if (empty($options['lang'])) {
      // If the user hasn't specified "lang=..." explicitly, try to guess the
      // language. If we fail, fall back to configured defaults.
      $lang = PhutilLanguageGuesser::guessLanguage($text);
      if (!$lang) {
        $lang = nonempty(
          $this->getEngine()->getConfig('phutil.codeblock.language-default'),
          'php');
      }
      $options['lang'] = $lang;
    }

    $code_body = $this->highlightSource($text, $options);

    $name_header = null;
    if ($this->getEngine()->isHTMLMailMode()) {
      $header_attributes = array (
        'style' => 'padding: 6px 8px;
          background: #fdf5d4;
          color: rgba(0,0,0,.75);
          font-weight: bold;
          display: inline-block;
          border-top: 1px solid #f1c40f;
          border-left: 1px solid #f1c40f;
          border-right: 1px solid #f1c40f;
          margin-bottom: -1px;',
      );
    } else {
      $header_attributes = array(
        'class' => 'remarkup-code-header',
      );
    }
    if ($options['name']) {
      $name_header = phutil_tag(
        'div',
        $header_attributes,
        $options['name']);
    }

    $attributes = array(
        'class' => 'remarkup-code-block',
        'data-code-lang' => $options['lang'],
        'data-sigil' => 'remarkup-code-block',
    );

    return phutil_tag(
      'div',
      $attributes,
      array($name_header, $code_body));
  }

  private function highlightSource($text, array $options) {
    if ($options['counterexample']) {
      $aux_class = ' remarkup-counterexample';
    } else {
      $aux_class = null;
    }

    $aux_style = null;

    if ($this->getEngine()->isHTMLMailMode()) {
      if ($options['counterexample']) {
        $aux_style = 'border: 1px solid #c0392b;
          background: #f4dddb;
          font-size: 10x;
          padding: 8px;';
      } else {
        $aux_style = 'border: 1px solid #f1c40f;
          background: #fdf5d4;
          font-size: 10x;
          padding: 8px;';
      }
    }

    if ($options['lines']) {
      // Put a minimum size on this because the scrollbar is otherwise
      // unusable.
      $height = max(6, (int)$options['lines']);
      $aux_style = $aux_style
        .' '
        .'max-height: '
        .(2 * $height)
        .'em; overflow: auto;';
    }

    $engine = $this->getEngine()->getConfig('syntax-highlighter.engine');
    if (!$engine) {
      $engine = 'PhutilDefaultSyntaxHighlighterEngine';
    }
    $engine = newv($engine, array());
    $engine->setConfig(
      'pygments.enabled',
      $this->getEngine()->getConfig('pygments.enabled'));
    return phutil_tag(
      'pre',
      array(
        'class' => 'remarkup-code'.$aux_class,
        'style' => $aux_style,
      ),
      PhutilSafeHTML::applyFunction(
        'rtrim',
        $engine->highlightSource($options['lang'], $text)));
  }

}
