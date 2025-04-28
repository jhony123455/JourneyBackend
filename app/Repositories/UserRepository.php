<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['fecha_registro'] = now();
        
        return $this->model->create($data);
    }

    public function findByName(string $name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }
}