<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    protected $fillable = [
        'username', 'foods_id', 'qty'
    ];

    public function isExists($username,$foods_id) {
        $data = $this->where('username', $username)->where('foods_id', $foods_id)->first();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    public function isExistsById($id) {
        $data = $this->find($id);
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    public function isExistsByUsername($username) {
        $data = $this->where('username', $username)->first();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }
}