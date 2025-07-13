<?php

namespace App\Controllers;

abstract class AbstractModel {
  abstract public function get($id);

  abstract public function getAll();

  abstract public function create($data);

  abstract public function update($id, $data);

  abstract public function delete($id);
}
