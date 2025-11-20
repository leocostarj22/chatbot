<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('id', auth()->user()->client_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:clients,slug'],
            'active' => ['nullable', 'boolean'],
        ]);

        $data['active'] = $data['active'] ?? true;

        $client = Client::create($data);

        return redirect()->route('clients.show', $client)->with('status', 'Cliente criado com sucesso.');
    }

    public function show(Client $client)
    {
        if ($client->id !== auth()->user()->client_id) {
            abort(403);
        }
        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        if ($client->id !== auth()->user()->client_id) {
            abort(403);
        }
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        if ($client->id !== auth()->user()->client_id) {
            abort(403);
        }
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('clients', 'slug')->ignore($client->id)],
            'active' => ['nullable', 'boolean'],
        ]);

        $client->update($data);

        return redirect()->route('clients.index')->with('status', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Client $client)
    {
        if ($client->id !== auth()->user()->client_id) {
            abort(403);
        }
        $client->delete();
        return redirect()->route('clients.index')->with('status', 'Cliente removido.');
    }
}