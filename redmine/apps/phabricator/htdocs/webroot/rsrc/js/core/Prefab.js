/**
 * @provides phabricator-prefab
 * @requires javelin-install
 *           javelin-util
 *           javelin-dom
 *           javelin-typeahead
 *           javelin-tokenizer
 *           javelin-typeahead-preloaded-source
 *           javelin-typeahead-ondemand-source
 *           javelin-dom
 *           javelin-stratcom
 *           javelin-util
 * @javelin
 */

/**
 * Utilities for client-side rendering (the greatest thing in the world).
 */
JX.install('Prefab', {

  statics : {
    renderSelect : function(map, selected, attrs) {
      var select = JX.$N('select', attrs || {});
      for (var k in map) {
        select.options[select.options.length] = new Option(map[k], k);
        if (k == selected) {
          select.value = k;
        }
      }
      select.value = select.value || JX.keys(map)[0];
      return select;
    },


    /**
     * Build a Phabricator tokenizer out of a configuration with application
     * sorting, datasource and placeholder rules.
     *
     *   - `id` Root tokenizer ID (alternatively, pass `root`).
     *   - `root` Root tokenizer node (replaces `id`).
     *   - `src` Datasource URI.
     *   - `ondemand` Optional, use an ondemand source.
     *   - `value` Optional, initial value.
     *   - `limit` Optional, token limit.
     *   - `placeholder` Optional, placeholder text.
     *   - `username` Optional, username to sort first (i.e., viewer).
     *   - `icons` Optional, map of icons.
     *
     */
    buildTokenizer : function(config) {
      config.icons = config.icons || {};

      var root;

      try {
        root = config.root || JX.$(config.id);
      } catch (ex) {
        // If the root element does not exist, just return without building
        // anything. This happens in some cases -- like Conpherence -- where we
        // may load a tokenizer but not put it in the document.
        return;
      }

      var datasource;

      // Default to an ondemand source if no alternate configuration is
      // provided.
      var ondemand = true;
      if ('ondemand' in config) {
        ondemand = config.ondemand;
      }

      if (ondemand) {
        datasource = new JX.TypeaheadOnDemandSource(config.src);
      } else {
        datasource = new JX.TypeaheadPreloadedSource(config.src);
      }

      // Sort results so that the viewing user always comes up first; after
      // that, prefer unixname matches to realname matches.

      var sort_handler = function(value, list, cmp) {
        var priority_hits = {};
        var self_hits     = {};

        var tokens = this.tokenize(value);

        for (var ii = 0; ii < list.length; ii++) {
          var item = list[ii];
          if (!item.priority) {
            continue;
          }

          if (config.username && item.priority == config.username) {
            self_hits[item.id] = true;
          }

          for (var jj = 0; jj < tokens.length; jj++) {
            if (item.priority.substr(0, tokens[jj].length) == tokens[jj]) {
              priority_hits[item.id] = true;
            }
          }
        }

        list.sort(function(u, v) {
          if (self_hits[u.id] != self_hits[v.id]) {
            return self_hits[v.id] ? 1 : -1;
          }

          // If one result is open and one is closed, show the open result
          // first. The "!" tricks here are becaused closed values are display
          // strings, so the value is either `null` or some truthy string. If
          // we compare the values directly, we'll apply this rule to two
          // objects which are both closed but for different reasons, like
          // "Archived" and "Disabled".

          var u_open = !u.closed;
          var v_open = !v.closed;

          if (u_open != v_open) {
            if (u_open) {
              return -1;
            } else {
              return 1;
            }
          }

          if (priority_hits[u.id] != priority_hits[v.id]) {
            return priority_hits[v.id] ? 1 : -1;
          }

          // Sort users ahead of other result types.
          if (u.priorityType != v.priorityType) {
            if (u.priorityType == 'user') {
              return -1;
            }
            if (v.priorityType == 'user') {
              return 1;
            }
          }

          return cmp(u, v);
        });
      };

      datasource.setSortHandler(JX.bind(datasource, sort_handler));
      datasource.setFilterHandler(JX.Prefab.filterClosedResults);
      datasource.setTransformer(JX.Prefab.transformDatasourceResults);

      var typeahead = new JX.Typeahead(
        root,
        JX.DOM.find(root, 'input', 'tokenizer-input'));
      typeahead.setDatasource(datasource);

      var tokenizer = new JX.Tokenizer(root);
      tokenizer.setTypeahead(typeahead);
      tokenizer.setRenderTokenCallback(function(value, key) {
        var result = datasource.getResult(key);

        var icon;
        if (result) {
          icon = result.icon;
          value = result.displayName;
        } else {
          icon = config.icons[key];
        }

        if (icon) {
          icon = JX.Prefab._renderIcon(icon);
        }

        // TODO: Maybe we should render these closed tags in grey? Figure out
        // how we're going to use color.

        return [icon, value];
      });

      if (config.placeholder) {
        tokenizer.setPlaceholder(config.placeholder);
      }

      if (config.limit) {
        tokenizer.setLimit(config.limit);
      }

      if (config.value) {
        tokenizer.setInitialValue(config.value);
      }

      JX.Stratcom.addData(root, {'tokenizer' : tokenizer});

      return {
        tokenizer: tokenizer
      };
    },

    /**
     * Filter callback for tokenizers and typeaheads which filters out closed
     * or disabled objects unless they are the only options.
     */
    filterClosedResults: function(value, list) {
      // Look for any open result.
      var has_open = false;
      var ii;
      for (ii = 0; ii < list.length; ii++) {
        if (!list[ii].closed) {
          has_open = true;
          break;
        }
      }

      if (!has_open) {
        // Everything is closed, so just use it as-is.
        return list;
      }

      // Otherwise, only display the open results.
      var results = [];
      for (ii = 0; ii < list.length; ii++) {
        if (!list[ii].closed) {
          results.push(list[ii]);
        }
      }

      return results;
    },

    /**
     * Transform results from a wire format into a usable format in a standard
     * way.
     */
    transformDatasourceResults: function(fields) {
      var closed = fields[9];
      var closed_ui;
      if (closed) {
        closed_ui = JX.$N(
          'div',
          {className: 'tokenizer-closed'},
          closed);
      }

      var icon = fields[8];
      var icon_ui;
      if (icon) {
        icon_ui = JX.Prefab._renderIcon(icon);
      }

      var display = JX.$N(
        'div',
        {className: 'tokenizer-result'},
        [icon_ui, fields[4] || fields[0], closed_ui]);
      if (closed) {
        JX.DOM.alterClass(display, 'tokenizer-result-closed', true);
      }

      return {
        name: fields[0],
        displayName: fields[4] || fields[0],
        display: display,
        uri: fields[1],
        id: fields[2],
        priority: fields[3],
        priorityType: fields[7],
        imageURI: fields[6],
        icon: icon,
        closed: closed,
        type: fields[5],
        sprite: fields[10]
      };
    },

    _renderIcon: function(icon) {
      return JX.$N(
        'span',
        {className: 'phui-icon-view phui-font-fa ' + icon});
    }

  }

});
