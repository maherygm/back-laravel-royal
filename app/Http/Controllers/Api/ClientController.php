<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\EditClientRequest;
use App\Models\Client;
use Exception;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Méthode pour afficher tous les clients
    public function index()
    {
        try {
            $clients =  Client::all();
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Tous les clients récupérés',
                'data' => $clients
            ]);
        } catch (Exception $erreur) {
            return response()->json($erreur->getMessage(), 500);
        }
    }

    // Méthode pour créer un nouveau client
    public function store(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:clients,email', // Validation de l'email
            'nom' => 'required|string|max:255',
            'prenom' => 'required|max:255',
            'mdp' => 'required|max:255',

        ]);

        try {
            // Récupération des données de la requête
            $data = $request->all();
            // Conversion du nom en majuscules si nécessaire
            $data['nom'] = mb_strtoupper($data['nom'], 'UTF-8');

            // Création du client
            $client = Client::create($data);

            // Retourner la réponse JSON avec succès
            return response()->json(['success' => true, 'message' => 'Client créé avec succès.', 'data' => $client], 201);
        } catch (\Exception $e) {
            // Retourner la réponse JSON en cas d'erreur
            return response()->json(['success' => false, 'message' => 'Erreur lors de la création du client.'], 500);
        }
    }

    public function edit(EditClientRequest $request, Client $client)
    {
        try {
            $client = Client::findOrFail($id);
            return response()->json(['success' => true, 'message' => 'Client trouvé avec succès.', 'data' => $client], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Client non trouvé.'], 404);
        }
    }

    // Méthode pour mettre à jour un client
    public function update(Request $request, $id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->update($request->all());
            return response()->json(['success' => true, 'message' => 'Client mis à jour avec succès.', 'data' => $client], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour du client.'], 500);
        }
    }

    // Méthode pour supprimer un client
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return response()->json(['success' => true, 'message' => 'Client supprimé avec succès.'], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression du client.'], 500);
        }
    }
}




