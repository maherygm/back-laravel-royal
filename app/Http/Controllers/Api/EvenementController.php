<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    // Méthode pour afficher tous les événements
    public function index()
    {
        try {
            $evenements = Evenement::all();
            return response()->json(['success' => true, 'message' => 'Liste des événements récupérée avec succès.', 'data' => $evenements], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la récupération des événements.'], 500);
        }
    }

    // Méthode pour créer un nouvel événement
    public function store(Request $request)
    {

        if ($request->has('validation')) {
            $request->merge(['validation' => $request->input('validation') === 'oui' ? true : false]);
        }

        // Validation des données de la requête
        $request->validate([
            'types' => 'nullable|string|max:255',
            'date_evenement' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'prix' => 'nullable|numeric',
            'validation' => 'nullable|boolean',
            'note_evenement' => 'nullable|integer',
            'client_id' => 'required|exists:clients,id',
        ]);

        try {
            // Création de l'événement
            $evenement = Evenement::create($request->all());

            // Retourner la réponse JSON avec succès
            return response()->json(['success' => true, 'message' => 'Événement créé avec succès.', 'data' => $evenement], 201);
        } catch (\Exception $e) {
            // Retourner la réponse JSON en cas d'erreur
            return response()->json(['success' => false, 'message' => 'Erreur lors de la création de l\'événement.'], 500);
        }
        
    }

    // Méthode pour afficher un événement spécifique
    public function show($id)
    {
        try {
            $evenement = Evenement::findOrFail($id);
            return response()->json(['success' => true, 'message' => 'Événement trouvé avec succès.', 'data' => $evenement], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Événement non trouvé.'], 404);
        }
    }

    // Méthode pour mettre à jour un événement
    public function update(Request $request, $id)
    {
        try {
            $evenement = Evenement::findOrFail($id);
            $evenement->update($request->all());
            return response()->json(['success' => true, 'message' => 'Événement mis à jour avec succès.', 'data' => $evenement], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'événement.'], 500);
        }
    }

    // Méthode pour supprimer un événement
    public function destroy($id)
    {
        try {
            $evenement = Evenement::findOrFail($id);
            $evenement->delete();
            return response()->json(['success' => true, 'message' => 'Événement supprimé avec succès.'], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression de l\'événement.'], 500);
        }
    }
}
