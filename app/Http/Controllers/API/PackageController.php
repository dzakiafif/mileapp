<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePackageRequest;
use App\Libraries\ResponseBase;
use App\Models\Transaction;
use App\Repository\PackageRepository;
use Illuminate\Http\Request;

class PackageController extends Controller
{

    private $packageRepository;

    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function index(Request $request) 
    {
        try {

            $limit      = $request->limit ? : 10;
            $order      = $request->order ? : 'created_at';
            $direction  = $request->direction ? : 'desc';

            $package = $this->packageRepository->getAllPackage($limit, $order, $direction);

            $response = [
                'data' => !empty($package['data']) ? $package['data'] : [],
                'links' => [
                    'first' => $package['first_page_url'],
                    'last' => $package['last_page_url'],
                    'prev' => $package['prev_page_url'],
                    'next' => $package['next_page_url']
                ],
                'meta' => [
                    'current_page' => $package['current_page'],
                    'from' => $package['from'],
                    'last_page' => $package['last_page'],
                    'per_page' => $package['per_page'],
                    'to' => $package['to'],
                    'total' => $package['total']
                ]
            ];

            return ResponseBase::success($response);

        } catch (\Exception $e) {
            return ResponseBase::error(400, $e->getMessage());
        }
    }

    public function store(StorePackageRequest $request)
    {
        try {

            $package = $this->packageRepository->createPackage($request);

            $response = [
                'data' => $package
            ];

            return ResponseBase::success($response);

        } catch (\Exception $e) {
            return ResponseBase::error(400, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $package = $this->packageRepository->showPackage($id);
            if(!$package['status']) {
                throw new \Exception($package['message']);
            }

            $response = [
                'data' => $package['data']
            ];

            return ResponseBase::success($response);

        } catch (\Exception $e) {
            return ResponseBase::error(400, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $package = $this->packageRepository->updatePackage($request, $id);
            if (!$package['status'])
            {
                throw new \Exception($package['message']);
            }

            $response = [
                'data' => $package['data']
            ];

            return ResponseBase::success($response);

        } catch (\Exception $e) {
            return ResponseBase::error(400, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $package = $this->packageRepository->deletePackage($id);
            if (!$package['status']) {
                throw new \Exception($package['message']);
            }

            $response = [
                'message' => "data package with ${id} successfully deleted"
            ];

            return ResponseBase::success($response);
        } catch (\Exception $e) {
            return ResponseBase::error(400, $e->getMessage());
        }
    }
}
