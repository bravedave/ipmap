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

use Json;
use strings;

class controller extends \Controller {
  protected function _index() {
    $this->render([
      'title' => $this->title = config::label,

      'primary' => ['matrix'],  /* load the matrix view */

      'secondary' => ['index'],
      'data' => (object)[
        'searchFocus' => true,
        'pageUrl' => strings::url($this->route)
      ]
    ]);
  }

  protected function before() {
    config::ipmap_checkdatabase();  // add this line
    parent::before();

    $this->viewPath[] = __DIR__ . '/views/';  // location for module specific views
  }

  protected function postHandler() {
    $action = $this->getPost('action');

    if ('delete' == $action) {
      /*
        (_ => {
          _.post({
            url: _.url('ipmap'),
            data: {
              action: 'delete',
              id : 1
            },
          }).then(d => {
            if ('ack' == d.response) {
              console.log(d.data);
            } else {
              _.growl(d);
            }
          });

        })(_brayworth_);
       */
      if ($id = (int)$this->getPost('id')) {
        $dao = new dao\ipmap;
        $dao->delete($id);
        Json::ack($action);
      } else {
        Json::nak($action);
      }
    } elseif ('get-by-id' == $action) {
      /*
        (_ => {
          _.post({
            url: _.url('ipmap'),
            data: {
              action: 'get-by-id',
              id : 1
            },
          }).then(d => {
            if ('ack' == d.response) {
              console.log(d.data);
            } else {
              _.growl(d);
            }
          });

        })(_brayworth_);
       */
      if ($id = (int)$this->getPost('id')) {
        $dao = new dao\ipmap;
        if ($dto = $dao->getByID($id)) {
          Json::ack($action)
            ->add('data', $dto);
        } else {
          Json::nak($action);
        }
      } else {
        Json::nak($action);
      }
    } elseif ('get-matrix' == $action) {
      /*
        (_ => {
          _.post({
            url: _.url('ipmap'),
            data: {
              action: 'get-matrix'
            },
          }).then(d => {
            if ('ack' == d.response) {
              console.table(d.data);
            } else {
              _.growl(d);
            }
          });

        })(_brayworth_);
       */
      $dao = new dao\ipmap;
      Json::ack($action)
        ->add('data', $dao->getMatrix());
    } elseif ('ipmap-save' == $action) {
      $a = [
        'hostname' => $this->getPost('hostname'),
        'ip' => $this->getPost('ip'),
        'description' => $this->getPost('description')
      ];

      $dao = new dao\ipmap;
      if ($id = (int)$this->getPost('id')) {
        $dao->UpdateByID($a, $id);
      } else {
        $dao->Insert($a);
      }

      Json::ack($action); // json return { "response": "ack", "description" : "ipmap-save" }
    } else {
      parent::postHandler();
    }
  }

  public function edit($id = 0) {
    // tip : the structure is available in the view at $this->data->dto
    $this->data = (object)[
      'title' => $this->title = config::label,
      'dto' => new dao\dto\ipmap
    ];

    if ($id = (int)$id) {
      $dao = new dao\ipmap;
      $this->data->dto = $dao->getByID($id);
      $this->data->title .= ' edit';
    }

    $this->load('edit');
  }
}
