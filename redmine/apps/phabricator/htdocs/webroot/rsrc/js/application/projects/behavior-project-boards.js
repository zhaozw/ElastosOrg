/**
 * @provides javelin-behavior-project-boards
 * @requires javelin-behavior
 *           javelin-dom
 *           javelin-util
 *           javelin-vector
 *           javelin-stratcom
 *           javelin-workflow
 *           phabricator-draggable-list
 */

JX.behavior('project-boards', function(config) {

  function finditems(col) {
    return JX.DOM.scry(col, 'li', 'project-card');
  }

  function onupdate(col) {
    var data = JX.Stratcom.getData(col);
    var cards = finditems(col);

    // Update the count of tasks in the column header.
    if (!data.countTagNode) {
      data.countTagNode = JX.$(data.countTagID);
      JX.DOM.show(data.countTagNode);
    }

    var sum = 0;
    for (var ii = 0; ii < cards.length; ii++) {
      // TODO: Allow this to be computed in some more clever way.
      sum += 1;
    }

    // TODO: This is a little bit hacky, but we don't have a PHUIX version of
    // this element yet.

    var over_limit = (data.pointLimit && (sum > data.pointLimit));

    var display_value = sum;
    if (data.pointLimit) {
      display_value = sum + ' / ' + data.pointLimit;
    }
    JX.DOM.setContent(JX.$(data.countTagContentID), display_value);


    var panel_map = {
      'project-panel-empty': !cards.length,
      'project-panel-over-limit': over_limit
    };
    var panel = JX.DOM.findAbove(col, 'div', 'workpanel');
    for (var k in panel_map) {
      JX.DOM.alterClass(panel, k, !!panel_map[k]);
    }

    var color_map = {
      'phui-tag-shade-disabled': (sum === 0),
      'phui-tag-shade-blue': (sum > 0 && !over_limit),
      'phui-tag-shade-red': (over_limit)
    };
    for (var k in color_map) {
      JX.DOM.alterClass(data.countTagNode, k, !!color_map[k]);
    }
  }

  function onresponse(response, item, list) {
    list.unlock();
    JX.DOM.alterClass(item, 'drag-sending', false);
    JX.DOM.replace(item, JX.$H(response.task));
  }

  function getcolumns() {
    return JX.DOM.scry(JX.$(config.boardID), 'ul', 'project-column');
  }

  function colsort(u, v) {
    var ud = JX.Stratcom.getData(u).sort || [];
    var vd = JX.Stratcom.getData(v).sort || [];

    for (var ii = 0; ii < ud.length; ii++) {

      if (parseInt(ud[ii]) < parseInt(vd[ii])) {
        return 1;
      }
      if (parseInt(ud[ii]) > parseInt(vd[ii])) {
        return -1;
      }
    }

    return 0;
  }

  function getcontainer() {
    return JX.DOM.find(
      JX.$(config.boardID),
      'div',
      'aphront-multi-column-view');
  }

  function onbegindrag(item) {
    // If the longest column on the board is taller than the window, the board
    // will scroll vertically. Dragging an item to the longest column may
    // make it longer, by the total height of the board, plus the height of
    // the drop target.

    // If this happens, the scrollbar will jump around and the scroll position
    // can be adjusted in a disorienting way. To reproduce this, drag a task
    // to the bottom of the longest column on a scrolling board and wave the
    // task in and out of the column. The scroll bar will jump around and
    // it will be hard to lock onto a target.

    // To fix this, set the minimum board height to the current board height
    // plus the size of the drop target (which is the size of the item plus
    // a bit of margin). This makes sure the scroll bar never needs to
    // recalculate.

    var item_size = JX.Vector.getDim(item);
    var container = getcontainer();
    var container_size = JX.Vector.getDim(container);

    container.style.minHeight = (item_size.y + container_size.y + 12) + 'px';
  }

  function onenddrag() {
    getcontainer().style.minHeight = '';
  }

  function ondrop(list, item, after) {
    list.lock();
    JX.DOM.alterClass(item, 'drag-sending', true);

    var item_phid = JX.Stratcom.getData(item).objectPHID;
    var data = {
      objectPHID: item_phid,
      columnPHID: JX.Stratcom.getData(list.getRootNode()).columnPHID
    };

    var after_phid = null;
    var items = finditems(list.getRootNode());
    if (after) {
      after_phid = JX.Stratcom.getData(after).objectPHID;
      data.afterPHID = after_phid;
    }
    var ii;
    var ii_item;
    var ii_item_phid;
    var ii_prev_item_phid = null;
    var before_phid = null;
    for (ii = 0; ii < items.length; ii++) {
      ii_item = items[ii];
      ii_item_phid = JX.Stratcom.getData(ii_item).objectPHID;
      if (ii_item_phid == item_phid) {
        // skip the item we just dropped
        continue;
      }
      // note this handles when there is no after phid - we are at the top of
      // the list - quite nicely
      if (ii_prev_item_phid == after_phid) {
        before_phid = ii_item_phid;
        break;
      }
      ii_prev_item_phid = ii_item_phid;
    }
    if (before_phid) {
      data.beforePHID = before_phid;
    }

    data.order = config.order;

    var workflow = new JX.Workflow(config.moveURI, data)
      .setHandler(function(response) {
        onresponse(response, item, list);
      });

    workflow.start();
  }

  var lists = [];
  var ii;
  var cols = getcolumns();

  for (ii = 0; ii < cols.length; ii++) {
    var list = new JX.DraggableList('project-card', cols[ii])
      .setFindItemsHandler(JX.bind(null, finditems, cols[ii]));

    list.listen('didSend', JX.bind(list, onupdate, cols[ii]));
    list.listen('didReceive', JX.bind(list, onupdate, cols[ii]));

    list.listen('didDrop', JX.bind(null, ondrop, list));

    list.listen('didBeginDrag', JX.bind(null, onbegindrag));
    list.listen('didEndDrag', JX.bind(null, onenddrag));

    lists.push(list);

    onupdate(cols[ii]);
  }

  for (ii = 0; ii < lists.length; ii++) {
    lists[ii].setGroup(lists);
  }

  var onedit = function(column, r) {
    var new_card = JX.$H(r.tasks).getNode();
    var new_data = JX.Stratcom.getData(new_card);
    var items = finditems(column);
    var edited = false;

    for (var ii = 0; ii < items.length; ii++) {
      var item = items[ii];

      var data = JX.Stratcom.getData(item);
      var phid = data.objectPHID;

      if (phid == new_data.objectPHID) {
        items[ii] = new_card;
        data = new_data;
        edited = true;
      }

      data.sort = r.data.sortMap[data.objectPHID] || data.sort;
    }

    // this is an add then...!
    if (!edited) {
      items[items.length + 1] = new_card;
      new_data.sort = r.data.sortMap[new_data.objectPHID] || new_data.sort;
    }

    items.sort(colsort);

    JX.DOM.setContent(column, items);

    onupdate(column);
  };

  JX.Stratcom.listen(
    'click',
    ['edit-project-card'],
    function(e) {
      e.kill();
      var column = e.getNode('project-column');
      var request_data = {
        responseType: 'card',
        columnPHID: JX.Stratcom.getData(column).columnPHID,
        order: config.order
      };
      new JX.Workflow(e.getNode('tag:a').href, request_data)
        .setHandler(JX.bind(null, onedit, column))
        .start();
    });

  JX.Stratcom.listen(
    'click',
    ['column-add-task'],
    function (e) {

      // We want the 'boards-dropdown-menu' behavior to see this event and
      // close the dropdown, but don't want to follow the link.
      e.prevent();

      var column_phid = e.getNodeData('column-add-task').columnPHID;
      var request_data = {
        responseType: 'card',
        columnPHID: column_phid,
        projects: config.projectPHID,
        order: config.order
      };
      var cols = getcolumns();
      var ii;
      var column;
      for (ii = 0; ii < cols.length; ii++) {
        if (JX.Stratcom.getData(cols[ii]).columnPHID == column_phid) {
          column = cols[ii];
          break;
        }
      }
      new JX.Workflow(config.createURI, request_data)
        .setHandler(JX.bind(null, onedit, column))
        .start();
    });

});
