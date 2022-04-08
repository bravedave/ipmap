<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace bravedave\ipmap;

use strings;

?>
<div class="table-responsive">
  <table class="table table-sm" id="<?= $_uidMatrix = strings::rand() ?>">
    <thead class="small">
      <tr>
        <td>hostname</td>
        <td data-role="sort-header" data-key="order">ip/range</td>
        <td>description</td>
      </tr>
    </thead>

    <tbody></tbody>

  </table>
</div>
<script>
  (_ => {
    const edit = function() {
      let _me = $(this);
      let _dto = _me.data('dto');

      _.get.modal(_.url(`<?= $this->route ?>/edit/${_dto.id}`))
        .then(m => m.on('success', e => _me.trigger('refresh')));

    };

    const formatIP = dto => {
      let _ip = dto.ip;
      let _parts = _ip;

      if (_ip.indexOf('-') > -1) {
        _parts = _ip.split('-')[0];
      }

      if (_ip.indexOf('.') > -1) {
        let __ip = _parts.split('.');
        let a = [];
        __ip.forEach(element => {
          a.push(('000' + String(element).trim()).substr(-3));
        });

        _ip = a.join('.');

      }

      return _ip;

    }

    const matrix = data => {
      let table = $('#<?= $_uidMatrix ?>');
      let tbody = $('#<?= $_uidMatrix ?> > tbody');

      tbody.html('');
      $.each(data, (i, dto) => {
        $(`<tr class="pointer" data-order="${formatIP(dto)}">
          <td class="js-hostname">${dto.hostname}</td>
          <td class="js-ip">${dto.ip}</td>
          <td class="js-description">${dto.description}</td>
        </tr>`)
          .data('dto', dto)
          .on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();

            $(this).trigger('edit');

          })
          .on('edit', edit)
          .on('refresh', refresh)
          .appendTo(tbody);

      });

      _.table.sortOn(table, 'order', 'string', 'asc');

    };

    const refresh = function(e) {
      e.stopPropagation();

      let _me = $(this);
      let _dto = _me.data('dto');

      _.post({
        url: _.url('<?= $this->route ?>'),
        data: {
          action: 'get-by-id',
          id: _dto.id
        },

      }).then(d => {
        if ('ack' == d.response) {
          let dto = d.data;
          $('.js-hostname', _me).html(dto.hostname);
          $('.js-ip', _me).html(dto.ip);
          $('.js-description', _me).html(dto.description);
          _me.data('order', formatIP(dto))
          _me.data('dto', dto)

        } else {
          _.growl(d);

        }
      });
    };

    $('#<?= $_uidMatrix ?>')
      .on('refresh', function(e) {
        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'get-matrix'
          },
        }).then(d => {
          if ('ack' == d.response) {
            matrix(d.data);
          } else {
            _.growl(d);
          }
        });

      });

    $(document).on('ipmap-add-new', e => $('#<?= $_uidMatrix ?>').trigger('refresh'));
    $(document).ready(() => $('#<?= $_uidMatrix ?>').trigger('refresh'));

  })(_brayworth_);
</script>