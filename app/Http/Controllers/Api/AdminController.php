<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Méthode pour afficher tous les admins
    public function index()
    {
        try {
            $admins = Admin::all();
            return response()->json(['success' => true, 'message' => 'Liste des admins récupérée avec succès.', 'data' => $admins], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la récupération des admins.'], 500);
        }
    }

    // Méthode pour créer un nouveau admin
    public function store(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|max:255',
            'mdp' => 'required|max:255',
        ]);

        try {
            // Récupération des données de la requête
            $data = $request->all();
            // Conversion du nom en majuscules si nécessaire
            $data['nom'] = mb_strtoupper($data['nom'], 'UTF-8');

            // Création de l'admin
            $admin = Admin::create($data);

            // Retourner la réponse JSON avec succès
            return response()->json(['success' => true, 'message' => 'Admin créé avec succès.', 'data' => $admin], 201);
        } catch (\Exception $e) {
            // Retourner la réponse JSON en cas d'erreur
            return response()->json(['success' => false, 'message' => 'Erreur lors de la création de l\'admin.'], 500);
        }
    }

    // Méthode pour afficher un admin spécifique
    public function show($id)
    {
        try {
            $admin = Admin::findOrFail($id);
            return response()->json(['success' => true, 'message' => 'Admin trouvé avec succès.', 'data' => $admin], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Admin non trouvé.'], 404);
        }
    }

    // Méthode pour mettre à jour un admin
    public function update(Request $request, $id)
    {
        try {
            $admin = Admin::findOrFail($id);
            $admin->update($request->all());
            return response()->json(['success' => true, 'message' => 'Admin mis à jour avec succès.', 'data' => $admin], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'admin.'], 500);
        }
    }

    // Méthode pour supprimer un admin
    public function destroy($id)
    {
        try {
            $admin = Admin::findOrFail($id);
            $admin->delete();
            return response()->json(['success' => true, 'message' => 'Admin supprimé avec succès.'], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression de l\'admin.'], 500);
        }
    }
}
