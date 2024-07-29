<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //display contacts
    public function index()
    {
        try {
            $user = Auth::user();
            $contacts = Contact::where('userID', $user->id)->paginate(5);
            return view('contacts', ['contacts' => $contacts]);
        } catch (\Exception $e) {
            return redirect('contacts')->with('error', 'Error fetching contacts.');
        }
    }

    //display add contact form
    public function displayAddContact()
    {
        return view('contact.addContact');
    }

    //add contact
    public function addContact(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'company' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('contacts/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $contact = new Contact;
            $contact->userID = $user->id;
            $contact->name = $request->name;
            $contact->company = $request->company;
            $contact->phone = $request->phone;
            $contact->email = $request->email;
            $contact->save();
            return redirect('contacts')->with('success', 'Contact successfully added.');
        } catch (\Exception $e) {
            return redirect('contacts/add')
                ->withErrors($validator)
                ->withInput();
        }
    }

    //display edit contact form
    public function editContact(string $id)
    {
        $contact = Contact::find($id);
        return view('contact.editContact', compact('contact'));
    }

    //update contact
    public function updateContact(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'company' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('contacts.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $contact = Contact::find($id);
            $contact->update($request->all());
            return redirect('contacts');
        } catch (\Exception $e) {
            return redirect()->route('contacts.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }
    }

    //delete contact
    public function deleteContact(string $id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();
            return redirect()->route('contacts')->with('success', 'Contact successfully deleted.');
        } catch (\Exception $e) {
            return redirect()->route('contacts')->with('error', 'Contact not found.');
        }
    }

    //search func
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query', '');
            $userID = Auth::id();

            $contacts = Contact::where('userID', $userID)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', '%' . $query . "%")
                        ->orWhere('company', 'LIKE', '%' . $query . "%")
                        ->orWhere('phone', 'LIKE', '%' . $query . "%")
                        ->orWhere('email', 'LIKE', '%' . $query . "%");
                })
                ->get();

            return response()->json($contacts);
        }
    }
}
