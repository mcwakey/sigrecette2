<?php

namespace App\Livewire\StockRequest;

use App\Models\StockRequest;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AddStatusForm extends Component
{
    //use WithFileUploads;
    use DispatchesMessages;

    public $request_id;

    public $status;

    public $edit_mode = false;

    protected $rules = [
        "status" =>"required",
    ];

    protected $listeners = [
        //'delete_user' => 'deleteUser',
        'update_request_status' => 'updateRequestStatus',
        //'add_request' => 'addrequest',
    ];
    public function render()
    {
        return view('livewire.stock_request.add-status-form');
    }

    public function submit()
    {


        // Validate the form input data
        $this->validate();

        DB::transaction(function () {

            // Prepare data for request
            $data = [
                'type' => $this->status,
            ];



            // Create or update request record
            $requests = StockRequest::where("req_id", $this->request_id)->get(); //?? request::create($request_id);

            foreach ($requests as $request) {
                $request->update($data);
            }

            $this->dispatchMessage('Paiement', 'update');
        });

        $this->reset();
    }



    public function updateRequestStatus($id)
    {
        $request = StockRequest::find($id);

        $this->request_id = $request->req_id;
        $this->status = $request->stock_request;


    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}

