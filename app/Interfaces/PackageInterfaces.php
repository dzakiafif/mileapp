<?php

namespace App\Interfaces;

interface PackageInterfaces {

    public function createPackage($request);

    public function updatePackage($request, $id);

    public function deletePackage($id);

    public function getAllPackage($limit, $order, $direction);

    public function showPackage($id);
}