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
                //'r_user_id'=>  Auth::id()
            ];

            //dd($requestData);

            // Create or update request record
            $requests = StockRequest::where("req_id", $this->request_id)->get(); //?? request::create($request_id);

            foreach ($requests as $request) {
                $request->update($data);
            }
            //$this->request_id = $request->id;

            // foreach ($data as $k => $v) {
            //     $request->$k = $v;
            // }
            // $request->save();
                //$this->dispatch('success', __('request updated'));
            $this->dispatchMessage('Paiement', 'update');
        });

        // Reset form fields after successful submission
        $this->reset();
    }

// public function updaterequest($id)
// {
//     $this->edit_mode = true;

//     $this->request_id = $request->id;
//     $this->tnif = $request->taxpayer->tnif;
//     $this->zone = $request->taxpayer->zone_id;
// }

    public function updateRequestStatus($id)
    {
        $request = StockRequest::find($id);

        $this->request_id = $request->req_id;
        $this->status = $request->stock_request;

        //$this->$request = $request;

        //dd($this->request_id);

    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}

